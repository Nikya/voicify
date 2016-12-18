<?php

/** To generate a notification speaked sound */
class Imperihome {

	private $ip = '192.168.1.221';

	private $port = 8080;

	/** Conversion des prefix */
	const PREFIX_MAP = array (
			'no' => '',
			'default' => 'Ici Blèblette ! ',
			'clock' => 'Tic, Tac. '
		);

	/***************************************************************************
	* Send the text to the sound system
	*/
	public function play($tts, $prefix, $cache) {
		$prefixTxt = SELF::PREFIX_MAP[$prefix];

		$enctext = urlencode($prefixTxt . $tts);

		$url = "{$this->ip}:{$this->port}/api/rest/speech/tts?text=$enctext";

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//$this->playPrefixSound($prefix);

		$res = curl_exec($ch);

		if ($res===false)
			throw new Exception("Imperihome fail to play $url");
	}
}
