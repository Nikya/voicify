<?php

/**
* [Singleton]
* Pour charger/modifier/sauver la collection de text de Voicekey et sous-voicekey
*/
class TextCollection {

	/** Chemin vers le fichier Json des textes */
	private $textFilePath;

	/** Instance du singleton */
	private static $instance = null;

	/** Collection des voicekey */
	private $collectionVoicekey;

	/** Collection des sous-voicekey */
	private $collectionSubvoicekey;

	////////////////////////////////////////////////////////////////////////////
	/** Constructeur du singleton*/
	private function __construct($textFilePath) {
		$this->textFilePath = $textFilePath;
		$this->loadText();
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir le singleton */
	public static function getInstance($textFilePath) {
		if(is_null(self::$instance))
			self::$instance = new TextCollection($textFilePath);
		elseif (self::$instance->textFilePath != $textFilePath)
			self::$instance = new TextCollection($textFilePath);

		return self::$instance;
	}

	////////////////////////////////////////////////////////////////////////////
	/** Charge un fichier Json en tant que tableau associatif */
	private function jsonFile2Array() {
		$fContent = file_get_contents($this->textFilePath);
		$jDecode = json_decode($fContent, true);

		$jErrorMsg;
		$jErrorCode=json_last_error();

		switch ($jErrorCode) {
			case JSON_ERROR_NONE:
				$jErrorMsg = 'Aucune erreur';
			break;
			case JSON_ERROR_DEPTH:
				$jErrorMsg = 'Profondeur maximale atteinte';
			break;
			case JSON_ERROR_STATE_MISMATCH:
				$jErrorMsg = 'Inadéquation des modes ou underflow';
			break;
			case JSON_ERROR_CTRL_CHAR:
				$jErrorMsg = 'Erreur lors du contrôle des caractères';
			break;
			case JSON_ERROR_SYNTAX:
				$jErrorMsg = 'Erreur de syntaxe ; JSON malformé';
			break;
			case JSON_ERROR_UTF8:
				$jErrorMsg = 'Caractères UTF-8 malformés, probablement une erreur d\'encodage';
			break;
			default:
				$jErrorMsg = 'Erreur inconnue';
			break;
		}

		if ($jErrorCode<>JSON_ERROR_NONE)
			throw new Exception("Json decode error #$jErrorCode  : $jErrorMsg");

		return $jDecode;
	}

	////////////////////////////////////////////////////////////////////////////
	/** Charger le fichier des texts dans cette class de collection */
	private function loadText() {
		$aAllText = $this->jsonFile2Array();

		$this->collectionVoicekey = $aAllText['voicekey'];
		$this->collectionSubvoicekey = $aAllText['subvoicekey'];
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir la liste de tout les voicekey existants */
	public function getAllVoicekey() {
		return array_keys($this->collectionVoicekey);
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir la liste de tout les voicekey existants */
	public function getAllSubvoicekey() {
		return array_keys($this->collectionSubvoicekey);
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir une phrase aléatoire correspondante au voicekey */
	public function getText($voicekey) {
		return $this->extract($voicekey, $this->collectionVoicekey);
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir une phrase aléatoire correspondante au sous-voicekey */
	public function replaceSubvoicekey($vars) {
		$newVars = array();
		foreach ($vars as $var) {
			if (array_key_exists($var, $this->collectionSubvoicekey)) {
				$nVar = $this->extract($var, $this->collectionSubvoicekey);
				array_push($newVars, $nVar);
			} else {
				array_push($newVars, $var);
			}
		}

		print_r($newVars);

		return $newVars;
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir une text aléatoire correspondante au voicekey ou subvoicekey */
	public function extract($key, $arrayCollection) {
		$simpleArray = array();

		// Ce voicekey est-il inconnue
		if (!array_key_exists($key, $arrayCollection))
			throw new Exception("Unknow voicekey '$key'");
		else {
			// Pour chaque contenue de ce voicekey
			foreach ($arrayCollection[$key] as $value) {
				// Si ce n'est pas un tableau, c'est directement un text
				if (!is_array($value))
					array_push($simpleArray, $value);
				else {
					// Un text est présent
					if (array_key_exists('text', $value)) {
						// La fréquence est-elle spécifiée
						if (array_key_exists('frequency', $value)) {
							$freq = $value['frequency'];
							for($i=0; $i<$freq; $i++)
								array_push($simpleArray, $value['text']);
						} else
							array_push($simpleArray, $value['text']);
					}
				}
			}
		}

		if (count($simpleArray)>0)
			print_r($simpleArray);

		return $this->rand1FromArray($simpleArray);
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
	public function getAllVoicekeyToJson() {
		return json_encode($this->collectionVoicekey);
	}
}
