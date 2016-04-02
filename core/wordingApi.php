<?php
// All wording API functions

////////////////////////////////////////////////////////////////////////////
/** Get Voicekey au format Json */
function getVoicekeyJson() {
	return WordingCollection::getInstance()->getAllVoicekeyToJson();
}

////////////////////////////////////////////////////////////////////////////
/** To all available voicekey into array */
function getVoiceKeyList() {
	$a = WordingCollection::getInstance()->getAllVoicekey();
	asort($a);
	return $a;
}

////////////////////////////////////////////////////////////////////////////
/** To all available voicekey into array */
function getSubvoicekeyList() {
	$a = WordingCollection::getInstance()->getAllSubvoicekey();
	asort($a);
	return $a;
}

////////////////////////////////////////////////////////////////////////////
/* post Voicekey au format Json */
function postVoicekeyJson() {
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$dataStr = file_get_contents("php://input");
		$wordingCollection = WordingCollection::getInstance();
		echo $wordingCollection->setAllVoicekeyFromJsonStr($dataStr);
	} else
		throw new Exception("Is not a POST method");
}
