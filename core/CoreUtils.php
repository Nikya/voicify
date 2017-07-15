<?php

/*******************************************************************************
* Common Core utiliy functions
*/
class CoreUtils {

	/** Core version */
	const VERSION = '2.1';

	/** Path to the config Dir */
	const PATH_CONFIG = './config/';

	/** Path to the temp Dir */
	const PATH_TEMP = './temp/';

	/** Path to modules */
	const PATH_MODULE = './module/';

	/** Path to the Main manifest */
	const PATH_MANIFEST_MAIN = self::PATH_CONFIG . 'manifest_main.json';

	/** Module type */
	const MODULE_T_FEATURE = 'FEATURE';

	/** Module type */
	const MODULE_T_TTSENGINE = 'TTSENGINE';

	/** Module type */
	const MODULE_T_CORE = 'CORE';

	/** Menu type */
	const TARGET_T_PLAY = 'play';

	/** Menu type */
	const TARGET_T_CONFIG= 'config';

	/** Menu type */
	const TARGET_T_SETUP = 'setup';

	/** Menu type */
	const TARGET_T_HOME = 'home';

	/** Menu type */
	const TARGET_T_LOG = 'log';

	/***************************************************************************
	* Parse a Mardown file into a formated HTML
	*/
	public static function mdParse($path) {
		$c = file_get_contents($path);

		if ($c===false)
			throw new Exception("Fail to read the Markdown file '$path' ");

		return Parsedown::instance()->text($c);
	}

	/***************************************************************************
	* Parse a README file into a formated HTML
	*/
	public static function getModuleReadme($moduleId) {
		$path = self::PATH_MODULE.$moduleId.'/README.md';
		return self::mdParse($path);
	}
}
