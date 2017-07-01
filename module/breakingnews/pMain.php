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

<!-- Temperature -->
<fieldset class="form-group">
	<label for="extTemperature">Temperature *</label></small>
	<small class="text-muted">The current outside temperature</small>
	<input type="number" class="form-control" id="extTemperature" name="extTemperature" value="80" min="-99" max="99" required/>
</fieldset>

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
