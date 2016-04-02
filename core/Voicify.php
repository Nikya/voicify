<?php

/** To generate a notification speaked sound */
class Voicify {
	/** Default voicekey when one it's not found */
	const DEFAULT_VOICEKEY = 'unknowVoicekey';

	/** Voicekey à traiter */
	private $voicekey;

	/** Text brut */
	private $rawText;

	/** Text final à prononcer */
	private $text;

	/** Liste de varaibles brut à injecter dans le text */
	private $vars = array();

	/** Liste de varaibles commutés à injecter dans le text */
	private $commuteVars = array();

	/** Array des config par défaut */
	private $confArray;

	/** Contient tout les textes */
	private $WordingCollection;

	////////////////////////////////////////////////////////////////////////////
	/** Main contructor */
	public function __construct ($voicekey) {
		$this->voicekey = $voicekey;
		$this->loadconfig();
	}

	////////////////////////////////////////////////////////////////////////////
	/** Chargement des fichiers de config et texte */
	private function loadconfig() {
		//$this->confArray = JsonUtils (CONF_FILE_GLOBAL);
		$this->WordingCollection = WordingCollection::getInstance();
	}

	////////////////////////////////////////////////////////////////////////////
	/** Set vars */
	public function setVars($vars) {
		$this->vars = array_filter($vars);
	}

	////////////////////////////////////////////////////////////////////////////
	/** Changer le moteur de génération */
	// TODO : Methode générique pour gérer tout les type de paramètre possible : Setter magic
	private function setEngine($engine) {
		$this->confArray['engine'] = $engine;
	}

	////////////////////////////////////////////////////////////////////////////
	/** To get generated text */
	public function getText() {
		return $this->text;
	}

	////////////////////////////////////////////////////////////////////////////
	/** To get generated text */
	public function getRawText() {
		return $this->rawText;
	}

	////////////////////////////////////////////////////////////////////////////
	/** To get input repalced vars */
	public function getVars() {
		return $this->vars;
	}

	////////////////////////////////////////////////////////////////////////////
	/** To get input repalced vars */
	public function getCommuteVars() {
		return $this->commuteVars;
	}

	////////////////////////////////////////////////////////////////////////////
	/** To generate the sound */
	public function process () {
		// Get a random text corresponding to the voicekey
		try {
			$this->rawText = $this->WordingCollection->getText($this->voicekey);

		// OR the default one if it's a unknow voicekey
		} catch (Exception $e) {
			try {
				$this->rawText = $this->WordingCollection->getText(Voicify::DEFAULT_VOICEKEY);
				array_unshift($this->vars, $this->voicekey);
			} catch (Exception $e) {
				throw new Exception("Unknow voicekey '$this->voicekey' and default voicekey '" .Voicify::DEFAULT_VOICEKEY. "' is missing !!! ");
			}
		}

		// Replace some vars to corresponding sub-voicekey
		$this->commuteVars = $this->WordingCollection->replaceSubvoicekey($this->vars);

		// Populate the text with vars
		$this->text = WordingBuilder::process($this->rawText, $this->commuteVars);

		// Generate and play the sound
		$this->soundSystemProcess_tmp();
		//$this->soundSystemProcess();
	}

	////////////////////////////////////////////////////////////////////////////
	/** Send the text to the sound system */
	public function soundSystemProcess () {
		//   ./executer_action.py 192.168.1.2 9090 0 0 94:de:80:72:23:f6  parle "Je suis ton paire. la force soit avec toi coucou bouh" http://192.168.1.2 100 dingdong google fr
		$cmd = "./core/sound_system/executer_action.py 192.168.1.2 9090 0 0 {$this->confArray['squeezplayermac']} parle \"{$this->text}\" http://192.168.1.2 100 {$this->confArray['jingle']} {$this->confArray['engine']} {$this->confArray['params']}";
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

		$enctext = urlencode('ICI Ruby. ' . $this->text);

		$url = "$ip:$port/cgi-bin/tts?voice=$voice&nocache=$noCache&text=$enctext";

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//$res = curl_exec($ch);
$res = true;

		if ($res===false)
			throw new Exception("process_tmp fail to process $url");
	}
}
