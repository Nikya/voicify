<?php

	if (!isset($_GET['tts']) or empty($_GET['tts'])) {
		Console::e('base.playMain', 'No TTS to process');
	} else {
		$say = $_GET['tts'];
	}
