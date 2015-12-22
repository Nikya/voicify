<?php

// Fichier de configuration
define ('DIR_CONF_GLOBAL', 'voicifyCore/config/global.ini');

// Fichier des textes
define ('DIR_CONF_TXT', 'voicifyCore/config/voicekey.json');

if (!extension_loaded('intl')) {
	$msg = "Internationalization extension not available. Install it. See http://php.net/manual/fr/intl.installation.php.";
	echo $msg;
	throw new Exception($msg);
}

if (!file_exists(DIR_CONF_TXT)) {
	$msg = 'The text configuration file not exist "'.DIR_CONF_TXT.'" ';
	echo $msg;
	throw new Exception($msg);
}

if (!file_exists(DIR_CONF_GLOBAL)) {
	$msg = 'The global configuration file not exist "'.DIR_CONF_GLOBAL.'" ';
	echo $msg;
	throw new Exception($msg);
}

require_once('voicifyCore/wording_system/TextBuilder.php');
require_once('voicifyCore/wording_system/TextCollection.php');
require_once('voicifyCore/common/JsonUtils.php');

?>
