<?php

	// Check TTS engine targeted
	$tts = null;
	if (!isset($_GET['ttsengine']) or empty($_GET['ttsengine'])) {
		$tts = $Config::getInstance()->getDefaultTtsEngineId();
		Console::d('playAPI', "Using the default TTS engine : $tts");
	} else
		$tts = $_GET['ttsengine'];

	if (!Config::getInstance()->isTtsEngine($tts))
		throw new Exception("Unknow TTS engine '{$_GET['ttsengine']}' ");

	// Play the feature
	$featureApiPath = CoreUtils::PATH_MODULE.$module.'/'.$subModule.'Api.php';
	include($featureApiPath);

	// Call the TTS engine
	$ttEngineApiPath = CoreUtils::PATH_MODULE.$tts.'/pTtsEngineApi.php';
	include($ttEngineApiPath);
