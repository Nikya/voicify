<?php
// All wording API functions

////////////////////////////////////////////////////////////////////////////
/** Play a voicekey */
function playVoicekey() {
	$date = array();

	if (!isset($_GET['voicekey']) or empty($_GET['voicekey']))
		throw new Exception("No voicekey to process");

	$voicekey = $_GET['voicekey'];

	$voicify = new Voicify($voicekey);
	if (isset($_GET['vars']) and !empty($_GET['vars']) and is_array($_GET['vars']))
		$voicify->setVars($_GET['vars']);
	$voicify->process();

	return array('phrase' => $voicify->getLastText());
}
