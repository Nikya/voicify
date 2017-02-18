<?php

?>

<div class="row" style="padding:2%;">
	<!-- LEFT -->
	<div class="col-sm-6">
		<!-- CONSOLE -->
		<?php include("./core/view/__console.php"); ?>

		<!-- README -->
		<?php include("./core/view/__readme.php"); ?>
	</div>

	<!-- RIGHT -->
	<div class="col-sm-6">
		<?php echo modulesToHtml() ?>
	</div>
</div>

<?php
/***************************************************************************
* To read modules information and format it to HTML
*/
function modulesToHtml() {
	if (!Setup::isOk())
		return '';

	$out = '';
	$aModules = Config::getInstance()->getManifestMain();

	// To list feature first
	$aModulesFeature = array();
	$aModulesTtsengine = array();
	foreach ($aModules as $mId => $m) {
		if ($m['type']==CoreUtils::MODULE_T_FEATURE)
			$aModulesFeature[$mId] = $m;
		elseif ($m['type']==CoreUtils::MODULE_T_TTSENGINE)
			$aModulesTtsengine[$mId] = $m;
	}
	$aModules = array_merge($aModulesFeature, $aModulesTtsengine);

	// For each modules
	foreach ($aModules as $mId => $m) {
		$out .= "<div class=\"ccc\"><h3>{$m['name']}<em>{$m['type']} - {$m['desc']}</em></h3><div class=\"content moduleDesc\"><ul>";

		// Play
		if (count($m['play']) == 1)
			$out .= "<li><a href=\"?play={$mId}\" title=\"{$m['play']['pMain']['desc']}\">Play {$m['play']['pMain']['name']}</a> - {$m['play']['pMain']['desc']}</li>";
		else
			foreach ($m['play'] as $pId => $p)
				$out .= "<li><a href=\"?play={$mId}_{$pId}\" title=\"{$p['desc']}\">Play {$p['name']}</a> - {$p['desc']}</li>";

		// Config
		if (count($m['config']) == 1)
			$out .= "<li><a href=\"?config={$mId}\" title=\"{$m['config']['cMain']['desc']}\">Config {$m['config']['cMain']['name']}</a> - {$m['config']['cMain']['desc']}</li>";
		else
			foreach ($m['config'] as $cId => $c)
				$out .= "<li><a href=\"?config={$mId}_{$cId}\" title=\"{$c['desc']}\">Config {$c['name']}</a> - {$c['desc']}</li>";

		$out .= "</ul></div></div>";
	}

	return $out;
}
