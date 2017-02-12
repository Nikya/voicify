<?php

/*******************************************************************************
* To log error to the output console
*/
class Console {

	/** To store console messages */
	private static $aConsole = array();

	/** To enable debug level */
	private static $debug = false;

	/***************************************************************************
	* To add a message into the console output
	*/
	public static function trace ($lvl, $tag, $msg, $mixed=null) {
		array_push(self::$aConsole, array(
			'lvl' => $lvl,
			'tag' => $tag,
			'msg' => $msg,
			'mixed' => $mixed
		));
	}

	public static function d ($tag, $msg, $mixed=null) { if(self::$debug)
															self::trace('D', $tag, $msg, $mixed); }
	public static function i ($tag, $msg, $mixed=null) {	self::trace('I', $tag, $msg, $mixed); }
	public static function w ($tag, $msg, $mixed=null) {	self::trace('W', $tag, $msg, $mixed); }
	public static function e ($tag, $msg, $mixed=null) {	self::trace('E', $tag, $msg, $mixed); }

	/***************************************************************************
	* To print the console into HTML LI
	*/
	public static function toHtml() {
		$out = '';
		$lvl2Indicator = array(
			'D' => 'd',
			'I' => 'ok',
			'W' => 'warn',
			'E' => 'ko'
		);

		$aCsl = self::getArrayConsole();

		foreach ($aCsl as $entry) {
			$fMixed = self::mixedToString($entry['mixed']);

			$out .= <<<EOE
				<li>
					<span class="lvl{$lvl2Indicator[$entry['lvl']]} lvl">{$entry['lvl']} - {$entry['tag']}</span>
					<p>{$entry['msg']}</p>
					<pre>$fMixed</pre>
				</li>
EOE;
		}

		return $out;
	}

	/***************************************************************************
	* To print the console into a log file
	*/
	public static function toLogFile() {
		if (self::indicator() != 'ok') {
			$out = '';
			$ts = date('Y-m-d H:i:s');
			$fileName = date('Ym').'.log';

			$aCsl = self::getArrayConsole();

			foreach ($aCsl as $entry) {
				$fMixed = self::mixedToString($entry['mixed']);

				$o = "[{$entry['lvl']} - {$entry['tag']}]\t\t{$entry['msg']} \t|\t $fMixed";
				$out .= trim(preg_replace( "/\r|\n/", "", $o ))."\n";
			}

			$r = file_put_contents(
					CoreUtils::PATH_TEMP.$fileName,
					"--- $ts --------------------------------------------------------\n$out\n",
					FILE_APPEND
			);

			if ($r===false) echo 'Fail to write into the log file';
		}
	}

	/***************************************************************************
	* Read a mixed value into a string
	*/
	private static function mixedToString($mixed) {
		$fMixed = $mixed != null ? print_r($mixed, true) : '';

		if (is_object($mixed))
			if ($mixed instanceof Exception)
				$fMixed = $mixed->getTraceAsString();

		return $fMixed;
	}

	/***************************************************************************
	* Get the global console indicator
	*/
	public static function indicator() {
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
	* Get the array stored console
	*/
	public static function getArrayConsole() {
		if (empty(self::$aConsole))
			self::i('OK', 'Everything is fine.', '');

		return self::$aConsole;
	}

	/***************************************************************************
	* Get/set debug
	*/
	public static function isDebug() { return self::$debug; }
	public static function setDebug($debugMode) { self::$debug = $debugMode===false ? false : true; }
}


/*******************************************************************************
* ******************************************************************************
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
			Console::w("UNEXPECTED_WARNING_LEVEL$level", $fMessage);
			break;

		// Errors and others
		default:
			Console::e("UNEXPECTED_ERROR_LEVEL$level", $fMessage);
			break;
	}
}
