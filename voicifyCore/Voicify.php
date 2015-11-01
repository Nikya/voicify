<?php
if (!extension_loaded('intl')) trigger_error("Internationalization extension not available see http://php.net/manual/fr/intl.installation.php.", E_USER_WARNING);
require_once('phrase_system/PhraseBuilder.php');

/** To generate a notification speaked sound */
class Voicify {

	/** Voicekey à traiter */
	private $voicekey;

	/** Text final à prononcer */
	private $tts;

	/** Liste de varaibles à injecter dans le tts */
	private $vars;

	/** Config file */
	const DIR_CONF_GLOBAL = 'config/global.ini';

	/** Texts file */
	const DIR_CONF_TXT = 'config/text.ini';

	/** Array des config par défaut */
	private $confArray;

	/** Array des texts */
	private $textArray;

	////////////////////////////////////////////////////////////////////////////
	/** Main contructor */
	public function __construct ($voicekey) {
		$this->voicekey = $voicekey;
		$this->loadconfig();
	}

	////////////////////////////////////////////////////////////////////////////
	/** Chargement des fichiers de config et texte */
	private function loadconfig() {
		$this->confArray = parse_ini_file(SELF::DIR_CONF_GLOBAL, true);
		$this->textArray = parse_ini_file(SELF::DIR_CONF_TXT, true);
	}

	////////////////////////////////////////////////////////////////////////////
	/** Set vars */
	public function setVars($vars) {
		$this->vars = $vars;
	}

	////////////////////////////////////////////////////////////////////////////
	/** Changer le moteur de génération */
	// TODO : Methode générique pour gérer tout les type de paramètre possible : Setter magic
	private function setEngine($engine) {
		$this->confArray['engine'] = $engine;
	}

	////////////////////////////////////////////////////////////////////////////
	/** To get last generated text */
	public function getLastText() {
		return $this->tts;
	}

	////////////////////////////////////////////////////////////////////////////
	/** To generate the sound */
	public function process () {
		$this->tts = PhraseBuilder::generate($this->textArray, $this->voicekey, $this->vars);

		//   ./executer_action.py 192.168.1.2 9090 0 0 94:de:80:72:23:f6  parle "Je suis ton paire. la force soit avec toi coucou bouh" http://192.168.1.2 100 dingdong google fr
		$cmd = "./voicifyCore/sound_system/executer_action.py 192.168.1.2 9090 0 0 {$this->confArray['squeezplayermac']} parle \"{$this->tts}\" http://192.168.1.2 100 {$this->confArray['jingle']} {$this->confArray['engine']} {$this->confArray['params']}";
		$cmd .= " 2>&1"; // To get Error also

		$res = exec ($cmd, $output, $return_var);

		if ($return_var!=0) {
			$foutput = print_r($output, true);
			throw new Exception("Fail to process the sound commande : $cmd \n $foutput", 1);
		}
	}
}
