<?php

/** To build phrases from a voiceKey */
class PhraseBuilder {

	////////////////////////////////////////////////////////////////////////////
	/** Generate the phrase */
	public static function generate($textArray, $voicekey, $vars) {
		$tts = null;

		if (array_key_exists($voicekey, $textArray)) {
			$tts = SELF::rand1FromArray($textArray[$voicekey]);
			$tts = self::fill($tts, $vars);
		}
		else
			throw new Exception(__FUNCTION__." Voicekey not found '$voicekey' ");

		return $tts;
	}

	////////////////////////////////////////////////////////////////////////////
	/** Fill the places Holder withs vars */
	private static function fill($tts, $vars) {

		if ($vars != null and !empty($vars)) {
			$hFormater = msgfmt_create('fr_FR', $tts);
			$tts = msgfmt_format($hFormater, $vars);
		}

		return $tts;
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
