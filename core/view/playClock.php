<!-- RESULT SIDE -------------------------------------------------------------->
	<div class="row">
		<div class="col-xs-6">
			<h3>Result</h3>

			<blockquote class="phrase">Nothing to say !</blockquote>

			<div id="calledUrl"><?php echo urldecode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?></div>

			<div id="wait"><img src="./core/view/aside/wait.gif"/><br/></div>

			<pre class="console" id="consolePlay" style="margin-top:0"><?php

				// Initialisation
				$finalPhrase = 'Nothing to say !';

				$pTargetHour = ( isset($_GET['targetHour']) and !empty($_GET['targetHour']) )		? $_GET['targetHour'] : '';

				// Traitement de l'URL d'appel
				$targetURL = urldecode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

				echo 'Initialised !';
			?></pre>
		</div>

<!-- PLAY VOICEKEY -------------------------------------------------------------->
		<div class="col-xs-6">
			<h3>Play a clock</h3>
			<form id="ajaxPlayForm">
				<input type="hidden" name="action" value="play_clock">
				<fieldset class="form-group">
					<label for="voicekey">Traget Hour</label>
					<input type="number" min="0" max="23" class="form-control" id="targetHour" name="targetHour" placeholder="auto" value="<?php echo $pTargetHour; ?>" >
				</fieldset>
				<fieldset class="checkbox">
					<label for="verbose"><input type="checkbox" name="verbose" value="yes" id="verbose"> Verbose mode</label>
					<br/>
					<small class="text-muted">To display more details in the speak result</small>
				</fieldset>

				<button type="submit" class="btn btn-primary">Speak</button>
			</form>
		</div>
	</div>
