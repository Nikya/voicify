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
		$mMixed = $mixed;
		// Managed the mixed if not null
		if ($mixed != null) {
			// Is an object
			if (is_object($mixed)) {
				// Is an Exception
				if ($mixed instanceof Exception) {
					$mMixed = $mixed->getTrace();
					$msg .= ' - '.$mixed->getMessage();
				} else {
					// Object but not an exception
					$mMixed = get_object_vars($mixed);
				}
			}
		}

		array_push($this->aConsole, array(
			'lvl' => $lvl,
			'tag' => $tag,
			'msg' => $msg,
			'mixed' => $mMixed
		));
	}

	public static function i ($tag, $msg, $mixed=null) {	self::getInstance()->trace('I', $tag, $msg, $mixed); }
	public static function w ($tag, $msg, $mixed=null) {	self::getInstance()->trace('W', $tag, $msg, $mixed); }
	public static function e ($tag, $msg, $mixed=null) {	self::getInstance()->trace('E', $tag, $msg, $mixed); }
	public static function d ($tag, $msg, $mixed=null) {if(self::isDebug()) self::getInstance()->trace('D', $tag, $msg, $mixed); }

	/***************************************************************************
	* To print the console into a log file if is debug or not Ok
	*/
	public function toLogFile() {
		if ($this->isDebug() or $this->indicator() != 'ok') {
			$out = '';
			$ts = time();
			$fTS = date('Y-m-d H:i:s', $ts);
			$fileName = CoreUtils::PATH_TEMP.'log_'.date('Ym', $ts).'.csv';

			if(!file_exists($fileName)) {
				$r = file_put_contents($fileName, '', FILE_APPEND);
				chmod($fileName, 0666);
			}

			$o = 0; // Order
			foreach ($this->getArrayConsole() as $entry) {
				$o++;
				$fLvl = self::sanitize($entry['lvl']);
				$fTag = self::sanitize($entry['tag']);
				$fMsg = self::sanitize($entry['msg']);
				$fMixed = self::sanitize(print_r($entry['mixed'], true));

				$out .= "$fTS;\t $o;\t $fLvl;\t $fTag;\t $fMsg;\t $fMixed \n";
			}

			$r = file_put_contents($fileName, "$out\n", FILE_APPEND);

			if ($r===false) echo 'Fail to write into the log file';
		}
	}

	/***************************************************************************
	* To clear text to output into the log file : Remove new line and séparator
	*/
	private static function sanitize($string) {
		$maxLenght = 250;
		$string = trim($string);
		$string = preg_replace( "/\r|\n|\t/", " ", $string);
		$string = preg_replace( "/\s{2,}/", " ", $string);
		$string = preg_replace( "/;/", ":", $string);
		$string = strlen($string)<$maxLenght ? $string : substr($string, 0, $maxLenght).' [...]';

		return $string;
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
