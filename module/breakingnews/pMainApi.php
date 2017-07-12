<?php

// The temperature is mandatory
if (!isset($_GET['extTemperature']) or empty($_GET['extTemperature']))
	Console::e('breakingnews.pMainApi', 'Missing mandatory external temperature', $_GET);
else {
	$oT = $_GET['extTemperature'];
	$bnb = new BreakingnewsBuilder($oT);
	$bnb->process();
	$bnbRes = $bnb->getResult();

	foreach ($bnbRes as $b) {
		$say .= "$b ";
	}

	// Debug
	if (Console::isDebug()) {
		Console::d('breakingnews', 'All breaking news part', $bnbRes);
	}
}
