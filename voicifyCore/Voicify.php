<?php

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
	const DIR_CONF_TXT = 'voicifyCore/config/text.json';

	/** Array des config par défaut */
	private $confArray;

	/** Contient tout les textes */
	private $textCollection;

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
		$this->textCollection = TextCollection::getInstance(SELF::DIR_CONF_TXT);
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
	/** To all available voicekey */
	public function getVoiceKeyList() {
		return $this->textCollection->getAllVoicekey();
	}

	////////////////////////////////////////////////////////////////////////////
	/** To all available voicekey */
	public function getSubvoicekeyList() {
		return $this->textCollection->getAllSubvoicekey();
	}

	////////////////////////////////////////////////////////////////////////////
	/** To generate the sound */
	public function process () {
		// Get a random text corresponding to the voicekey
		$text = $this->textCollection->getText($this->voicekey);

		// Replace some vars to corresponding sub-voicekey
		$this->vars = $this->textCollection->replaceSubvoicekey($this->vars);

		// Populate the text with vars
		$this->tts = TextBuilder::process($text, $this->vars);

		// Generate and play the sound
		//$this->soundSystemProcess_tmp();
		$this->soundSystemProcess();
	}

	////////////////////////////////////////////////////////////////////////////
	/** Send the text to the sound system */
	public function soundSystemProcess () {
		//   ./executer_action.py 192.168.1.2 9090 0 0 94:de:80:72:23:f6  parle "Je suis ton paire. la force soit avec toi coucou bouh" http://192.168.1.2 100 dingdong google fr
		$cmd = "./voicifyCore/sound_system/executer_action.py 192.168.1.2 9090 0 0 {$this->confArray['squeezplayermac']} parle \"{$this->tts}\" http://192.168.1.2 100 {$this->confArray['jingle']} {$this->confArray['engine']} {$this->confArray['params']}";
		$cmd .= " 2>&1"; // To get Error also

		$res = exec ($cmd, $output, $return_var);

		if ($return_var!=0) {
			$foutput = print_r($output, true);
			throw new Exception("Fail to process the sound commande : $cmd \n $foutput", 1);
		}
	}

	////////////////////////////////////////////////////////////////////////////
	/** Send the text to the sound system */
	public function soundSystemProcess_tmp() {
		$ip = "192.168.1.8";
		$port = "80";
		$voice = "margaux";

		$noCache = 1;

		$encTts = urlencode($this->tts);

		$url = "$ip:$port/cgi-bin/tts?voice=$voice&nocache=$noCache&text=$encTts";

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$res = curl_exec($ch);

		if ($res===false)
			throw new Exception("process_tmp fail to process $url");
	}
}
