<?php

/*******************************************************************************
* To log error to the output console
*/
class Console {

	/** The unique one Singleton instance */
	private static $instance = null;

	/** To store all console traces */
	private $aConsole = null;

	/** To enable debug level */
	private $debug = false;

	/***************************************************************************
	* Get the singleton instance
	*/
	public static function getInstance() {
		if (self::$instance==null) {
			self::$instance = new Console();
		}

		return self::$instance;
	}

	/***************************************************************************
	* Private constructor
	*/
	private function __construct() {
		$this->aConsole = array();
	}

	/***************************************************************************
	* To add a message into the console output
	*/
	private function trace ($lvl, $tag, $msg, $mixed=null) {

		array_push($this->aConsole, array(
			'lvl' => $lvl,
			'tag' => $tag,
			'msg' => $msg,
			'mixed' => $mixed
		));
	}

	public static function i ($tag, $msg, $mixed=null) {	self::getInstance()->trace('I', $tag, $msg, $mixed); }
	public static function w ($tag, $msg, $mixed=null) {	self::getInstance()->trace('W', $tag, $msg, $mixed); }
	public static function e ($tag, $msg, $mixed=null) {	self::getInstance()->trace('E', $tag, $msg, $mixed); }
	public static function d ($tag, $msg, $mixed=null) {if(self::isDebug()) self::getInstance()->trace('D', $tag, $msg, $mixed); }

	/***************************************************************************
	* To print the console into HTML LI
	*/
	public function toHtml() {
		$out = '';
		$lvl2Indicator = array(
			'D' => 'd',
			'I' => 'ok',
			'W' => 'warn',
			'E' => 'ko'
		);

		$aCsl = $this->aConsole;

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
	public function toLogFile() {


		if (self::indicator() != 'ok') {
			$out = '';
			$ts = date('Y-m-d H:i:s');
			$fileName = date('Ym').'.log';

			$aCsl = $this->aConsole;

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
	private function mixedToString($mixed) {
		$fMixed = $mixed != null ? print_r($mixed, true) : '';

		if (is_object($mixed))
			if ($mixed instanceof Exception)
				$fMixed = $mixed->getTraceAsString();

		return $fMixed;
	}

	/***************************************************************************
	* Get the global console indicator
	*/
	public function indicator() {

		$cptW = 0;

		foreach ($this->aConsole as $entry) {
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
	public function getArrayConsole() {


		if (empty($this->aConsole))
			self::i('OK', 'Everything is fine.', '');

		return $this->aConsole;
	}

	/***************************************************************************
	* Get/set debug
	*/
	public static function isDebug() { return self::getInstance()->debug; }
	public static function setDebug($debugMode) { self::getInstance()->debug = $debugMode===false ? false : true; }
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
