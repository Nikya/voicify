<?php

	require_once('./core/Setup.php');
	require_once('./core/JsonUtils.php');
	require_once('./core/vendors/erusev/parsedown/Parsedown.php');

/*******************************************************************************
* Common Core utiliy functions
*/
class CoreUtils {

	/** Module type */
	const MODULE_T_FEATURE = 'FEATURE';

	/** Module type */
	const MODULE_T_TTSENGINE = 'TTSENGINE';

	/** Menu type */
	const MENU_T_PLAYER = 'player';

	/** Menu type */
	const MENU_T_CONFIGURATOR= 'configurator';

	/** To store console messages */
	private static $aConsole = array();

	/** To store already read module config */
	private static $moduleConfig = array();

	/** To store the main manifest */
	private static $manifestMain = null;

	/***************************************************************************
	* To add a message into the console output
	*/
	public static function console ($lvl, $tag, $msg, $mixed=null) {
		array_push(self::$aConsole, array(
			'lvl' => $lvl,
			'tag' => $tag,
			'msg' => $msg,
			'mixed' => $mixed
		));
	}
	public static function consoleD ($tag, $msg, $mixed=null) {	self::console('D', $tag, $msg, $mixed); }
	public static function consoleI ($tag, $msg, $mixed=null) {	self::console('I', $tag, $msg, $mixed); }
	public static function consoleW ($tag, $msg, $mixed=null) {	self::console('W', $tag, $msg, $mixed); }
	public static function consoleE ($tag, $msg, $mixed=null) {	self::console('E', $tag, $msg, $mixed); }

	/***************************************************************************
	* To print the console into HTML LI
	*/
	public static function consolePrint() {
		$out = '';
		$lvl2Indicator = array(
			'D' => 'ok',
			'I' => 'ok',
			'W' => 'warn',
			'E' => 'ko'
		);

		if (empty(self::$aConsole))
			self::consoleI('OK', 'Ok, everything is fine.');

		foreach (self::$aConsole as $entry) {
			$mixed = $entry['mixed'];
			$fMixed = $mixed != null ? print_r($mixed, true) : '';

			if (is_object($mixed))
				if ($mixed instanceof Exception)
					$fMixed = $mixed->getTraceAsString();


			//$out .= "[${entry['lvl']} | ${entry['tag']}]\t${entry['msg']}$fMixed\n\n";
			$out .= <<<EOE
				<li>
					<span class="lvl{$lvl2Indicator[$entry['lvl']]} lvl">{$entry['lvl']} - {$entry['tag']}</span>
					<p>{$entry['msg']}</p>
					<pre>$fMixed</pre>
				</li>
EOE;
		}

		echo $out;
		self::$aConsole = array();
	}

	/***************************************************************************
	* Get the global console indicator
	*/
	public static function consoleIndicator() {
		$cptW = 0;

		foreach (self::$aConsole as $entry) {
			if ($entry['lvl'] == 'E')
				return 'ko';
			else if ($entry['lvl'] == 'W')
				$cptW++;
		}

		return $cptW>0 ? 'warn' : 'ok';
	}

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
	* Return already readed module config file or load it
	*/
	public static function getManifestMain() {
		if (self::$manifestMain == null)
			self::$manifestMain = JsonUtils::jFile2Array(Setup::PATH_MANIFEST_MAIN);

		return self::$manifestMain;
	}

	/***************************************************************************
	* Return already readed module config file or load it
	*/
	public static function getConfig($module, $confName='main') {
		$key = $module . ($confName!=null ? '_'.$confName : '');

		if (!array_key_exists($key, $moduleConfig)) {
			$moduleConfig[$key] = JsonUtils::jFile2Array("./config/$key.json");
		}

		return $moduleConfig[$key];
	}

	/***************************************************************************
	* Check if a module existe and is installed
	*/
	public static function existFeatureModule($id) {
		$modules = self::getConfig('modules', null);

		return array_key_exists($id, $modules['feature']);
	}

	/***************************************************************************
	* Check if a module existe and is installed
	*/
	public static function existTtsengineModule($id) {
		$modules = self::getConfig('modules', null);

		return array_key_exists($id, $modules['ttsengine']);
	}

	/***************************************************************************
	* Check if a module existe and is installed
	*/
	public static function getFeatureModules() {
		return array_key_exists($id, $modules['ttsengine']);
	}
}


/*******************************************************************************
* Redirect Error handler
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_error_handler('errorToException', E_ALL);

function errorToException($level, $message, $file, $line, $context) {
	if (error_reporting() === 0)
		return;

	$fMessage = "$message. In line $line of file $file";

	switch ($level) {
		// Warning
		case 'E_USER_NOTICE':
		case 'E_USER_WARNING':
		case 'E_COMPILE_WARNING':
		case 'E_CORE_WARNING':
		case 'E_WARNING':
			CoreUtils::consoleW("UNEXPECTED_WARNING_LEVEL_$level", $fMessage);
			break;

		// Errors and others
		default:
			CoreUtils::consoleE("UNEXPECTED_ERROR_LEVEL_$level", $fMessage);
			break;
	}
}
