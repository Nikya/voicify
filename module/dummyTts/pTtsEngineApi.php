<?php
/*******************************************************************************
* The TTS engine API
*******************************************************************************/

if (!isset($_GET['tts']) or empty($_GET['tts'])) {
	Console::e('dummy.playMain', 'No TTS to process');
} else {
	$say = $_GET['tts'];
}

dummyTrace($say);

/***************************************************************************
* To redirect the fake TTS to an output file
*/
function dummyTrace($tts) {
	$out = '';
	$ts = date('Y-m-d H:i:s');
	$fileName = CoreUtils::PATH_TEMP.'dummyTts.txt';

	$out = "$ts : $tts\n";

	$r = file_put_contents($fileName, $out, FILE_APPEND);

	if ($r===false)
		Console::e('dummy', 'Fail to write into the file', $fileName);

	Console::d('dummy', 'Output file', $fileName);
	Console::d('dummy', 'Output', $out);
}
