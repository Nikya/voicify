<!--============================================================================
= Main Play View
=============================================================================-->
<?php
	// Agenda list
	$agendaBeanList = CalandarAccountBean::autoLoadList();
	$agendaUl = '';
	foreach ($agendaBeanList as $aBean)
		$agendaUl .= "<li>{$aBean->getName()}</li>";
?>

<!-- Agendas -->
<fieldset class="form-group">
	<label for="extTemperature">Agendas</label></small>
	<small class="text-muted">
		Agendas announced
		<br/><a href="./?config=breakingnews_cGAgenda">Setup agendas to use</a>
	</small>
	<ul id="subvoicekeylist" class="datadisp">
		<?php echo $agendaUl ?>
	</ul>
</fieldset>

<!-- Weather -->
<fieldset class="form-group">
	<label for="extTemperature">Weather</label></small>
	<small class="text-muted">
		City use for the weather forcast
		<br/><a href="./?config=breakingnews_cMain">Setup city</a>
		<br/><a href="http://www.meteo-france.mobi/home#!ville_synthese_150140" class="external">See the weather map</a>
	</small>
</fieldset>

<!-- Vars -->
<fieldset class="form-group">
	<label for="var0">Free vars</label>
	<small class="text-muted">The variables to be injected into the placeholders <code>{9}</code> of <strong>Free breaking texts</strong>.</small>
	<small class="text-muted">They are optional, their number is unlimited.</small>

	<input type="text" class="form-control" id="var0" name="vars[]" placeholder="{0}" value=""/>
	<input type="text" class="form-control" id="var1" name="vars[]" placeholder="{1}" value=""/>
	<input type="text" class="form-control" id="var2" name="vars[]" placeholder="{2}" value=""/>
	<input type="text" class="form-control" id="var3" name="vars[]" placeholder="{3}" value=""/>
	<input type="text" class="form-control" id="var4" name="vars[]" placeholder="{4}" value=""/>
	<input type="text" class="form-control" id="var5" name="vars[]" placeholder="{5}" value=""/>
</fieldset>
