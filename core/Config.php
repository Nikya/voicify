<?php

/*******************************************************************************
* Utils to read Config files and Main Manifest
*/
class Config {

	/** The unique one Singleton instance */
	private static $instance = null;

	/** To store already read module config */
	private static $moduleConfig = array();

	/** To store the main manifest */
	private $manifestMain = null;

	public static function getInstance() {
		if (self::$instance==null) {
			self::$instance = new Config();
		}
		return self::$instance;
	}

	/**
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
	*
	*/
	public function isValidModule($targetType, $moduleId, $subModuleId) {
		$manifestSub = $this->getSubManifestTT($targetType);

		if (array_key_exists($moduleId, $manifestSub))
			return array_key_exists($subModuleId, $manifestSub[$moduleId][$targetType]);

		return false;
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

		if (!array_key_exists($key, $this->getManifestMain())) {
			$moduleConfig[$key] = JsonUtils::jFile2Array("./config/$key.json");
		}

		return $moduleConfig[$key];
	}

}
