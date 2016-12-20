<?php

/*******************************************************************************
* Manage Module loading
*/
class Setup {

	/** Path to the config Dir */
	const PATH_CONFIG = './config/';

	/** Path to the full Modules */
	const PATH_MODULES = self::PATH_CONFIG . 'modules.json';

	/** Path to modules */
	const PATH_MODULE = './module/';

	/** Path to a feature modules */
	const PATH_FEATURE_MODULE = self::PATH_MODULE.'feature/';

	/** Path to a TTS engine modules */
	const PATH_TTSENGINE_MODULE = self::PATH_MODULE.'ttsengine/';

	const EXEPTION_PATH = array(".", "..");

	/** All Know Modules */
	private static $modules = array(
		'feature' => array(),
		'ttsengine' => array(),
	);

	/** Setup repporting state */
	private static $repport = array(
		'ok' => array(),
		'ko' => array()
	);

	/***************************************************************************
	* To check if the full modules already exits
	*/
	public static function check() {
		return file_exists(self::PATH_MODULES);
	}

	/***************************************************************************
	* To check if there is a setup error recorded
	*/
	private static function isNoError() {
		return count(self::$repport['ko'])==0 and count(self::$repport['ok'])>0;
	}

	/***************************************************************************
	* To create the full Modules file
	*/
	public static function exec() {

		@$featureDirContent = scandir(self::PATH_FEATURE_MODULE);
		@$ttsengineDirContent  = scandir(self::PATH_TTSENGINE_MODULE);

		if ($featureDirContent===false)
			throw new Exception("Module feature folder not found : " . self::PATH_FEATURE_MODULE);

		if ($ttsengineDirContent===false)
			throw new Exception("Module TTS engine folder not found : " . self::PATH_TTSENGINE_MODULE);

		foreach ($featureDirContent as $c) {
			if (!in_array($c, self::EXEPTION_PATH))
				self::readFeatureModule($c);
		}

		foreach ($ttsengineDirContent as $c) {
			if (!in_array($c, self::EXEPTION_PATH))
				self::readTtsengineModule($c);
		}

		if (self::isNoError())
			self::save();

		return self::$repport;
	}

	/***************************************************************************
	* To read and check one module
	*/
	private static function readFeatureModule($id) {
		try {
			if (!is_dir(self::PATH_FEATURE_MODULE.$id))
				throw new Exception("Is not a directory");
			if (!file_exists(self::PATH_FEATURE_MODULE.$id.'/README.md'))
				throw new Exception("No 'Readme' found");

			$module = self::readhim(self::PATH_FEATURE_MODULE.$id.'/README.md');

			if (self::isNoError())
				self::loadDefaultConfig($id, self::PATH_FEATURE_MODULE.$id);

			self::$modules['feature'][$id] = $module;

			array_push(self::$repport['ok'], "Feature module '$id' loaded : ${module['title']} - ${module['desc']}");
		} catch (Exception $e) {
			array_push(self::$repport['ko'], "Fail to load the feature Module '$id' : " . $e->getMessage());
		}
	}

	/***************************************************************************
	* To read and check one module
	*/
	private static function readTtsengineModule($id) {
		try {
			if (!is_dir(self::PATH_TTSENGINE_MODULE.$id))
				throw new Exception("Is not a directory");
			if (!file_exists(self::PATH_TTSENGINE_MODULE.$id.'/README.md'))
				throw new Exception("No 'Readme' found");

			$module = self::readhim(self::PATH_TTSENGINE_MODULE.$id.'/README.md');
			self::loadDefaultConfig($id, self::PATH_TTSENGINE_MODULE.$id);

			self::$modules['ttsengine'][$id] = $module;

			array_push(self::$repport['ok'], "Ttsengine module '$id' loaded : ${module['title']} - ${module['desc']}");
		} catch (Exception $e) {
			array_push(self::$repport['ko'], "Fail to load the TTS engine Module '$id' : " . $e->getMessage());
		}
	}

	/***************************************************************************
	* To extract a name and a description from the README file
	*/
	private static function readhim($path) {
		$module = array(
			'title' => array(),
			'desc' => array(),
		);
		$c = file_get_contents($path);

		if ($c===false)
			throw new Exception("Fail to read the file $path");

		// TITLE
		$matches = array();
		$res = preg_match (':#{1}(.*):', $c, $matches);
		if ($res !== false and $res==1)
			$module['title'] = trim($matches[1]);
		else
			throw new Exception("No title found in the readme ! (# My title) PREG#".preg_last_error());

		// description
		$matches = array();
		$res = preg_match (':_{1}(.*)_{1}:', $c, $matches);
		if ($res !== false and $res==1)
			$module['desc'] = trim($matches[1]);
		else
			throw new Exception("No description found in the readme ! (_ My description_) PREG#".preg_last_error());

		return $module;
	}

	/***************************************************************************
	* To read and check one module
	*/
	private static function loadDefaultConfig($id, $path) {
		@$dirContent = scandir($path);

		if ($dirContent===false)
			throw new Exception("Fail to scan the directory");

		foreach ($dirContent as $c) {
			if (strcasecmp(substr($c, -5, 5), '.json')==0) {
				$cFrom = $path.'/'.$c;
				$cTo = self::PATH_CONFIG.$id.'_'.$c;

				if(!file_exists($cTo)) {
					@$res = copy($cFrom, $cTo);
					if ($res===TRUE)
						array_push(self::$repport['ok'], "Default config file loaded '$cTo'");
					else
						throw new Exception("Fail to load default config file FROM '$cFrom' TO '$cTo' ");
				}
			}
		}
	}

	/***************************************************************************
	* To save the full modules into the file
	*/
	private static function save() {
		JsonUtils::array2JFile(self::$modules, self::PATH_MODULES);
	}
}
