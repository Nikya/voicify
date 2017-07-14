<?php /** Common actions for play API */

	// Check TTS engine targeted
	$ttsEngine = null;
	if (!isset($_GET['ttsengine']) or empty($_GET['ttsengine'])) {
		$ttsEngine = Config::getInstance()->getDefaultTtsEngineId();
		Console::d('playAPI', "Using the default TTS engine : $ttsEngine");
	} else
		$ttsEngine = $_GET['ttsengine'];

	if (!Config::getInstance()->isTtsEngine($ttsEngine))
		throw new Exception("Unknow TTS engine '{$_GET['ttsengine']}' ");

	// Apply prefix
	$prefix = Config::getInstance()->getPrefix($ttsEngine, $module, $subModule);
	if (is_int($prefix))
		sleep(intval($prefix));
	if (Console::isDebug()) Console::d('playAPI', "prefix for $ttsEngine.$module.$subModule", $prefix);

	// Play the feature
	$featureApiPath = CoreUtils::PATH_MODULE.$module.'/'.$subModule.'Api.php';
	$incUPath = CoreUtils::PATH_MODULE.$module.'/Utils.php';
	if (file_exists($incUPath)) require_once($incUPath);
	include($featureApiPath);

	$encSay = urlencode($say);
	Console::d('say', $say);

	// Call the TTS engine
	$ttEngineApiPath = CoreUtils::PATH_MODULE.$ttsEngine.'/pTtsEngineApi.php';
	include($ttEngineApiPath);
