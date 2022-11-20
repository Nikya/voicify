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
	$cImperihome = $config->getModuleConfig('imperihome');
	$host = $cImperihome['host'];
	$port = $cImperihome['port'];
	$vol = $cImperihome['vol'];

	// Build
	$url = "http://{$host}:{$port}/api/rest/speech/tts?text={$encSay}&vol={$vol}";

	// Process
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$res = curl_exec($ch);

	// Debug
	if (Console::isDebug()) {
		Console::d('imperihomeSay', 'host', $host);
		Console::d('imperihomeSay', 'port', $port);
		Console::d('imperihomeSay', 'vol', $vol);
		Console::d('imperihomeSay', 'url', $url);
	}

	// Manage error
	if ($res===false) {
		Console::w('imperihomeSay', 'curl.res', $res);
		Console::w('imperihomeSay', 'curl.error', curl_error($ch));
		throw new Exception("imperihomeSay fail to play $url");
	}
}
