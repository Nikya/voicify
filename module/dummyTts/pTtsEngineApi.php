<?php
/*******************************************************************************
* The TTS engine API
*******************************************************************************/

/***************************************************************************
* To redirect the fake TTS to an output file
*/
function say($ttSay) {
	$config = Config::getInstance();
	$cDummyTts = $config->getModuleConfig('dummyTts');
	$maxTextLenght = $cDummyTts['maxTextLenght'];
	Console::d('dummySay', 'maxTextLenght', $maxTextLenght); 

	// Build
	global $prefix;
	$out = '';
	$ts = date('Y-m-d H:i:s');
	$fileName = CoreUtils::PATH_TEMP.'log_dummyTts.csv';

	if (strlen($ttSay) < $maxTextLenght)
		writteSay($fileName, $ttSay);
	else {
		$manyTtSay = OtherUtils::splitLongText($ttSay, $maxTextLenght);

		Console::d('dummySay', 'manyTtSay', $manyTtSay); 

		foreach ($manyTtSay as $miniTtSay) {
			writteSay($fileName, $miniTtSay);
		}
	}
}

/***************************************************************************
* To redirect the fake TTS to an output file
*/
function writteSay($fileName, $ttSay) {
	// Build
	global $prefix;
	$out = '';
	$ts = date('Y-m-d H:i:s');
	$fileName = CoreUtils::PATH_TEMP.'log_dummyTts.csv';

	$out .= "$ts;\t #0;\t I;\t dummySay.$prefix;\t $ttSay\n";

	// Process
	$r = file_put_contents($fileName, $out, FILE_APPEND);

	// Debug
	if (Console::isDebug()) {
		Console::d('dummyWritteSay', 'Output file', $fileName);
		Console::d('dummyWritteSay', "Output.$prefix", $ttSay);
	}

	// Manage error
	if ($r===false) {
		Console::e('dummyWritteSay', 'Fail to write into the file', $fileName);
		throw new Exception("dummyWritteSay fail write");
	}
	
	chmod($fileName, 0666);
}
