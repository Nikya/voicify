<?php

/** To build texts with his vars */
class WordingBuilder {

	////////////////////////////////////////////////////////////////////////////
	/** Generate the filled text */
	public static function process($text, $vars) {
		$text = self::fill($text, $vars);
		return $text;
	}

	////////////////////////////////////////////////////////////////////////////
	/** Fill the places Holder withs vars */
	private static function fill($text, $vars) {
		if ($vars != null and !empty($vars)) {
			$hFormater = msgfmt_create('fr_FR', $text);
			$text = msgfmt_format($hFormater, $vars);
		}

		return $text;
	}
}
