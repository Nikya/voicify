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

	/** Appliquer un prefix ou non */
	private $prefix = 'default';

	/** Mettre en cache la phrase générée ou non */
	private $cache = false;

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
		$this->wordingCollection = WordingCollection::getInstance();
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
		$failButSpeaked = false;

		// Get a random text corresponding to the voicekey
		try {
			$this->rawText = $this->wordingCollection->getText($this->voicekey);
			$this->prefix = $this->wordingCollection->getPrefix($this->voicekey);
			$this->cache = $this->wordingCollection->getCache($this->voicekey);

		// OR the default one if it's a unknow voicekey
		} catch (Exception $e) {
			try {
				$this->rawText = $this->wordingCollection->getText(Voicify::DEFAULT_VOICEKEY);
				array_unshift($this->vars, $this->voicekey);
				$failButSpeaked = true;
			} catch (Exception $e) {
				throw new Exception("Unknow voicekey '$this->voicekey' and default voicekey '" .Voicify::DEFAULT_VOICEKEY. "' is missing !!! ");
			}
		}

		// Replace some vars to corresponding sub-voicekey
		$this->commuteVars = $this->wordingCollection->replaceSubvoicekey($this->vars);

		// Populate the text with vars
		$this->text = WordingBuilder::process($this->rawText, $this->commuteVars);

		// Generate and play the sound
		$this->soundSystemProcess();

		if ($failButSpeaked)
			throw new Exception("Unknow voicekey '$this->voicekey'");
	}

	////////////////////////////////////////////////////////////////////////////
	/** Send the text to the sound system */
	public function soundSystemProcess () {
		// TODO dynamque engine switch
		// $oKarotz = new OpenKarotz();
		// $oKarotz->play($this->text, $this->prefix, $this->cache);
		$ih = new Imperihome();
		$ih->play($this->text, $this->prefix, $this->cache);
	}
}
