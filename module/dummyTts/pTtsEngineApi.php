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
	$out = '';
	$ts = date('Y-m-d H:i:s');
	$fileName = CoreUtils::PATH_TEMP.'dummyTts.txt';

	$out = "$ts : $say\n";

	// Process
	$r = file_put_contents($fileName, $out, FILE_APPEND);

	// Debug
	if (Console::isDebug()) {
		Console::d('dummy', 'Output file', $fileName);
		Console::d('dummy', 'Output', $out);
	}

	// Manage error
	if ($r===false) {
		Console::e('dummy', 'Fail to write into the file', $fileName);
		throw new Exception("dummyTrace fail");
	}
	chmod($fileName, 0666);

}
