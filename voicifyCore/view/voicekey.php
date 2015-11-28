<form>
	<fieldset class="form-group">
		<label for="voicekey">Voicekey</label>
		<input type="text" class="form-control" id="voicekey" name="voicekey" placeholder="saisir un voicekey" value="welcome" >
	</fieldset>
	<fieldset class="form-group">
		<label for="var1">Vars</label>
		<input type="text" class="form-control" id="var1" placeholder="variable" name="vars[]" value="James Raynor" >
		<input type="text" class="form-control" id="var2" placeholder="variable" name="vars[]" value="Aide de camp">
		<input type="text" class="form-control" id="var3" placeholder="variable" name="vars[]" value="Tarsonis" >
	</fieldset>
	<button type="submit" class="btn btn-primary">Parler</button>
</form>

<!-- RESULT --------------------------------------->
<br/>
<pre class="console"><?php

	$finalPhrase = 'Nothing to say !';

	if (!isset($_GET['voicekey']) or empty($_GET['voicekey']))
		echo $finalPhrase;
	else {
		require_once('./voicifyCore/Voicify.php');

		$v = new voicify($_GET['voicekey']);
		// $v->setEngine();
		// $v->setVoice();

		if (isset($_GET['vars']) and !empty($_GET['vars']))
			$v->setVars($_GET['vars']);

		$v->process_tmp();

		$finalPhrase = $v->getLastText();
	}
?></pre>
<br/>
<blockquote class="phrase "><?php echo $finalPhrase ?></blockquote>
