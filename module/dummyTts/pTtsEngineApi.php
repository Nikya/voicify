<?php
/*******************************************************************************
* The TTS engine API
*******************************************************************************/

dummySay();

/***************************************************************************
* To redirect the fake TTS to an output file
*/
function dummySay() {
	// Build
	global $say;
	global $prefix;
	$out = '';
	$ts = date('Y-m-d H:i:s');
	$fileName = CoreUtils::PATH_TEMP.'log_dummyTts.csv';

	$out .= "$ts;\t #0;\t I;\t dummySay.$prefix;\t $say\n";

	// Process
	$r = file_put_contents($fileName, $out, FILE_APPEND);

	// Debug
	if (Console::isDebug()) {
		Console::d('dummy', 'Output file', $fileName);
		Console::d('dummy', "Output.$prefix", $say);
	}

	// Manage error
	if ($r===false) {
		Console::e('dummy', 'Fail to write into the file', $fileName);
		throw new Exception("dummyTrace fail");
	}
	chmod($fileName, 0666);

}
