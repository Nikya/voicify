<div class="row" style="padding:2%;">
	<!-- LEFT -->
	<div class="col-sm-6">
		<!-- CONSOLE -->
		<?php include("./core/view/__console.php"); ?>
	</div>

	<!-- RIGHT -->
	<div class="col-sm-6">
		<div class="ccc">
			<h3>Setup</h3>
			<div class="content">
				<p>To load or refresh all available <em>Modules</em>.
				<form action="." >
					<input type="hidden" name="setup" value="run"/>
					<input type="submit" value="Run setup"/>
				</form>
			</div>
		</div>

		<?php if (Setup::isOk()) { ?>
		<div class="ccc">
			<h3>Feature Modules</h3>
			<div class="content markdown-body moduleDesc">
				<?php printModule(CoreUtils::MODULE_T_FEATURE); ?>
			</div>
		</div>

		<div class="ccc">
			<h3>TTS Engine Modules</h3>
			<div class="content markdown-body moduleDesc">
				<?php printModule(CoreUtils::MODULE_T_TTSENGINE); ?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>


<?php
/***************************************************************************
* To read and display all modules from a type
*/
function printModule($moduleT) {
	$aModules = CoreUtils::getManifestMain()[$moduleT];

	foreach ($aModules as $mId => $m) {
		echo <<<EOM
			<strong>{$m['name']}</strong>
			<br/>
			<em>{$m['desc']}</em>
			<ul>
				<li>Id : $mId</li>
				<li>Version : {$m['version']}</li>
				<li>Author : {$m['author']}</li>
				<li><a href={$m['sourcelink']} >Origin</a></li>
			</ul>
			<hr/>
EOM;
	}
}
