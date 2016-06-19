<?php

/** To generate a notification speaked sound */
class OpenKarotz {

	private $ip = '192.168.1.8';

	private $port = '80';

	private $voice = 'margaux';

	/** Conversion des prefix */
	const PREFIX_MAP = array (
			'no' => '',
			'default' => 'ICI Ruby ! ',
			'clock' => 'Tic. Tac. '
		);

	/***************************************************************************
	* Send the text to the sound system
	*/
	public function play($tts, $prefix, $cache) {
		$noCache = $cache ? '0' : '1';
		$prefixTxt = SELF::PREFIX_MAP[$prefix];

		$enctext = urlencode($prefixTxt . $tts);

		$url = "{$this->ip}:{$this->port}/cgi-bin/tts?voice={$this->voice}&nocache=$noCache&text=$enctext";

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$this->playPrefixSound($prefix);

		$res = curl_exec($ch);

		if ($res===false)
			throw new Exception("OpenKarotz fail to play $url");
	}

	/***************************************************************************
	* Play a stored sound before playing the tts
	*/
	private function playPrefixSound($prefix) {
		if ($prefix != 'no') {
			$url = "{$this->ip}:{$this->port}/cgi-bin/sound?id=prefixsound_$prefix";

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$res = curl_exec($ch);

			if ($res === false)
				traceError(__FUNCTION__, "Can't play Prefix Sound $prefix with $url");
		}
	}
}
