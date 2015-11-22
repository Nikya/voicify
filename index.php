<?php
	// Affichage des erreurs
	ini_set('display_errors',1);
	error_reporting(E_ALL);
?>
<style>
	.voicifyRes {
		font-style: italic;
		border: 1px dashed black;
		padding: 5px;
	}
</style>

<h1>Home Voicify sample</h1>

<!-- CONFIGURATION --------------------------------------->
<hr/>
<h2>Configuration</h2>
	<li><a href="configGlobal.php">Global</a></li>
	<li><a href="configText.php">Texts</a></li>

<!-- COMMAND --------------------------------------->
<hr/>
<h2>Command</h2>
<form>
	Voicekey : <input type="text" value="welcome" name="voicekey"></br>
	</br>
	Vars : <input type="text" value="James Raynor" name="vars[]">
	<input type="text" value="Aide de camp" name="vars[]">
	<input type="text" value="Tarsonis" name="vars[]">
	</br>
	</br>
	<input type="submit" value="Say it">
</form>

<!-- RESULT --------------------------------------->
<hr/>
<h2>Result</h2>

<pre><?php

	// Affichage des erreurs
	ini_set('display_errors',1);
	error_reporting(E_ALL);

	if (!isset($_GET['voicekey']) or empty($_GET['voicekey']))
		echo 'Nothing to say !';
	else {
		require_once('./voicifyCore/Voicify.php');

		$v = new voicify($_GET['voicekey']);
		// $v->setEngine();
		// $v->setVoice();

		if (isset($_GET['vars']) and !empty($_GET['vars']))
			$v->setVars($_GET['vars']);

		$v->process_tmp();

		echo "<span class=\"voicifyRes\">" . $v->getLastText() ."</span>";
	}

?></pre>
