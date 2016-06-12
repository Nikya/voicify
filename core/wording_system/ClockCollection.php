<?php

/**
* [Singleton]
* Pour charger/modifier/sauver la collection de text pour les heures
*/
class ClockCollection {

	/** Instance du singleton */
	private static $instance = null;

	/** Collection des voicekey */
	private $collectionClock;

	/***************************************************************************
	* Constructeur du singleton
	*/
	private function __construct() {
		$this->loadText();
	}

	/***************************************************************************
	* Obtenir le singleton
	*/
	public static function getInstance() {
		if(is_null(self::$instance))
			self::$instance = new ClockCollection();

		return self::$instance;
	}

	/***************************************************************************
	* Charger le fichier des texts dans cette class de collection
	*/
	private function loadText() {
		$this->collectionClock = JsonUtils::jFile2Array(CONF_FILE_CLOCK);
	}

	/***************************************************************************
	* Obtenir la liste de tout les clocks existants et leurs données
	*/
	public function getClockFull() {
		return $this->collectionClock;
	}

	/***************************************************************************
	* Obtenir la liste de tout les voicekey existants
	*/
	public function getVoiceKeyList() {
		return array_keys($this->collectionClock);
	}

	/***************************************************************************
	* Obtenir une phrase aléatoire correspondante à l'heure
	*/
	public function getText($targetHour) {
			return $this->extractClockText($targetHour);
	}

	/***************************************************************************
	* Obtenir une text aléatoire correspondante à l'heure
	*/
	private function extractClockText($targetHour) {
		$targetCollection = $this->collectionClock['neutral'];

		// Cette heure posséde t'elle un collection dédiée
		if (array_key_exists($targetHour, $this->collectionClock))
			if(mt_rand(1,3)<3) // 2 chance sur 3 de prendre la collection dédié dans ce cas
				$targetCollection = $this->collectionClock[$targetHour];

		return $this->extractFrequencedText($targetCollection);
	}

	/***************************************************************************
	* Obtenir un texte extrait de la collection en fonction de sa fréquence
	*/
	private function extractFrequencedText($textList) {
		$frequencedTextList = array();

		foreach ($textList as $textElement) {
			// La fréquence est-elle spécifiée
			if (array_key_exists('frequency', $textElement)) {
				$freq = $textElement['frequency'];
				for($i=0; $i<$freq; $i++)
					array_push($frequencedTextList, $textElement['text']);
			} else
				array_push($frequencedTextList, $textElement['text']);
		}

		return Utils::rand1FromArray($frequencedTextList);
	}

	/***************************************************************************
	* Enregistrer le contenue complet des clock au format Json dans le fichier dédié
	*/
	public function setAllClockFromJsonStr($dataStr) {
		JsonUtils::jString2JFile($dataStr, CONF_FILE_CLOCK);
	}
}
