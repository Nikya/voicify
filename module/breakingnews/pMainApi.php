<?php

// Extract Vars into data
$data = array();
if (isset($_GET['vars']))
	$data = array_filter($_GET['vars']);

$bnb = new BreakingnewsBuilder($data);
$bnb->process();
$bnbRes = $bnb->getResult();

foreach ($bnbRes as $b) {
	$say .= "$b ";
}

// Debug
if (Console::isDebug()) {
	Console::d('breakingnews', 'All breaking news part', $bnbRes);
}
