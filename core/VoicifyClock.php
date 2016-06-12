<?php

/** To generate a speaked hour speaked sound */
class VoicifyClock {

	/** Heure ciblé à traiter */
	private $targetHour;

	/** Text brut */
	private $rawText;

	/** Text final à prononcer */
	private $text;

	/** Liste de varaibles brut à injecter dans le text */
	private $hourList;

	/** Prefix au texte */
	const PREFIX = 'clock';

	/** Mettre en cache la phrase générée ou non */
	const CACHE = true;

	/** Contient tout les textes */
	private $clockCollection;

	/***************************************************************************
	* Main contructor
	*/
	public function __construct ($targetHour) {
		$this->computeHours($targetHour);
		$this->loadconfig();
	}

	/***************************************************************************
	* Construction de la listes des heures
	*/
	private function computeHours($targetHour) {

		if ($targetHour != null)
			$this->targetHour = $targetHour;
		else
			$this->targetHour = $this->currentHour();

		$this->hourList = array();
		$this->hourList[0] = $this->targetHour;

		$unshift = 0;
		for ($i=1; $i<24; $i++) {
			if ($this->targetHour + $i > 23)
				$unshift = 24;
			$this->hourList[$i] = $this->targetHour + $i - $unshift ;
		}

		$this->hourList[24] = $this->hour12();
	}

	/***************************************************************************
	* Obtenir l'heure actuel (Décalée de 2 minutes pour palier au manque de précisions)
	*/
	private function currentHour() {
		$datetime = new DateTime();
		$datetime->add(new DateInterval('PT2M'));
		return intval($datetime->format('G'));
	}

	/***************************************************************************
	* Donne l'heure actuel au format 12 heures
	*
	* @return Juste le chiffre de l'heure
	*/
	private function hour12() {
		$hour24 = $this->targetHour;
		$hour = $hour24;

		if ($hour24 > 12)
			$hour = $hour24 - 12;

		return $hour;
	}

	/***************************************************************************
	* Chargement des fichiers de config et texte
	*/
	private function loadconfig() {
		$this->clockCollection = ClockCollection::getInstance();
	}

	/***************************************************************************
	* Changer le moteur de génération
	*/
	// TODO : Methode générique pour gérer tout les type de paramètre possible : Setter magic
	private function setEngine($engine) {
		$this->confArray['engine'] = $engine;
	}

	/***************************************************************************
	* To get generated text
	*/
	public function getHour() {
		return $this->targetHour;
	}

	/***************************************************************************
	* To get generated text
	*/
	public function getText() {
		return $this->text;
	}

	/***************************************************************************
	* To get generated text */
	public function getRawText() {
		return $this->rawText;
	}

	/***************************************************************************
	* To get input repalced vars
	*/
	public function getHourList() {
		return $this->hourList;
	}

	/***************************************************************************
	* To generate the sound
	*/
	public function process () {
		try {
			// Get a random text corresponding to target Hour
			$this->rawText = $this->clockCollection->getText($this->targetHour);

			// Populate the text with vars
			$this->text = WordingBuilder::process($this->rawText, $this->hourList);

			// Generate and play the sound
			$this->soundSystemProcess();

		} catch (Exception $e) {
			throw new Exception("Unable to Speak the clock for hour '$this->targetHour' ! {$e->getMessage()}");
		}
	}

	/***************************************************************************
	* Send the text to the sound system
	*/
	public function soundSystemProcess () {
		// TODO dynamque engine switch
		$oKarotz = new OpenKarotz();
		$oKarotz->play($this->text, SELF::PREFIX, SELF::CACHE);
	}
}
