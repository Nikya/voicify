<!--============================================================================
= Main Play View
=============================================================================-->
<?php
	$config = Config::getInstance();

	// Voicekey dropdown list
	$cVoicekey = $config->getModuleConfig('voicekey', 'voicekey');
	ksort($cVoicekey);
	$strOptVoicekey = '';
	$firstLetter='';
	$firstLetterPrev='';
	foreach ($cVoicekey as $voicekey => $voicekeyConf) {
		$firstLetter = substr($voicekey, 0, 1);
		if(strcmp($firstLetter, $firstLetterPrev) != 0) {
			$strOptVoicekey .= '<optgroup label="'.strtoupper($firstLetter).'">';
		}
		$strOptVoicekey .= "<option>$voicekey</option>";
		$firstLetterPrev = $firstLetter;
	}

	// SubVoicekey list
	$cSubvoicekey = $config->getModuleConfig('voicekey', 'subvoicekey');
	ksort($cSubvoicekey);
	$strSubvoicekey = '';
	foreach ($cSubvoicekey as $subvoicekey => $subvoicekeyConf) {
		$strSubvoicekey .= "<li>$subvoicekey </li>";
	}

?>

	<!-- Voicekey -->
	<fieldset class="form-group">
		<label for="voicekey">Voicekey*</label>
		<small class="text-muted">The keyword of the text to speak</small>
		<select class="form-control" id="voicekey" name='voicekey'>
			<option></option>
			<?php echo $strOptVoicekey ?>
		</select>
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
		<small class="text-muted">Keywords that can be placed in the above fields to have variables derived from Subvoicekey</small></small>
		<ul id="subvoicekeylist">
			<?php echo $strSubvoicekey ?>
		</ul>
	</fieldset>
