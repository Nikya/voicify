<?php
/*******************************************************************************
* The TTS engine API
*******************************************************************************/

templateSay();
throw new Exception ('TTS engine API not implemented');

/***************************************************************************
* The TTS function
*/
function templateSay() {
	// Build
	global $say;
	global $encSay; // URL encoded

	// Process
	// $r = curl_exec($say);

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
