<?php

/*******************************************************************************
* Utils to read Config files and Main Manifest
*/
class Config {

	/** The unique one Singleton instance */
	private static $instance = null;

	/** To store already read module config */
	private $moduleConfig = array();

	/** To store the main manifest */
	private $manifestMain = null;

	/***************************************************************************
	* Get the singleton instance
	*/
	public static function getInstance() {
		if (self::$instance==null) {
			self::$instance = new Config();
		}
		return self::$instance;
	}

	/***************************************************************************
	* Private constructor
	*/
	private function __construct() {
		$this->loadManifestMain();
	}

	/***************************************************************************
	* Read the manifest file
	*/
	private function loadManifestMain() {
		$this->manifestMain = JsonUtils::jFile2Array(CoreUtils::PATH_MANIFEST_MAIN);
	}

	/***************************************************************************
	* Return the full manifest file
	*/
	public function getManifestMain() {
		return $this->manifestMain;
	}

	/***************************************************************************
	* Return only a target typed module manifest
	*/
	public function getSubManifestTT($targetType) {
		$manifestSub = array();

		foreach ($this->manifestMain as $mId => $module) {
			if(count($module[$targetType])>0)
				$manifestSub[$mId] = $module;
		}

		return $manifestSub;
	}

	/***************************************************************************
	* Return only a module typed module manifest
	*/
	public function getSubManifestMT($modulesType) {
		$manifestSub = array();

		foreach ($this->manifestMain as $mId => $module) {
			if($module['type'] == $modulesType)
				$manifestSub[$mId] = $module;
		}

		return $manifestSub;
	}

	/***************************************************************************
	* Return a target typed and module typed manifest
	*/
	public function getSubManifestTT_MT($targetType, $modulesType) {
		$manifestSub = array();

		foreach ($this->manifestMain as $mId => $module) {
			if($module['type'] == $modulesType)
				if(count($module[$targetType])>0)
					$manifestSub[$mId] = $module;
		}

		return $manifestSub;
	}

	/***************************************************************************
	* Return true if the module existe
	*
	* @param $targetType
	* @param $moduleId
	* @param $subModuleId
	*/
	public function isValidModule($targetType, $moduleId, $subModuleId) {
		$manifestSub = $this->getSubManifestTT($targetType);

		if (array_key_exists($moduleId, $manifestSub))
			return array_key_exists($subModuleId, $manifestSub[$moduleId][$targetType]);

		return false;
	}

	/***************************************************************************
	* To get the default TTS Engine Id
	*/
	public function getDefaultTtsEngineId() {
		$cBase = $this->getModuleConfig('base');

		return $cBase['defaultTtsEngine'];
	}

	/***************************************************************************
	* To get the the prefix
	*/
	public function getPrefix($ttsEngine, $module, $submodule) {
		$prefixSwitch = $cBase = $this->getModuleConfig('base')['prefixSwitch'];

		$key111 = "$ttsEngine.$module.$submodule";
		$key110 = "$ttsEngine.$module.*";
		$key100 = "$ttsEngine.*.*";
		if (array_key_exists($key111, $prefixSwitch))
			return $prefixSwitch[$key111];
		elseif (array_key_exists($key110, $prefixSwitch))
			return $prefixSwitch[$key110];
		elseif (array_key_exists($key100, $prefixSwitch))
			return $prefixSwitch[$key100];
		else
			return $this->getModuleConfig('base')['defaultPrefix'];
	}

	/***************************************************************************
	* Return a indexed array of TTSENGINE module $id=>name
	*/
	public function getTtsEngineModuleIArray() {
		$manifestSub = $this->getSubManifestMT(CoreUtils::MODULE_T_TTSENGINE);
		$iArray = array();

		foreach ($manifestSub as $id => $m) {
			$iArray[$id] = $m['name'];
		}

		return $iArray;
	}

	/***************************************************************************
	* Return a specific module manifest
	*/
	public function getModuleManifest($moduleId) {
		return $this->manifestMain[$moduleId];
	}

	/***************************************************************************
	* Return true if the module existe
	*
	* @param $targetType
	* @param $moduleId
	* @param $subModuleId
	*
	*/
	public function isTtsEngine($moduleId) {
		$manifestSub = $this->getSubManifestMT(CoreUtils::MODULE_T_TTSENGINE);

		return array_key_exists($moduleId, $manifestSub);
	}

	/***************************************************************************
	* Return already readed module config file or load it
	*/
	public function getModuleConfig($module, $submodule='main') {
		$key = $module .'_'. $submodule;
		$path = CoreUtils::PATH_CONFIG.$key.'.json';

		if (!array_key_exists($key, $this->moduleConfig)) {
			$this->moduleConfig[$key] = JsonUtils::jFile2Array($path);
		}

		return $this->moduleConfig[$key];
	}

	/***************************************************************************
	* Return already readed module config file or load it
	*/
	public function saveModuleConfig($module, $submodule='main', $aData) {
		$key = $module .'_'. $submodule;
		$path = CoreUtils::PATH_CONFIG.$key.'.json';

		JsonUtils::array2JFile($aData, $path);

		if (!array_key_exists($key, $this->moduleConfig[$key])) {
			$this->moduleConfig[$key] = $aData;
		}
	}

	/***************************************************************************
	* Return a module config file in a raw format
	*/
	public function getModuleRawConfig($module, $fileId) {
		$fileName = $module .'_'. $fileId;
		$path = CoreUtils::PATH_CONFIG.$fileName;

		return file_get_contents($path);
	}

	/***************************************************************************
	* Return a module config file in a raw format
	*/
	public function saveModuleRawConfig($module, $fileId, $data) {
		$fileName = $module .'_'. $fileId;
		$path = CoreUtils::PATH_CONFIG.$fileName;

		$r = file_put_contents($path, $data);
		if ($r===false)
			throw new Exception("File to write file $path");
		chmod($path, 0666);
	}
}
