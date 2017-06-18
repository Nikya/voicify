<?php
/*******************************************************************************
* Google Agenda configuration View
*******************************************************************************/

	// Autorize URL
	$aURL = UtilsGoogleApi::getAuthorizeUrl();

	// Agenda list
	$agendaList = Config::getInstance()->getModuleConfig('breakingnews', 'main')['agendaList'];
	$agendaOpt = '';
	foreach ($agendaList as $aId => $aName) {
		$exAId = UtilsGoogleApi::explodeALongId($aId);
		$agendaOpt .= "<option value=\"$aId\">{$aName} | $exAId[0] | $exAId[1]</option>";
	}
?>

<!-- Agenda Name -->
<fieldset class="form-group">
	<label for="agendaId">Agenda</label>
	<small class="text-muted">An agenda to autorize and test.</small>
	<select id="agendaLongId" name="agendaLongId" required="required" class="form-control" >
		<!--option></option-->
		<?php echo $agendaOpt ?>
	</select>
</fieldset>

<!-- Step1 -->
<fieldset class="form-group">
	<label for="authorize">Step 1 : authorize</label>
	<small class="text-muted">Allow <em>Home Voicify</em> to access your Google calendars</small>
	<input type="button" id="authorize" name="authorize" value="Autoriser" class="btn" onclick="
		window.open('<?php echo $aURL ?>'); return false; "
	/>
</fieldset>

<!-- Step2 -->
<fieldset class="form-group">
	<label for="code">Step 2 : connect</label>
	<small class="text-muted">Enter here the code provided by Google in response to the authorization.</small>
	<input type="text" id="code" name="code" class="form-control" >
	<input type="submit" id="connect" name="connect" value="Connect" class="btn" onclick="$('#stepId').val('step2'); ">
</fieldset>

<!-- Step3 -->
<fieldset class="form-group">
	<label for="test">Step 3 : test</label>
	<small class="text-muted">
		Test a Google Agenda reading. Shows the appointments of the next 10 days.
		Can be restarted if the previous steps (1, 2) have already been done once.
	</small>
	<input type="submit" id="test" name="test" value="Test" class="btn btn-success" onclick="$('#stepId').val('step3'); ">
</fieldset>

<!-- delete -->
<fieldset class="form-group">
	<label for="logout">Logout</label>
	<small class="text-muted">Remove connection informations from Google Agenda.</small>
	<input type="submit" id="delete" name="delete" value="Delete" class="btn btn-danger" onclick="$('#stepId').val('delete'); ">
</fieldset>

<input type="hidden" id="stepId" name="stepId" value="" />
