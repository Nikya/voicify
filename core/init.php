<?php
// DEFINE FILE CONSTANTES
define ('CONF_DIR',		'config');
define ('CONF_FILE_DEFAULT_GLOBAL',			CONF_DIR.'/default_global.json');
define ('CONF_FILE_DEFAULT_VOICEKEY',		CONF_DIR.'/default_voicekey.json');
define ('CONF_FILE_DEFAULT_SUBVOICEKEY',	CONF_DIR.'/default_subvoicekey.json');
define ('CONF_FILE_DEFAULT_CLOCK',			CONF_DIR.'/default_clock.json');
define ('CONF_FILE_GLOBAL',					CONF_DIR.'/global.json');
define ('CONF_FILE_VOICEKEY',				CONF_DIR.'/voicekey.json');
define ('CONF_FILE_SUBVOICEKEY',			CONF_DIR.'/subvoicekey.json');
define ('CONF_FILE_CLOCK',					CONF_DIR.'/clock.json');
define ('MODULE_MARKDOWN',					'core/common/php-markdown/Michelf/Markdown.php');

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
				checkFail ("Can't initialize the '$friendlyName' file (copy '$defaultFile' to '$targetFile') ");
}

////////////////////////////////////////////////////////////////////////////////
// CHECK requirement
if (!extension_loaded('intl'))
	checkFail ("Internationalization extension not available. Install it. See http://php.net/manual/fr/intl.installation.php.");

// Git submodules
if (!file_exists(MODULE_MARKDOWN))
	checkFail ('Git modules are not initialized. Please "git submodule init; git submodule update" to get : "'.MODULE_MARKDOWN.'" ');

////////////////////////////////////////////////////////////////////////////////
// CHECK CONF FILES

// Folder
if (!is_writable(CONF_DIR))
	checkFail ('The configuration FOLDER is not not writable : "'.CONF_DIR.'" ');

// Files
checkAndInit(CONF_FILE_DEFAULT_GLOBAL, CONF_FILE_GLOBAL, 'Global');
checkAndInit(CONF_FILE_DEFAULT_VOICEKEY, CONF_FILE_VOICEKEY, 'Voicekey');
checkAndInit(CONF_FILE_DEFAULT_SUBVOICEKEY, CONF_FILE_SUBVOICEKEY, 'Sub-Voicekey');
checkAndInit(CONF_FILE_DEFAULT_CLOCK, CONF_FILE_CLOCK, 'Clock');

////////////////////////////////////////////////////////////////////////////////
// Class loading
require_once('core/wording_system/WordingBuilder.php');
require_once('core/wording_system/WordingCollection.php');
require_once('core/wording_system/ClockCollection.php');
require_once('core/sound_system/OpenKarotz.php'); // TODO replace by dynamic loader
require_once('core/sound_system/Imperihome.php'); // TODO replace by dynamic loader
require_once('core/common/JsonUtils.php');
require_once('core/common/Utils.php');

?>
