<?php

/**
* Utilitaire de manipulation du format Json en PHP
*/
class JsonUtils {

	////////////////////////////////////////////////////////////////////////////
	/** Charge un fichier Json en tant que tableau associatif
	*
	* @param $filePath Chemin vers le fichier à lire
	* @return Le contenue au dans un tableau associatif
	*/
	public static function jFile2Array($jFilePath) {
		$content = utf8_encode(file_get_contents($jFilePath));

		if ($content === false)
			throw new Exception("Can't read input file $jFilePath");

		return JsonUtils::jString2Array($content);
	}

	////////////////////////////////////////////////////////////////////////////
	/** Charge une chaine Json en tant que tableau associatif
	*
	* @param $stringContent Contenue Json
	* @return Le contenue au dans un tableau associatif
	*/
	public static function jString2Array($jStringContent) {
		$jDecode = json_decode($jStringContent, true);

		JsonUtils::throwLastJsonError("Can't decode the Json String '$jStringContent'");

		return $jDecode;
	}

	////////////////////////////////////////////////////////////////////////////
	/** Convertit un tableau en chaine Json
	*
	* @param $array tableau à Convertir Json
	* @return La représentation Json de ce tableau
	*/
	public static function array2JString($array) {

		$jString = json_encode($array, JSON_UNESCAPED_UNICODE);

		if ($jString === false)
			JsonUtils::throwLastJsonError("Can't encode array to Json String. $array");

		return $jString;
	}

	////////////////////////////////////////////////////////////////////////////
	/** Convertit un tableau en chaine Json
	*
	* @param $array tableau à Convertir Json
	* @return La représentation Json de ce tableau
	*/
	public static function throwLastJsonError($failMsg) {
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
			throw new Exception("$failMsg >>> Json error #$jErrorCode  : $jErrorMsg");
	}
}
