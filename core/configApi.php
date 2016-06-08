<?php
// All config API functions

////////////////////////////////////////////////////////////////////////////
/** To all available voicekey data into array */
function getPrefixList() {

	// $a = ConfigManager::getInstance()->getPrefixList();

	$a = array (
		'no' => 'Aucun',
		'default' => 'Ici ruby',
		'warning' => 'Attention !'
	);

	ksort($a, SORT_NATURAL | SORT_FLAG_CASE);

	return $a;
}
