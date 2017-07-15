<?php

/*******************************************************************************
* Manage Module loading
*/
class Setup {

	/** Exluding path not a valid Module */
	private static $exeptionPath = array(".", "..", "_template");

	/** All Know Modules */
	private static $manifestMain = array();

	/** Global Setup status */
	private static $runOk = true;

	/***************************************************************************
	* To check if the full modules already exits : Setup already done
	*/
	public static function isOk() {
		if (!file_exists(CoreUtils::PATH_MANIFEST_MAIN))
			return false;
		else {
			$mmVersion = Config::getInstance()->getManifestMainVersion();
			if (strcasecmp($mmVersion, CoreUtils::VERSION)==0)
				return true;
			else {
				Console::e('setup.isOk', "Manifest Version '$mmVersion' is different than expected CORE version '".CoreUtils::VERSION."'");
				return false;
			}
		}

	}

	/***************************************************************************
	* Execute the full setup (create the full Modules file)
	*/
	public static function exec() {
		// Delete file
		@unlink(CoreUtils::PATH_MANIFEST_MAIN);

		if (!extension_loaded('intl'))
			throw new Exception("Mandatory internationalization extension, named 'intl', is not available. Install it. See http://php.net/manual/intl.installation.php");

		if (!extension_loaded('curl'))
			throw new Exception("Mandatory libcurl extension, named 'curl', is not available. Install it. See http://php.net/manual/curl.setup.php");

		if (!is_dir(CoreUtils::PATH_CONFIG))
			throw new Exception("Config folder not exists : " . CoreUtils::PATH_CONFIG);

		if (!is_writable(CoreUtils::PATH_CONFIG))
			throw new Exception("Config folder is not writable : " . CoreUtils::PATH_CONFIG);

		if (!is_dir(CoreUtils::PATH_TEMP))
			throw new Exception("Temp folder not exists : " . CoreUtils::PATH_TEMP);

		if (!is_writable(CoreUtils::PATH_TEMP))
			throw new Exception("Temp folder is not writable : " . CoreUtils::PATH_TEMP);

		@$dirContent = scandir(CoreUtils::PATH_MODULE);

		if ($dirContent===false)
			throw new Exception("Module folder not found : " . CoreUtils::PATH_MODULE);

		if (!Console::isDebug())
			array_push(self::$exeptionPath, 'moduleTemplate');

		foreach ($dirContent as $c) {
			if (!in_array($c, self::$exeptionPath))
				self::readModule($c);
		}

		//Console.d('setup', 'Final main manifest', self::$manifestMain);
		$cntTotal = count(self::$manifestMain);

		if (self::$runOk and $cntTotal>0) {
			self::$manifestMain['version'] = CoreUtils::VERSION;
			self::save();
			Console::i('setup', "Setup sucessful ! x$cntTotal modules found and loaded.");
		}
		else
			Console::e('setup', "Setup fail, correct problems and run it again");
	}

	/***************************************************************************
	* To read and check one module
	*/
	private static function readModule($id) {
		$path = CoreUtils::PATH_MODULE.$id;
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

			if ($type == CoreUtils::MODULE_T_TTSENGINE and !file_exists("$path/pTtsEngineApi.php"))
					throw new Exception("No 'pTtsEngineApi' found");

			self::$manifestMain[$id] = $module;

			Console::i('setup.readModule', "The $type module '$id' is loaded and installed");
		} catch (Exception $e) {
			self::$runOk = false;
			Console::w('setup.readModule', "Fail to load the module '$id' : in '$path$id' ", $e);
		}
	}

	/***************************************************************************
	* To extract a name and a description from the README file
	*/
	private static function readManifest($id, $path) {
		try {
			$module = array(
				'play' => array(),
				'config' => array(),
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
			//var_dump(CoreUtils::MODULE_T_FEATURE); exit;
			if (strcmp($module['type'], CoreUtils::MODULE_T_FEATURE)!=0 and strcmp($module['type'], CoreUtils::MODULE_T_TTSENGINE)!=0)
				throw new Exception("Invalid module type {$module['type']}");

			self::readManifestTarget('play', $path, $module, $inManifest);
			self::readManifestTarget('config', $path, $module, $inManifest);
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
		$cTo = CoreUtils::PATH_CONFIG.$id.'_'.$configfile.'.json';

		if(!file_exists($cTo)) {
			@$res = copy($cFrom, $cTo);
			if ($res===FALSE)
				throw new Exception("Fail to load default config file FROM '$cFrom' TO '$cTo' ");
			@$res = chmod($cTo, 0666);
			if ($res===FALSE)
				throw new Exception("Fail to load default config file FROM '$cFrom' TO '$cTo' ");
		}
	}

	/***************************************************************************
	* To save the full modules into the file
	*/
	private static function save() {
		JsonUtils::array2JFile(self::$manifestMain, CoreUtils::PATH_MANIFEST_MAIN);
	}
}
