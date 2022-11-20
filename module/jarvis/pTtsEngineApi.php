<?php
/*******************************************************************************
* The TTS engine API
*******************************************************************************/

/***************************************************************************
* The TTS function
*/
function say($ttSay) {
	// Build
	$config = Config::getInstance();
	$cImperihome = $config->getModuleConfig('jarvis');

	$host = $cImperihome['host'];
	$port = $cImperihome['port'];
	$encSay = urlencode($ttSay);

	$url = "http://{$host}:{$port}?say={$encSay}";

	// Process
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$res = curl_exec($ch);

	// Debug
	if (Console::isDebug()) {
		Console::d('jarvisSay', 'host', $host);
		Console::d('jarvisSay', 'port', $port);
		Console::d('jarvisSay', 'url', $url);
	}

	// Manage error
	if ($res===false) {
		Console::w('jarvisSay', 'curl.res', $res);
		Console::w('jarvisSay', 'curl.error', curl_error($ch));
		throw new Exception("jarvisSay fail to play $url");
	}
}
