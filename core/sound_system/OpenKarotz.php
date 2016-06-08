<?php

/** To generate a notification speaked sound */
class OpenKarotz {

	////////////////////////////////////////////////////////////////////////////////
	/** Send the text to the sound system */
	public function play($tts, $prefix, $cache) {
		$ip = "192.168.1.8";
		$port = "80";
		$voice = "margaux";

		$noCache = $cache ? '0' : '1';
		$prefixTxt = $prefix=='no' ? '' : 'ICI Ruby ! ';

		$enctext = urlencode($prefixTxt . $tts);

		$url = "$ip:$port/cgi-bin/tts?voice=$voice&nocache=$noCache&text=$enctext";

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$res = curl_exec($ch);

		if ($res===false)
			throw new Exception("process_tmp fail to process $url");
	}
}
