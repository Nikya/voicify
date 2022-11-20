<?php
/*******************************************************************************
* The TTS engine API
*******************************************************************************/

/***************************************************************************
* The TTS function
*/
function say($ttSay) {
	throw new Exception ('TTS engine API not implemented');

	$encSay = urlencode($ttSay); // URL encoded

	// Process

	// Debug
	if (Console::isDebug()) {
		Console::d('templateSay', 'eSay', $encSay);
		// Console::d('templateSay', 'foo', $bar);
	}

	// Manage error
	if ($r===false) {
		Console::e('templateSay', 'Fail to process', $fileName);
		throw new Exception("templateSay fail");
	}
}
