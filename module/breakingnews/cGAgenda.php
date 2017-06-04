<?php
/*******************************************************************************
* Google Agenda configuration View
*******************************************************************************/
?>

<div class="ccc">
	<h3>Agenda <em>- read</em></h3>
	<div class="content">
		<form id="formGoogleApi" action="api.php?config=breakingnews_cGAgenda" method="get">
			<!-- Agenda Name -->
			<fieldset class="form-group">
				<label for="agendaId">Agenda</label>
				<small class="text-muted">An agenda to autorize and test.</small>
				<select id="agendaId" name="agendaId" required="required">
					<option value=""></option>
				</select>
			</fieldset>

			<!-- Step1 -->
			<fieldset class="form-group">
				<label for="authorize">Step 1 : authorize</label>
				<small class="text-muted">Allow <em>Home Voicify</em> to access your Google calendars</small>
				<input type="button" id="authorize" name="authorize" value="Autoriser"  class="btn"
						onclick="window.open('googleApi.php?authorize=Autoriser'); return false;"
				/>
			</fieldset>

			<!-- Step2 -->
			<fieldset class="form-group">
				<label for="code">Step 2 : connect</label>
				<small class="text-muted">Enter here the code provided by Google in response to the authorization.</small>
				<input type="text" id="code" name="code">
				<input type="submit" id="connect" name="connect" value="Connect" class="btn">
			</fieldset>

			<!-- Step3 -->
			<fieldset class="form-group">
				<label for="test">Step 3 : test</label>
				<small class="text-muted">
					Test a Google Agenda reading. Shows the appointments of the next 10 days.
					Can be restarted if the previous steps (1, 2) have already been done once.
				</small>
				<input type="submit" id="test" name="test" value="Test" class="btn btn-success">
			</fieldset>

			<!-- Reset -->
			<fieldset class="form-group">
				<label for="logout">Logout</label>
				<small class="text-muted">Remove connection informations for Google Agenda.</small>
				<input type="submit" id="logout" name="logout" value="Delete" class="btn btn-danger">
			</fieldset>
		</form>
	</div>
</div>
