<?php
/*******************************************************************************
* The TTS engine API
*******************************************************************************/

eedomusTts();

/***************************************************************************
* The TTS function
*/
function eedomusTts() {
	global $encSay;
	$config = Config::getInstance();
	$cEedomus = $config->getModuleConfig('eedomusTts');
	$host = $cEedomus['host'];
	$lng = $cEedomus['lng'];

	// Build
	$url = "http://{$host}/tools/?action=tts&text={$encSay}&lng={$lng}";

	// Process
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$res = curl_exec($ch);

	// Debug
	if (Console::isDebug()) {
		Console::d('eedomusTts', 'host', $host);
		Console::d('eedomusTts', 'lng', $lng);
		Console::d('eedomusTts', 'url', $url);
	}

	// Manage error
	if ($res===false) {
		Console::w('eedomusTts', 'curl.res', $res);
		Console::w('eedomusTts', 'curl.error', curl_error($ch));
		throw new Exception("eedomusTts fail to play $url");
	}

	if(strpos($res, '<error>0') === false) {
		Console::e('eedomusTts', 'Error found in eedomus tools', $res);
		throw new Exception("eedomus tools error found");
	}
}
