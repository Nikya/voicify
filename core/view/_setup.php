<?php

?>

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

		<?php if (Setup::isOk()) { $aHtml = modulesToHtml(); ?>
			<div class="ccc">
				<h3>Feature Modules</h3>
				<div class="content markdown-body moduleDesc">
					<?php echo $aHtml[CoreUtils::MODULE_T_FEATURE]; ?>
				</div>
			</div>

			<div class="ccc">
				<h3>TTS Engine Modules</h3>
				<div class="content markdown-body moduleDesc">
					<?php echo $aHtml[CoreUtils::MODULE_T_TTSENGINE]; ?>
				</div>
			</div>
		<?php } ?>
	</div>
</div>


<?php
/***************************************************************************
* To read modules information and format it to HTML
*/
function modulesToHtml() {
	$aHtml = array(
		CoreUtils::MODULE_T_TTSENGINE => '',
		CoreUtils::MODULE_T_FEATURE => ''
	);

	$aModules = Config::getInstance()->getManifestMain();
	foreach ($aModules as $mId => $m) {
		$aHtml[$m['type']] .= <<<EOM
			<strong>{$m['name']}</strong>
			<br/>
			<em>{$m['desc']}</em>
			<ul>
				<li>Id : $mId</li>
				<li>Version : {$m['version']}</li>
				<li>Author : {$m['author']}</li>
				<li>Origin : <a href={$m['sourcelink']} >{$m['sourcelink']}</a></li>
			</ul>
			<br/>
EOM;
	}

	return $aHtml;
}
