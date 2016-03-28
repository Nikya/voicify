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
	return WordingCollection::getInstance()->getAllVoicekey();
}

////////////////////////////////////////////////////////////////////////////
/** To all available voicekey into array */
function getSubvoicekeyList() {
	return WordingCollection::getInstance()->getAllSubvoicekey();
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
