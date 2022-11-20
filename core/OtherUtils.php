<?php

/**
* Autres utilitaire
*/
class OtherUtils {

	/** ***********************************************************************
	* Split a long text into differents sub-texts, according to a specific max lenght.
	* 
	* @param text A long text to split
	* @param maxLength Maximum lenght for each sub-texts
	* 
	* @return array Of splitted text into sub-part
	*/
	public static function splitLongText($text, $maxLength=256) {
		$paragraphs = array();
		$patternEndOfSentence = '/(?<=[\.\?\!]\s)/';

		$sentences = preg_split($patternEndOfSentence, $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

		if ($sentences===false) {
			$errMsg = preg_last_error_msg();
			throw new Exception("Fail to split the long text : $errMsg. Text:$text");
		}

		$newParagraph = '';

		while (count($sentences)>0) {
			$sentence = array_shift($sentences);

			if (strlen($newParagraph.$sentence) < $maxLength) {
				$newParagraph .= $sentence ;
			} else {
				array_push($paragraphs, $newParagraph);
				$newParagraph = $sentence;
			}
		}

		array_push($paragraphs, $newParagraph);
		return $paragraphs;
	}
}
