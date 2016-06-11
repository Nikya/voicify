<?php
// All playable API functions

////////////////////////////////////////////////////////////////////////////
/** Play a voicekey */
function playVoicekey() {
	$debug = isset($_GET['verbose']) and strcasecmp($_GET['verbose'], 'yes')==0;

	if (!isset($_GET['voicekey']) or empty($_GET['voicekey']))
		throw new Exception("No voicekey to process");

	$voicekey = $_GET['voicekey'];

	$voicify = new Voicify($voicekey);
	if (isset($_GET['vars']) and !empty($_GET['vars']) and is_array($_GET['vars']))
		$voicify->setVars($_GET['vars']);
	$voicify->process();

	if (!$debug)
		return array('text' => $voicify->getText());
	else
		return array(
			'voicekey' => $voicekey,
			'text' => $voicify->getText(),
			'textRaw' => $voicify->getRawText(),
			'vars' => $voicify->getVars(),
			'varsCommute' => $voicify->getCommuteVars()
		);
}

////////////////////////////////////////////////////////////////////////////
/** Play a clock */
function playClock() {
	/*$debug = isset($_GET['verbose']) and strcasecmp($_GET['verbose'], 'yes')==0;

	if (!isset($_GET['voicekey']) or empty($_GET['voicekey']))
		throw new Exception("No voicekey to process");

	$voicekey = $_GET['voicekey'];

	$voicify = new Voicify($voicekey);
	if (isset($_GET['vars']) and !empty($_GET['vars']) and is_array($_GET['vars']))
		$voicify->setVars($_GET['vars']);
	$voicify->process();

	if (!$debug)
		return array('text' => $voicify->getText());
	else
		return array(
			'voicekey' => $voicekey,
			'text' => $voicify->getText(),
			'textRaw' => $voicify->getRawText(),
			'vars' => $voicify->getVars(),
			'varsCommute' => $voicify->getCommuteVars()
		);
	*/
		return array(
			'voicekey' => 'Clock',
			'text' => 'Unimplemeted',
			'textRaw' => 'Unimplemeted',
			'vars' => 'Unimplemeted',
			'varsCommute' => 'Unimplemeted'
		);
}
