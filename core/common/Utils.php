<?php

/**
* Utilitaire generic
*/
class Utils {

	/***************************************************************************
	* Obtenir une entrée aléatoire d'un array
	*
	* @param inArray Tableau où chercher
	* @return Une entré choisie en retour
	*/
	public static function rand1FromArray($inArray) {
		if (count($inArray) > 0) {
			$randKey = array_rand($inArray, 1);
			$i = $inArray[$randKey];
			return $i;
		} else
			throw new Exception(__FUNCTION__.' Array is empty');
	}
}
