<?php


/*******************************************************************************
* Common Core utiliy functions
*/
class ViewUtils {

	/** A Bootstrap menu entries séparator */
	const BOOTSTRAP_MENU_SEP = '<li role="separator" class="divider"></li>';

	/** A Bootstrap menu entries séparator */
	const SETUP_MENU_LINK = '<a href="?setup" title="Execute the setup">Setup</a>';

	/***************************************************************************
	* Build the play Menu
	*/
	public static function buildPlayMenu($pageMenu=false) {
		return self::buildMenu(CoreUtils::TARGET_T_PLAY, $pageMenu);
	}

	/***************************************************************************
	* Build the config Menu
	*/
	public static function buildConfigMenu($pageMenu=false) {
		$out = !$pageMenu ? '<li>'.self::SETUP_MENU_LINK.'</li>'.self::BOOTSTRAP_MENU_SEP : '';
		return $out . self::buildMenu(CoreUtils::TARGET_T_CONFIG, $pageMenu);
	}

	/***************************************************************************
	* Build a Menu
	*/
	private static function buildMenu($targetT, $pageMenu) {
		if (!Setup::isOk()) return '';

		$out = '';
		$aManifestFeature = Config::getInstance()->getSubManifestTT_MT($targetT, CoreUtils::MODULE_T_FEATURE);
		$aManifestTtsengine = Config::getInstance()->getSubManifestTT_MT($targetT, CoreUtils::MODULE_T_TTSENGINE);

		if (count($aManifestFeature)>0)
			$out .= self::buildMenuEntries($targetT, $aManifestFeature, $pageMenu);

		if (count($aManifestFeature)>0 and count($aManifestTtsengine)>0)
			if ($pageMenu)
				$out .= '</ul><hr/><ul>';
			else
				$out .= self::BOOTSTRAP_MENU_SEP;

		if (count($aManifestTtsengine)>0)
			$out .= self::buildMenuEntries($targetT, $aManifestTtsengine, $pageMenu);

		return $out;
	}

	/***************************************************************************
	* Build a Menu Entry from a manifest Array
	*/
	private static function buildMenuEntries($targetT, $aManifest, $pageMenu) {
		$out = '';

		$target = $targetT==CoreUtils::TARGET_T_PLAY ? 'play' : 'config';

		foreach ($aManifest as $mId => $m) {
			$d = $pageMenu ? ' - ' . $m['desc'] : '';

			if (count($m[$targetT]) == 1) {
				$out .= "<li><a href=\"?$target=$mId\" title=\"{$m['desc']}\">{$m['name']}</a>$d</li>";
			} else {
				foreach ($m[$targetT] as $eId => $entry) {
					$out .= "<li><a href=\"?$target={$mId}_$eId\" title=\"{$m['desc']} : {$entry['desc']}\">{$m['name']} : {$entry['name']}</a>$d</li>";
				}
			}
		}

		return $out;
	}

	/***************************************************************************
	* Build dropdown select options
	*
	* @param $iArray Indexed Array for key+value
	* @param $selected The value to be selected
	*/
	public static function buildDropdownSelectOpt($iArray, $selected=null) {
		$strOpt = '';

		foreach ($iArray as $k => $v) {
			if(strcmp($k, $selected)==0)
				$strOpt .= "<option value=\"$k\" selected>(Default) $v</option>";
			else
				$strOpt .= "<option value=\"$k\">$v</option>";
		}

		return $strOpt;
	}

	/***************************************************************************
	* Afficher un fichier de configuration au format brut
	*/
	public static function configureFile($module, $submodule='main') {
		$confFilePath = CoreUtils::PATH_CONFIG.$module.'_'.$submodule.'.json';

		try {
			$fContent = print_r(Config::getInstance()->getModuleConfig($module, $submodule), true);
		} catch (Exception $e) {
			$msg = "Fail to read the content of the file '$confFilePath' - {$e->getMEssage()}";
			$fContent = $msg;
			Console::e('ViewUtils.configureFile', $msg, $e);
		}

		return <<<EED
			<div class="ccc">
				<h3>Configuration file <em>- $submodule</em></h3>
				<div class="content">
					<label>Edit the file manually : </label>
					<code>$confFilePath</code>
					<br/>
					<br/>
					<label>File content</label>
					<pre>$fContent</pre>
				</div>
			</div>
EED;
	}
}
