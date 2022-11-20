<?php
/*******************************************************************************
* The TTS engine API
*******************************************************************************/

/***************************************************************************
* The TTS function
*/
function say($ttSay) {
	$config = Config::getInstance();
	$cJpi = $config->getModuleConfig('jpi');
	$host = $cJpi['host'];
	$port = $cJpi['port'];
	$volume = $cJpi['volume'];
	$stream = $cJpi['stream'];
	$queue = $cJpi['queue'];
	$wait = $cJpi['wait'];
	$voice = $cJpi['voice'];
	$maxTextLenght = $cJpi['maxTextLenght'];

	// Build
	$url = "http://${host}:${port}/?action=tts&volume=${volume}&stream=${stream}&queue=${stream}&wait=${wait}&voice=${voice}";

	// Debug
	if (Console::isDebug()) {
		Console::d('jpiSay', 'host', $host);
		Console::d('jpiSay', 'port', $port);
		Console::d('jpiSay', 'volume', $volume);
		Console::d('jpiSay', 'stream', $stream);
		Console::d('jpiSay', 'queue', $queue);
		Console::d('jpiSay', 'wait', $wait);
		Console::d('jpiSay', 'url', $url);
	}

	if (strlen($ttSay) < $maxTextLenght)
		callSay($url, $ttSay);
	else {
		$manyTtSay = OtherUtils::splitLongText($ttSay, $maxTextLenght);

		if (Console::isDebug()) Console::d('jpiSay', 'manyTtSay', $manyTtSay); 

		foreach ($manyTtSay as $miniTtSay) {
			callSay($url, $miniTtSay);
		}
	}
}

function callSay($url, $ttSay) {
	$encSay = urlencode($ttSay);
	$url = "$url&message=${encSay}";

	// Process
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$res = curl_exec($ch);

	// Manage error
	if ($res===false) {
		Console::w('jpiCallSay', 'curl.res', $res);
		Console::w('jpiCallSay', 'curl.error', curl_error($ch));
		Console::d('jpiCallSay', 'url', $url);
		throw new Exception("jpiCallSay fail to play $url");
	}
	
	// If is not ok 
	if (curl_getinfo($ch, CURLINFO_RESPONSE_CODE) != 200) {
		Console::w('jpiCallSay', 'curl.respondeCode', curl_getinfo($ch, CURLINFO_RESPONSE_CODE));
		Console::w('jpiCallSay', 'curl.res', $res);
		Console::w('jpiCallSay', 'curl.error', curl_error($ch));
		Console::d('jpiCallSay', 'url', $url);
	}
}
