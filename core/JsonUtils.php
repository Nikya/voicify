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
		@$content = file_get_contents($jFilePath);

		if ($content === false)
			throw new Exception("Can't read input file $jFilePath");

		return JsonUtils::jString2Array($content);
	}

	////////////////////////////////////////////////////////////////////////////
	/** Sauvegarde une chaine Json dans un fichier
	*
	* @param $dataStr Donnée brut au format Json
	* @param $jFilePath Chemin du fichier dans lequel sauvegarder
	*/
	public static function jString2JFile($dataStr, $jFilePath) {
		// Check Json
		$jDecode = json_decode($dataStr);
		JsonUtils::throwLastJsonError(__FUNCTION__);

		$dataStr2 = json_encode($jDecode, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
		JsonUtils::throwLastJsonError(__FUNCTION__);

		if (file_put_contents($jFilePath, $dataStr2)===false)
			throw new Exception("Can't write the Json file $jFilePath");
	}

	////////////////////////////////////////////////////////////////////////////
	/** Charge une chaine Json en tant que tableau associatif
	*
	* @param $stringContent Contenue Json
	* @return Le contenue au dans un tableau associatif
	*/
	public static function jString2Array($jStringContent) {
		$jDecode = json_decode($jStringContent, true);

		JsonUtils::throwLastJsonError(__FUNCTION__);

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
			JsonUtils::throwLastJsonError(__FUNCTION__);

		return $jString;
	}

	/***************************************************************************
	* Sauvegarde un tableau dans un fichier Json
	*
	* @param $dataArray Tableau de données
	* @param $jFilePath Chemin du fichier dans lequel sauvegarder
	*/
	public static function array2JFile($dataArray, $jFilePath) {
		$dataStr = self::array2JString($dataArray);
		self::jString2JFile($dataStr, $jFilePath);
	}

	////////////////////////////////////////////////////////////////////////////
	/** Convertit un tableau en chaine Json
	*
	* @param $array tableau à Convertir Json
	* @return La représentation Json de ce tableau
	*/
	public static function throwLastJsonError($step) {
		$jErrorMsg;
		$jErrorCode=json_last_error();

		switch ($jErrorCode) {
			case JSON_ERROR_NONE:
				$jErrorMsg = 'No error';
			break;
			case JSON_ERROR_DEPTH:
				$jErrorMsg = 'Maximum depth reached';
			break;
			case JSON_ERROR_STATE_MISMATCH:
				$jErrorMsg = 'Inadequate modes or underflow';
			break;
			case JSON_ERROR_CTRL_CHAR:
				$jErrorMsg = 'Error checking characters';
			break;
			case JSON_ERROR_SYNTAX:
				$jErrorMsg = 'Syntax error; JSON malformed';
			break;
			case JSON_ERROR_UTF8:
				$jErrorMsg = 'UTF-8 characters malformed, probably an encoding error';
			break;
			default:
				$jErrorMsg = 'Unknown error';
			break;
		}

		if ($jErrorCode<>JSON_ERROR_NONE)
			throw new Exception("JSON_ERROR_$jErrorCode at JsonUtils step $step. $jErrorMsg", $jErrorCode);
	}
}
