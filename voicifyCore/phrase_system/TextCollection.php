<?php

/**
* [Singleton]
* Pour charger/modifier/sauver la collection de text de Voicekey et sous-voicekey
*/
class TextCollection {

	/** Instance du singleton */
	private $instance = null;

	////////////////////////////////////////////////////////////////////////////
	/** Constructeur du singleton*/
	private function __construct() {
		$this->loadText();
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir le singleton */
	public static function getInstance() {
		if(is_null(self::$instance))
			self::$_instance = new TextCollection();

		return self::$_instance;
	}

	////////////////////////////////////////////////////////////////////////////
	/** Charger le fichier des texts dans cette class de collection */
	private function loadText() {
		// TODO a impl

	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir une phrase aléatoire correspondante au voicekey */
	public function getPhrase($voicekey) {
		// TODO a impl
		return "C'est une phrase de test afforte par votre Texte collection";
	}

	////////////////////////////////////////////////////////////////////////////
	/** Obtenir une entré aléatoire à partir d'un array
	*
	* @param inArray Tableau où chercher
	* @return Une entré choisie en retour
	*/
	private static function rand1FromArray($inArray) {
		if (count($inArray) > 0) {
			$randKey = array_rand($inArray, 1);
			$i = $inArray[$randKey];
			return $i;
		} else
			throw new Exception(__FUNCTION__.' Array is empty');
	}
}
