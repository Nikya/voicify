<?php /** Common actions for config API */

	// Play the feature
	$configApiPath = CoreUtils::PATH_MODULE.$module.'/'.$subModule.'Api.php';
	$incUPath = CoreUtils::PATH_MODULE.$module.'/Utils.php';
	if (file_exists($incUPath)) require_once($incUPath);

	include($configApiPath);
