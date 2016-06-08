<?php

/**
* [Singleton]
* Pour charger/modifier/sauver la collection de text de Voicekey et sous-voicekey
*/
class WordingCollection {

	/** Instance du singleton */
	private static $instance = null;

	/** Collection des voicekey */
	private $collectionVoicekey;

	/** Collection des sous-voicekey */
	private $collectionSubvoicekey;

	////////////////////////////////////////////////////////////////////////////
	/** Constructeur du singleton
	*/
	private function __construct() {
		$this->loadText();
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir le singleton
	*/
	public static function getInstance() {
		if(is_null(self::$instance))
			self::$instance = new WordingCollection();

		return self::$instance;
	}

	////////////////////////////////////////////////////////////////////////////
	/** Charger le fichier des texts dans cette class de collection
	*/
	private function loadText() {
		$this->collectionVoicekey = JsonUtils::jFile2Array(CONF_FILE_VOICEKEY);
		$this->collectionSubvoicekey = JsonUtils::jFile2Array(CONF_FILE_SUBVOICEKEY);
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir la liste de tout les voicekey existants et leurs données
	*/
	public function getVoiceKeyFull() {
		return $this->collectionVoicekey;
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir la liste de tout les voicekey existants
	*/
	public function getVoiceKeyList() {
		return array_keys($this->collectionVoicekey);
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir la liste de tout les voicekey existants
	*/
	public function getSubvoicekeyList() {
		return array_keys($this->collectionSubvoicekey);
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir une phrase aléatoire correspondante au voicekey */
	public function getText($voicekey) {
			return $this->extractVkText($voicekey);
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir une phrase aléatoire correspondante au sous-voicekey
	*/
	public function replaceSubvoicekey($vars) {
		$newVars = array();
		foreach ($vars as $var) {
			if (array_key_exists($var, $this->collectionSubvoicekey)) {
				$nVar = $this->extractSubvkText($var);
				array_push($newVars, $nVar);
			} else {
				array_push($newVars, $var);
			}
		}

		return $newVars;
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir l'indicateur de prefix correspondante au voicekey
	*/
	public function getPrefix($voicekey) {
		// Ce voicekey est-il inconnue
		if (!array_key_exists($voicekey, $this->collectionVoicekey))
			throw new Exception("Unknow voicekey '$key'");
		else
			return $this->collectionVoicekey[$voicekey]['prefix'];
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir l'indicateur de mise en cache correspondante au voicekey
	*/
	public function getCache($voicekey) {
		// Ce voicekey est-il inconnue
		if (!array_key_exists($voicekey, $this->collectionVoicekey))
			throw new Exception("Unknow voicekey '$key'");
		else
			return $this->collectionVoicekey[$voicekey]['cache']=='1' ? true : false;
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir une text aléatoire correspondante au voicekey
	*/
	private function extractSubvkText($subvoicekey) {
		// Ce subvoicekey est-il inconnue
		if (!array_key_exists($subvoicekey, $this->collectionSubvoicekey))
			throw new Exception("Unknow subvoicekey '$key'");
		else
			return $this->extractFrequencedText($this->collectionSubvoicekey[$subvoicekey]);
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir une text aléatoire correspondante au voicekey
	*/
	private function extractVkText($voicekey) {
		// Ce voicekey est-il inconnue
		if (!array_key_exists($voicekey, $this->collectionVoicekey))
			throw new Exception("Unknow voicekey '$key'");
		else
			return $this->extractFrequencedText($this->collectionVoicekey[$voicekey]['textList']);
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir un texte extrait de la collection en fonction de sa fréquence
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

		return $this->rand1FromArray($frequencedTextList);
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir une entré aléatoire à partir d'un array
	*
	* @param inArray Tableau où chercher
	* @return Une entré choisie en retour
	*/
	private function rand1FromArray($inArray) {
		if (count($inArray) > 0) {
			$randKey = array_rand($inArray, 1);
			$i = $inArray[$randKey];
			return $i;
		} else
			throw new Exception(__FUNCTION__.' Array is empty');
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir le contenue complet des voicekey au format Json */
	public function setAllVoicekeyFromJsonStr($dataStr) {
		JsonUtils::jString2JFile($dataStr, CONF_FILE_VOICEKEY);
	}
}
