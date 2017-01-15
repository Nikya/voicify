<?php

/*******************************************************************************
* Manage Module loading
*/
class Setup {

	/** Path to the config Dir */
	const PATH_CONFIG = './config/';

	/** Path to the full Modules */
	const PATH_MANIFEST_MAIN = self::PATH_CONFIG . 'manifest_main.json';

	/** Path to modules */
	const PATH_MODULE = './module/';

	/** Exluding path not a valid Module */
	const EXEPTION_PATH = array(".", ".."/*, "_template"*/);

	/** All Know Modules */
	private static $manifestMain = array(
		CoreUtils::MODULE_T_FEATURE => array(),
		CoreUtils::MODULE_T_TTSENGINE => array(),
	);

	/** Global Setup status */
	private static $runOk = true;

	/***************************************************************************
	* To check if the full modules already exits : Setup already done
	*/
	public static function isOk() {
		return file_exists(self::PATH_MANIFEST_MAIN);
	}

	/***************************************************************************
	* Execute the full setup (create the full Modules file)
	*/
	public static function exec() {
		// Delete file
		@unlink(self::PATH_MANIFEST_MAIN);

		if (!extension_loaded('intl'))
			throw new Exception("Mandatory internationalization extension is not available. Install it. See http://php.net/manual/fr/intl.installation.php");

		if (!is_dir(self::PATH_CONFIG))
			throw new Exception("Config folder not exists : " . self::PATH_CONFIG);

		if (!is_writable(self::PATH_CONFIG))
			throw new Exception("Config folder is not writable : " . self::PATH_CONFIG);

		@$dirContent = scandir(self::PATH_MODULE);

		if ($dirContent===false)
			throw new Exception("Module folder not found : " . self::PATH_MODULE);

		foreach ($dirContent as $c) {
			if (!in_array($c, self::EXEPTION_PATH))
				self::readModule($c, 'FEATURE');
		}

		//CoreUtils::consoleD('setup', 'Final main manifest', self::$manifestMain);
		$cntFeature = count(self::$manifestMain['FEATURE']);
		$cntTtsengine = count(self::$manifestMain['TTSENGINE']);

		if (self::$runOk and count(self::$manifestMain['FEATURE'])>0 and count(self::$manifestMain['TTSENGINE'])>0) {
			self::save();
			CoreUtils::consoleI('setup', "Setup sucessful ! Feature Modules x$cntFeature, TTS engine Modules x$cntTtsengine.");
		}
		else
			CoreUtils::consoleE('setup', "Setup fail, correct problems and run it again");
	}

	/***************************************************************************
	* To read and check one module
	*/
	private static function readModule($id) {
		$path = self::PATH_MODULE.$id;
		$type = 'UNKNOW_TYPE';

		try {
			if (!is_dir($path))
				throw new Exception("Is not a directory");

			if (!file_exists("$path/README.md"))
				throw new Exception("No 'Readme' found");

			if (!file_exists("$path/manifest.json"))
				throw new Exception("No 'Manifest' found");

			$module = self::readManifest($id, $path);
			$type = $module['type'];

			self::$manifestMain[$type][$id] = $module;

			CoreUtils::consoleI('setup.readModule', "The $type module '$id' is loaded");
		} catch (Exception $e) {
			self::$runOk = false;
			CoreUtils::consoleW('setup.readModule', "Fail to load the module '$id' : {$e->getMessage()} - in '$path$id' ", $e);
		}
	}

	/***************************************************************************
	* To extract a name and a description from the README file
	*/
	private static function readManifest($id, $path) {
		try {
			$module = array(
				'player' => array(),
				'configurator' => array(),
				'configfile' => array()
			);

			$inManifest = JsonUtils::jFile2Array("$path/manifest.json");

			self::readManifestValue('type', $module, $inManifest);
			self::readManifestValue('name', $module, $inManifest);
			self::readManifestValue('desc', $module, $inManifest);
			self::readManifestValue('version', $module, $inManifest);
			self::readManifestValue('author', $module, $inManifest);
			self::readManifestValue('sourcelink', $module, $inManifest);

			// type
			if (!array_key_exists($module['type'], self::$manifestMain))
				throw new Exception("Invalid module type {$module['type']}");

			self::readManifestTarget('player', $path, $module, $inManifest);
			self::readManifestTarget('configurator', $path, $module, $inManifest);
			self::readManifestConfigfiles($id, $path, $module, $inManifest);

			return $module;
		} catch (Exception $e) {
			throw new Exception("Fail to read Manifest - " . $e->getMessage(), -1, $e);
		}
	}

	/***************************************************************************
	* To extract a value from the manifest
	*/
	private static function readManifestValue($vId, &$module, $inManifest) {
		if (array_key_exists($vId, $inManifest))
			$module[$vId] = trim($inManifest[$vId]);
		else
			throw new Exception("No $vId found");
	}

	/***************************************************************************
	* To extract targets from the manifest
	*/
	private static function readManifestTarget($tId, $path, &$module, $inManifest) {
		if (array_key_exists($tId, $inManifest)) {
			foreach ($inManifest[$tId] as $targetId => $targetInInfo) {
				// Name
				if (!array_key_exists('name', $targetInInfo))
					throw new Exception("No $tId name found");

				// desc
				if (!array_key_exists('desc', $inManifest))
					throw new Exception("No $tId desc found");

				$targetInfo = array('name'=>$targetInInfo['name'], 'desc'=>$targetInInfo['desc']);

				// Files
				$targetFile = "$path/$targetId.php";
				$targetApiFile = "$path/{$targetId}Api.php";
				if (!file_exists($targetFile) or !file_exists($targetApiFile))
					throw new Exception("Invalid $tId. A file is missing '$targetFile', '$targetApiFile'");

				$module[$tId][$targetId] = $targetInfo;
			}
		}
	}

	/***************************************************************************
	* To extract config files from the manifest
	*/
	private static function readManifestConfigfiles($id, $path, &$module, $inManifest) {
		if (array_key_exists('configfile', $inManifest)) {
			foreach ($inManifest['configfile'] as $configfileName) {
				$configFile = "$path/$configfileName.json";
				if (file_exists($configFile)){
					self::loadDefaultConfig($id, $configFile, $configfileName);
					array_push($module['configfile'], $configfileName);
				}
				else
					throw new Exception("Invalid CONFIG FILE. File missing '$configFile'");
			}
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
		JsonUtils::array2JFile(self::$manifestMain, self::PATH_MANIFEST_MAIN);
	}
}
