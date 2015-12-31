<?php
// DEFINE FILE CONSTANTES
define ('CONF_DIR',		'voicifyCore/config');
define ('CONF_FILE_DEFAULT_GLOBAL',			CONF_DIR.'/default_global.json');
define ('CONF_FILE_DEFAULT_VOICEKEY',		CONF_DIR.'/default_voicekey.json');
define ('CONF_FILE_DEFAULT_SUBVOICEKEY',	CONF_DIR.'/default_subvoicekey.json');
define ('CONF_FILE_GLOBAL',					CONF_DIR.'/global.json');
define ('CONF_FILE_VOICEKEY',				CONF_DIR.'/voicekey.json');
define ('CONF_FILE_SUBVOICEKEY',			CONF_DIR.'/subvoicekey.json');

////////////////////////////////////////////////////////////////////////////////
// Utils functions

/** If a check fail here, juste throw an exeception */
function checkFail($msg) {
	echo $msg;
	throw new Exception($msg);
}

/** Check for conf file and his default equivalent and init it if not existe */
function checkAndInit($defaultFile, $targetFile, $friendlyName) {
	// Voicekey file
	if (!file_exists($targetFile))
		if (!file_exists($defaultFile))
			checkFail ("$friendlyName FILES not founds '$targetFile' & '$defaultFile' ");
		else
			if (!copy($defaultFile, $targetFile))
				checkFail ("Can't initialize the $friendlyName file (copy '$defaultFile' to '$targetFile') ");
}

////////////////////////////////////////////////////////////////////////////////
// CHECK requirement
if (!extension_loaded('intl'))
	checkFail ("Internationalization extension not available. Install it. See http://php.net/manual/fr/intl.installation.php.");

////////////////////////////////////////////////////////////////////////////////
// CHECK CONF FILES

// Folder
if (!is_writable(CONF_DIR))
	checkFail ('The configuration FOLDER is not not writable : "'.CONF_DIR.'" ');

// Files
checkAndInit(CONF_FILE_DEFAULT_GLOBAL, CONF_FILE_GLOBAL, 'global');
checkAndInit(CONF_FILE_DEFAULT_VOICEKEY, CONF_FILE_VOICEKEY, 'voicekey');
checkAndInit(CONF_FILE_DEFAULT_SUBVOICEKEY, CONF_FILE_SUBVOICEKEY, 'SubVoicekey');

////////////////////////////////////////////////////////////////////////////////
// Class loader
require_once('voicifyCore/wording_system/TextBuilder.php');
require_once('voicifyCore/wording_system/TextCollection.php');
require_once('voicifyCore/common/JsonUtils.php');

?>
