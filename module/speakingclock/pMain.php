<!--============================================================================
= Main Play View
=============================================================================-->
<?php
	$u = new Utils();
	$fDate = $u->getTimestampToStr();
	$strVars = $u->varsToList();
	$strTrgTxt = $u->textOptions();
?>

	<!-- Automatic -->
	<fieldset class="form-group">
		<label for="cts">Automatic</label>
		<small class="text-muted">By default, automatic operation: Simply call this functionality without additional parameters for the current hour to be announced.</small>
		<input type="text" class="form-control" id="cts" value="<?php echo $fDate ?>" disabled/>
		<br/>
		<small class="text-muted"><a href="./?config=speakingclock_cHour">Edit hour</a> or <a href="./?config=speakingclock_cNeutral">edit neutral</a></small>
	</fieldset>

	<!-- Vars -->
	<fieldset class="form-group">
		<label for="hourvars">Vars</label>
		<small class="text-muted">The variable are self-generated. They can be use into a placeholders <code>{9}</code> in the text to speak.</small>
		<ul id="hourvars" class="datadisp">
			<?php echo $strVars ?>
		</ul>
	</fieldset>

	<!-- Test trigger -->
	<fieldset class="form-group">
		<label for="trgHour">Test trigger</label>
		<small class="text-muted">Only a test : select a text and an hour to test.</small>
		<input type="number" class="form-control" id="trgHour" name="trgHour" placeholder="hour" step="1" value="" min="0" max="24">
		<select class="form-control" id="trgTxt" name="trgTxt">
			<option></option>
			<?php echo $strTrgTxt; ?>
		</select>
	</fieldset>
