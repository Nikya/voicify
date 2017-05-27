<!--============================================================================
= Main Play View
=============================================================================-->
<?php
	$config = Config::getInstance();

	// Voicekey dropdown list
	// And text collection
	$strOptVoicekey = '';
	$voicekeyDatadisp = array();

	try {
		$cVoicekey = $config->getModuleConfig('voicekey', 'voicekey');
		ksort($cVoicekey);
		$firstLetter='';
		$firstLetterPrev='';
		foreach ($cVoicekey as $voicekey => $voicekeyConf) {
			// Options
			$firstLetter = substr($voicekey, 0, 1);
			if(strcmp($firstLetter, $firstLetterPrev) != 0) {
				$strOptVoicekey .= '<optgroup label="'.strtoupper($firstLetter).'">';
			}
			$strOptVoicekey .= "<option>$voicekey</option>";
			$firstLetterPrev = $firstLetter;

			// Data disp
			$aText = array();
			foreach ($voicekeyConf['textList'] as $t) {
				array_push($aText, "[x{$t['frequency']}]  {$t['text']}");
			}
			$voicekeyDatadisp[$voicekey] = $aText;
		}
	} catch (Exception $e) {
		Console::e('playVoicekey', 'Fail to load Voicekey list', $e);
	}

	// SubVoicekey list
	$strSubvoicekey = '';
	try {
		$cSubvoicekey = $config->getModuleConfig('voicekey', 'subvoicekey');
		ksort($cSubvoicekey);
		foreach ($cSubvoicekey as $subvoicekey => $subvoicekeyConf) {
			$strSubvoicekey .= "<li>$subvoicekey </li>";
		}
	} catch (Exception $e) {
		Console::e('playVoicekey', 'Fail to load Subvoicekey list', $e);
	}

?>

	<!-- Voicekey -->
	<fieldset class="form-group">
		<label for="voicekey">Voicekey*</label>
		<small class="text-muted">
			The keyword of the text to speak.
			<br/><a href="./?config=voicekey_cVoicekey">Edit voicekey</a>
		</small>
		<select class="form-control" id="voicekey" name='voicekey' required>
			<option></option>
			<?php echo $strOptVoicekey ?>
		</select>
		<ul id="voicekeytexts" class="datadisp">
		</ul>
	</fieldset>

	<!-- Vars -->
	<fieldset class="form-group">
		<label for="var0">Vars</label>
		<small class="text-muted">The variables to be injected into the placeholders <code>{9}</code> of text to speak.</small>
		<small class="text-muted">They are optional, their number is unlimited.</small>

		<input type="text" class="form-control" id="var0" name="vars[]" placeholder="{0}" value=""/>
		<input type="text" class="form-control" id="var1" name="vars[]" placeholder="{1}" value=""/>
		<input type="text" class="form-control" id="var2" name="vars[]" placeholder="{2}" value=""/>
		<input type="text" class="form-control" id="var3" name="vars[]" placeholder="{3}" value=""/>
		<input type="text" class="form-control" id="var4" name="vars[]" placeholder="{4}" value=""/>
		<input type="text" class="form-control" id="var5" name="vars[]" placeholder="{5}" value=""/>
	</fieldset>

	<!-- Subvoicekey -->
	<fieldset class="form-group">
		<label for="subvoicekeylist">Subvoicekey</label>
		<small class="text-muted">
			Keywords that can be placed in the above fields to have variables derived from Subvoicekey.
			<br/><a href="./?config=voicekey_cSubvoicekey">Edit subvoicekey</a>
		</small>
		<ul id="subvoicekeylist" class="datadisp">
			<?php echo $strSubvoicekey ?>
		</ul>
		<small class="text-muted">You can double-click a subvoicekey and then drag it to a var field <code>{0}</code>.</small>
	</fieldset>

	<script>
		/** Display selected voicekey texts **/
		var voicekeyDatadisp  = <?php echo json_encode($voicekeyDatadisp); ?>;

		$('#voicekey').change(function() {
			var ulVoicekeytexts = document.getElementById("voicekeytexts");
			ulVoicekeytexts.innerHTML = '';

			for (var i = 0; i < voicekeyDatadisp[this.value].length; i++) {
				var txtdisp = voicekeyDatadisp[this.value][i];
				txtdisp = txtdisp.replace(/(\{\d\})/g, "<code>$1</code>");
				var liTextdisp = document.createElement("li");
				liTextdisp.innerHTML = txtdisp;
				ulVoicekeytexts.appendChild(liTextdisp);
			}
		});
	</script>
