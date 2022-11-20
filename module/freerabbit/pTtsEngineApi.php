<?php
/*******************************************************************************
* The TTS engine API
*******************************************************************************/

/***************************************************************************
* The TTS function
*/
function say($ttSay) {
	$encSay = urlencode($ttSay);
	$config = Config::getInstance();
	$c = $config->getModuleConfig('freerabbit');
	$host = $c['host'];
	$voice = $c['voice'];
	$nocache = $c['nocache'];

	// Build
	$url = "http://{$host}/cgi-bin/tts?voice={$voice}&text={$encSay}&nocache={$nocache}";

	// Process
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$res = curl_exec($ch);

	// Debug
	if (Console::isDebug()) {
		Console::d('freerabbitSay', 'host', $host);
		Console::d('freerabbitSay', 'voice', $voice);
		Console::d('freerabbitSay', 'nocache', $nocache);
		Console::d('freerabbitSay', 'url', $url);
	}

	// Manage error
	if ($res===false) {
		Console::w('freerabbitSay', 'curl.res', $res);
		Console::w('freerabbitSay', 'curl.error', curl_error($ch));
		throw new Exception("freerabbitSay fail to exec $url");
	}
}
