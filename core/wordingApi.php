<?php
// All wording API functions

////////////////////////////////////////////////////////////////////////////
/** To all available voicekey data into array */
function getVoiceKeyFull() {
	$a = WordingCollection::getInstance()->getVoiceKeyFull();
	ksort($a, SORT_NATURAL | SORT_FLAG_CASE);
	return $a;
}

////////////////////////////////////////////////////////////////////////////
/** To all available voicekey into array */
function getVoiceKeyList() {
	$a = WordingCollection::getInstance()->getVoiceKeyList();
	asort($a);
	return $a;
}

////////////////////////////////////////////////////////////////////////////
/** To all available voicekey into array */
function getSubvoicekeyList() {
	$a = WordingCollection::getInstance()->getSubvoicekeyList();
	asort($a);
	return $a;
}

////////////////////////////////////////////////////////////////////////////
/* post Voicekey au format Json */
function postVoicekeyJson() {
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$dataStr = file_get_contents("php://input");
		$wordingCollection = WordingCollection::getInstance();
		$wordingCollection->setAllVoicekeyFromJsonStr($dataStr);
	} else
		throw new Exception("Is not a POST method");
}
