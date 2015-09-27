<h1>Voicify sample</h1>

<h2>Command</h2>
<form>
	Voicekey : <input type="text" value="welcome" name="voicekey"></br>
	</br>
	Var : <input type="text" value="James Raynor" name="vars[]"></br>
	Var : <input type="text" value="Aide de camp" name="vars[]"></br>
	Var : <input type="text" value="Tarsonis" name="vars[]"></br>
	</br>
	<input type="submit" value="Say it">
</form>

<hr/>

<h2>Result</h2>
<pre><?php

	if (!isset($_GET['voicekey']) or empty($_GET['voicekey']))
		echo 'Nothing to say !';
	else {
		require_once('./voicify/Voicify.php');

		$v = new voicify($_GET['voicekey']);
		// $v->setEngine();
		// $v->setVoice();

		if (isset($_GET['vars']) and !empty($_GET['vars']))
			$v->setVars($_GET['vars']);

		$v->process();
	}

?></pre>
