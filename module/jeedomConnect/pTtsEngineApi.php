<?php
/*******************************************************************************
* The TTS engine API
*******************************************************************************/

/***************************************************************************
* The TTS function
*/
function say($ttSay) {
	$config = Config::getInstance();
	$cJeedomConnect = $config->getModuleConfig('jeedomConnect');
	$host = $cJeedomConnect['host'];
	$port = $cJeedomConnect['port'];
	$jeedomApikey = $cJeedomConnect['jeedomApikey'];
	$commandId = $cJeedomConnect['commandId'];
	$volume = $cJeedomConnect['volume'];

	// Build
	$url = "http://${host}:${port}/core/api/jeeApi.php?apikey=${jeedomApikey}&type=cmd&id=${commandId}&title=${volume}";
	$obfusUrl = str_replace($jeedomApikey, 'XxXxXx', $url);

	// Debug
	if (Console::isDebug()) {
		Console::d('jeedomConnect', 'host', $host);
		Console::d('jeedomConnect', 'port', $port);
		Console::d('jeedomConnect', 'jeedomApikey', substr($jeedomApikey, 0, 5));
		Console::d('jeedomConnect', 'commandId', $commandId);
		Console::d('jeedomConnect', 'volume', $volume);
		Console::d('jeedomConnect', 'url', $obfusUrl);
	}

	callSay($url, $ttSay, $obfusUrl);
	
	/*if (strlen($ttSay) < $maxTextLenght)
		callSay($url, $ttSay);
	else {
		$manyTtSay = OtherUtils::splitLongText($ttSay, $maxTextLenght);

		if (Console::isDebug()) Console::d('jeedomConnect', 'manyTtSay', $manyTtSay); 

		foreach ($manyTtSay as $miniTtSay) {
			callSay($url, $miniTtSay);
		}
	}*/
}

function callSay($url, $ttSay, $obfusUrl) {
	$encSay = urlencode($ttSay);
	$url = "$url&message=${encSay}";

	// Process
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$res = curl_exec($ch);

	// Manage error
	if ($res===false) {
		Console::w('jeedomConnectCallSay', 'curl.res', $res);
		Console::w('jeedomConnectCallSay', 'curl.error', curl_error($ch));
		Console::d('jeedomConnectCallSay', 'url', $obfusUrl);
		throw new Exception("jeedomConnectCallSay fail to play $obfusUrl");
	}
	
	// If is not ok 
	if (curl_getinfo($ch, CURLINFO_RESPONSE_CODE) != 200) {
		Console::w('jeedomConnectCallSay', 'curl.respondeCode', curl_getinfo($ch, CURLINFO_RESPONSE_CODE));
		Console::w('jeedomConnectCallSay', 'curl.res', $res);
		Console::w('jeedomConnectCallSay', 'curl.error', curl_error($ch));
		Console::d('jeedomConnectCallSay', 'url', $obfusUrl);
	}
}
