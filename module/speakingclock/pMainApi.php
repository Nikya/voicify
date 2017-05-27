<?php

// The current hour
if (isset($_GET['trgHour']) and !empty($_GET['trgHour']))
	$u = new Utils($_GET['trgHour']);
else
	$u = new Utils();

$config = Config::getInstance();
$colHour = $config->getModuleConfig('speakingclock', 'hour');
$colNeutral = $config->getModuleConfig('speakingclock', 'neutral');


// Choice a text collection
// If is a test
if (isset($_GET['trgTxt']) and !empty($_GET['trgTxt'])) {
	$trgTxtExp = explode('.', $_GET['trgTxt']);
	$colSel = $trgTxtExp[0];
	$txtSel = $trgTxtExp[1];
	if ($colSel=='n')
		$colToUse = array(0 => $colNeutral[$txtSel]);
	else
		$colToUse = array(0 => $colHour[$colSel][$txtSel]);
}
else if (array_key_exists($u->getHour(), $colHour)) {
	$colAll = array (0=> $colHour[$u->getHour()], 1=>$colNeutral);
	$colToUse = $colAll[rand(0,1)];
}
else
	$colToUse = $colNeutral;

// Call Textify process
$tfy = new Textify($colToUse, $u->getVars());
$tfy->process();
$say = $tfy->getFinalText();

// Debug
if (Console::isDebug()) {
	Console::d('speakingclock', 'Collection neutral size', count($colNeutral));
	Console::d('speakingclock', 'Collection hour size', count($colHour));
	Console::d('speakingclock', 'Collection selected', $colToUse);
	Console::d('speakingclock', 'Selected text', $tfy->getSelectedText());
	Console::d('speakingclock', 'Data', $tfy->getData());
}
