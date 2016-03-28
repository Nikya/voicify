<!-- RESULT SIDE -------------------------------------------------------------->
	<div class="row">
		<div class="col-xs-6">
			<h3>Result</h3>

			<blockquote class="phrase">Nothing to say !</blockquote>

			<div id="calledUrl"><?php echo urldecode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?></div>

			<div id="wait"><img src="./core/view/aside/wait.gif"/><br/></div>

			<pre class="console" id="consolePlay" style="margin-top:0"><?php

				// Initialisation
				require_once('./core/wordingApi.php');
				$finalPhrase = 'Nothing to say !';

				// Lecture des paramètres
				$pVoicekey	= (isset($_GET['voicekey']) and !empty($_GET['voicekey']))			? $_GET['voicekey'] : "";
				$pVar0		= (isset($_GET['vars'][0]) and !empty($_GET['vars'][0]))			? $_GET['vars'][0] : "";
				$pVar1		= (isset($_GET['vars'][1]) and !empty($_GET['vars'][1]))			? $_GET['vars'][1] : "";
				$pVar2		= (isset($_GET['vars'][2]) and !empty($_GET['vars'][2]))			? $_GET['vars'][2] : "";
				$pVar3		= (isset($_GET['vars'][3]) and !empty($_GET['vars'][3]))			? $_GET['vars'][3] : "";
				$pVar4		= (isset($_GET['vars'][4]) and !empty($_GET['vars'][4]))			? $_GET['vars'][4] : "";

				// Construction de la liste des voicekey
				$selOption = "";
				$vkArray = getVoiceKeyList();
				foreach ($vkArray as $vk) {
					$strSelected = ($vk == $pVoicekey) ? " selected " : "";
					$selOption .= "<option$strSelected>$vk</option>";
				}

				// Construction de l'autocomplete des subvoicekey
				$svkArray = getSubvoicekeyList();
				$svkStr = '';
				foreach ($svkArray as $svk) {
					$svkStr .= $svk.", ";
				}
				$svkStr .= ' ou texte libre.';

				// Traitement de l'URL d'appel
				$targetURL = urldecode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

				echo 'Initialised !';
			?></pre>
		</div>

<!-- PLAY VOICEKEY -------------------------------------------------------------->
		<div class="col-xs-6">
			<h3>Play voicekey</h3>
			<form id="playVoicekey">
				<input type="hidden" name="action" value="play_voicekey">
				<fieldset class="form-group">
					<label for="voicekey">Voicekey</label>
					<select class="form-control" id="voicekey" name="voicekey"><?php echo $selOption; ?></select>
				</fieldset>
				<fieldset class="form-group">
					<label for="var1">Vars</label>
					<small class="text-muted" style="display: block; font-size:0.7em;"><?php echo $svkStr ?></small>
					<input type="text" class="form-control" id="var1" name="vars[]" placeholder="{0}" value="<?php echo $pVar0; ?>" >
					<input type="text" class="form-control" id="var2" name="vars[]" placeholder="{1}" value="<?php echo $pVar1; ?>" >
					<input type="text" class="form-control" id="var3" name="vars[]" placeholder="{2}" value="<?php echo $pVar2; ?>" >
					<input type="text" class="form-control" id="var4" name="vars[]" placeholder="{3}" value="<?php echo $pVar3; ?>" >
					<input type="text" class="form-control" id="var5" name="vars[]" placeholder="{4}" value="<?php echo $pVar4; ?>" >
					<span style="font-family:monospace; font-weight: bold;" title="Endless list of parameters">...</span>
				</fieldset>

				<button type="submit" class="btn btn-primary">Speak</button>
			</form>
		</div>
	</div>
