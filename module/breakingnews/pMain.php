<!--============================================================================
= Main Play View
=============================================================================-->

<!-- Temperature -->
<fieldset class="form-group">
	<label for="extTemperature">Temperature *</label></small>
	<small class="text-muted">The current outside temperature</small>
	<input type="number" class="form-control" id="extTemperature" name="extTemperature" value="0" min="-99" max="99" required/>
</fieldset>

<!-- Agendas -->
<fieldset class="form-group">
	<label for="extTemperature">Agendas</label></small>
	<small class="text-muted">
		Agendas announced
		<br/><a href="./?config=breakingnews_cGAgenda">Setup agendas to use</a>
	</small>
	<ul id="subvoicekeylist" class="datadisp">
		<li>Agenda 1</li>
		<li>Agenda 2</li>
		<li>Agenda 3</li>
	</ul>
</fieldset>

<!-- Weather -->
<fieldset class="form-group">
	<label for="extTemperature">Weather</label></small>
	<small class="text-muted">
		City use for the weather forcast
		<br/><a href="./?config=breakingnews_cGAgenda">Setup city</a>
		<br/><a href="http://www.meteo-france.mobi/home#!ville_synthese_150140" class="external">See the weather map</a>
	</small>
</fieldset>
