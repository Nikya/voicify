<?php

	require_once('./core/Setup.php');
	require_once('./core/JsonUtils.php');
	//require_once('./core/Config.php');

/*******************************************************************************
* Common Core utiliy functions
*/
class CoreUtils {

	/** To store console messages */
	private $aConsole = array();

	/***************************************************************************
	* To add a message into the console output
	*/
	function __construct() {
		if (!Setup::check()) {
			$this->consoleW('CoreUtils.constructor', 'Need to execute Setup');
			$this->consoleI('CoreUtils.constructor', 'Setup result', Setup::exec());
		}

		// Config::Load();
	}

	/***************************************************************************
	* To add a message into the console output
	*/
	public function console ($lvl, $tag, $msg, $mixed=null) {
		array_push($this->aConsole, array(
			'lvl' => $lvl,
			'tag' => $tag,
			'msg' => $msg,
			'mixed' => $mixed
		));
	}
	public function consoleD ($tag, $msg, $mixed=null) {	$this->console('D', $tag, $msg, $mixed); }
	public function consoleI ($tag, $msg, $mixed=null) {	$this->console('I', $tag, $msg, $mixed); }
	public function consoleW ($tag, $msg, $mixed=null) {	$this->console('W', $tag, $msg, $mixed); }
	public function consoleE ($tag, $msg, $mixed=null) {	$this->console('E', $tag, $msg, $mixed); }

	/***************************************************************************
	* Common Core utiliy functions
	*/
	public function consolePrint() {
		$out = '';

		foreach ($this->aConsole as $entry) {
			$mixed = $entry['mixed'];
			$fMixed = $mixed != null ? "\n\t" . print_r($mixed, true) : '';

			if (is_object($mixed))
				if ($mixed instanceof Exception)
					$fMixed = "\n\t" . $mixed->getTraceAsString();

			$out .= "[${entry['lvl']} | ${entry['tag']}]\t${entry['msg']}$fMixed\n\n";
		}

		echo $out;
		$this->aConsole = array();
	}
}
