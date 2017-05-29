<?php

// The voicekey is mandatory
if (!isset($_GET['voicekey']) or empty($_GET['voicekey']))
	Console::e('voicekey.pMainApi', 'Missing mandatory Voicekey', $_GET);
else {
	$voicekey = trim($_GET['voicekey']);

	$config = Config::getInstance();
	$colVoicekey = $config->getModuleConfig('voicekey', 'voicekey');

	// Extract Vars into data
	$data = array();
	if (isset($_GET['vars']))
		$data = array_filter($_GET['vars']);

	// It's unknow voicekey
	if (!array_key_exists($voicekey, $colVoicekey)) {
		Console::w('voicekey.pMainApi', 'Unknow voicekey', $voicekey);
		$voicekeyold = $voicekey;
		$voicekey = 'zDefault';
		$data = array($voicekeyold);
		if (!array_key_exists($voicekey, $colVoicekey)) {
			Console::e('voicekey.pMainApi', 'Default voicekey not found', $data);
			throw new Exception("Unknow voicekey and $voicekey voicekey not found !", 1);
		}
	}

	// Call Textify process
	$tfy = new Textify($colVoicekey[$voicekey]['textList'], $data);
	$tfy->process();
	$say = $tfy->getFinalText();

	// Debug
	if (Console::isDebug()) {
		Console::d('voicekey', 'Original frequenced text', $tfy->getOFreqTextList());
		Console::d('voicekey', 'Original data', $tfy->getOData());
		Console::d('voicekey', 'Selected text', $tfy->getSelectedText());
		Console::d('voicekey', 'Substituted data', $tfy->getData());
	}
}
