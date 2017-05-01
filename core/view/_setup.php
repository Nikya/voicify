<?php

?>
<div class="ccc">
	<h3>Setup</h3>
	<div class="content">
		<form action="." >
			<input type="hidden" name="setup" value="run"/>
			<input type="submit" value="Run setup" class="btn btn-default navbar-btn btn-success" title="Execute the setup"/>
		</form>
	</div>
</div>

<?php if (Setup::isOk()) { $aHtml = modulesToHtml(); ?>

	<div class="ccc">
		<h3>Configure this</h3>
		<div class="content">
			<ul>
				<?php echo ViewUtils::buildConfigMenu(true); ?>
			</ul>
		</div>
	</div>

	<div class="row" >
		<!-- LEFT - README -->
		<div class="col-sm-6">

	<div class="ccc">
		<h3>Feature Modules</h3>
		<div class="content markdown-body moduleDesc">
			<?php echo $aHtml[CoreUtils::MODULE_T_FEATURE]; ?>
		</div>
	</div>
</div>

		<div class="col-sm-6">
	<div class="ccc">
		<h3>TTS Engine Modules</h3>
		<div class="content markdown-body moduleDesc">
			<?php echo $aHtml[CoreUtils::MODULE_T_TTSENGINE]; ?>
		</div>
	</div>
	</div>
</div>
<?php } ?>


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
