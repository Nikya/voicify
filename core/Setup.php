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

	private static $ok = true;

	/***************************************************************************
	* To check if the full modules already exits
	*/
	public static function check() {
		return file_exists(self::PATH_MODULES);
	}

	/***************************************************************************
	* To create the full Modules file
	*/
	public static function exec() {
		if (!is_dir(self::PATH_CONFIG))
			throw new Exception("Config folder not exists : " . self::PATH_CONFIG);

		if (!is_writable(self::PATH_CONFIG))
			throw new Exception("Config folder is not writable : " . self::PATH_CONFIG);

		@$featureDirContent = scandir(self::PATH_FEATURE_MODULE);
		@$ttsengineDirContent  = scandir(self::PATH_TTSENGINE_MODULE);

		if ($featureDirContent===false)
			throw new Exception("Module feature folder not found : " . self::PATH_FEATURE_MODULE);

		if ($ttsengineDirContent===false)
			throw new Exception("Module TTS engine folder not found : " . self::PATH_TTSENGINE_MODULE);

		foreach ($featureDirContent as $c) {
			if (!in_array($c, self::EXEPTION_PATH))
				self::readModule($c, 'FEATURE');
		}

		foreach ($ttsengineDirContent as $c) {
			if (!in_array($c, self::EXEPTION_PATH))
				self::readModule($c, 'TTSENGINE');
		}

		if (self::$ok and count(self::$modules['FEATURE'])>0 and count(self::$modules['TTSENGINE'])>0) {
			self::save();
			CoreUtils::consoleI('setup', 'Setup sucessful ! Feature Modules x' . count(self::$modules['FEATURE']) . ', TTS engine Modules x' . count(self::$modules['TTSENGINE']));
		}
		else
			CoreUtils::consoleE('setup', "Setup fail, correct problems and run it again");
	}

	/***************************************************************************
	* To read and check one module
	*/
	private static function readModule($id, $type) {
		$path = "/??/";

		if ($type == "FEATURE")
			$path = self::PATH_FEATURE_MODULE;
		else if ($type == "TTSENGINE")
			$path = self::PATH_TTSENGINE_MODULE;

		try {
			if (!is_dir($path.$id))
				throw new Exception("Is not a directory");

			if (!file_exists($path.$id.'/README.md'))
				throw new Exception("No 'Readme' found");

			if (!file_exists($path.$id.'/manifest.json'))
				throw new Exception("No 'Manifest' found");

			$module = self::readManifest($id, $path);

			self::$modules[$type][$id] = $module;

			CoreUtils::consoleI('setup.readModule', "$type module '$id' loaded : {$module['name']} - {$module['desc']}");
		} catch (Exception $e) {
			self::$ok = false;
			CoreUtils::consoleW('setup.readModule', "Fail to load the $type module '$id' : {$e->getMessage()} - in '$path$id' ", $e);
		}
	}

	/***************************************************************************
	* To extract a name and a description from the README file
	*/
	private static function readManifest($id, $path) {
		try {
			$module = array(
				'name' => array(),
				'desc' => array(),
				'play' => array(),
				'configurator' => array(),
				'configfile' => array()
			);

			$jManifest = JsonUtils::jFile2Array("$path/$id/manifest.json");

			// title
			if (array_key_exists('name', $jManifest))
				$module['name'] = trim($jManifest['name']);
			else
				throw new Exception("No name found");

			// description
			if (array_key_exists('desc', $jManifest))
				$module['desc'] = trim($jManifest['desc']);
			else
				throw new Exception("No description found");

			// play
			if (array_key_exists('play', $jManifest)) {
				foreach ($jManifest['play'] as $play) {
					$playFile = "$path$id/$play.php";
					$playApiFile = "$path$id/${play}Api.php";
					if (file_exists($playFile) and file_exists($playApiFile))
						array_push($module['play'], $play);
					else
						throw new Exception("Invalid PLAY. A file is missing '$playFile', '$playApiFile'");
				}
			}

			// configurator
			if (array_key_exists('configurator', $jManifest)) {
				foreach ($jManifest['configurator'] as $configurator) {
					$configuratorFile = "$path$id/$configurator.php";
					$configuratorApiFile = "$path$id/${configurator}Api.php";
					if (file_exists($configuratorFile) and file_exists($configuratorApiFile))
						array_push($module['configurator'], $configurator);
					else
						throw new Exception("Invalid CONFIGURATOR. A file is missing '$configuratorFile', '$configuratorApiFile'");
				}
			}

			// configfile
			if (array_key_exists('configfile', $jManifest)) {
				foreach ($jManifest['configfile'] as $configfileName) {
					$configFile = "$path$id/$configfileName.json";
					if (file_exists($configFile)){
						self::loadDefaultConfig($id, $configFile, $configfileName);
						array_push($module['configfile'], $configfileName);
					}
					else
						throw new Exception("Invalid CONFIG FILE. File missing '$configFile'");
				}
			}

			return $module;
		} catch (Exception $e) {
			throw new Exception("Fail to read Manifest - " . $e->getMessage(), -1, $e);
		}
	}

	/***************************************************************************
	* To read and check one module
	*/
	private static function loadDefaultConfig($id, $path, $configfile) {
		$cFrom = $path;
		$cTo = self::PATH_CONFIG.$id.'_'.$configfile.'.json';

		if(!file_exists($cTo)) {
			@$res = copy($cFrom, $cTo);
			if ($res===FALSE)
				throw new Exception("Fail to load default config file FROM '$cFrom' TO '$cTo' ");
		}
	}

	/***************************************************************************
	* To save the full modules into the file
	*/
	private static function save() {
		JsonUtils::array2JFile(self::$modules, self::PATH_MODULES);
	}
}
