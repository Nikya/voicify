<?php


/*******************************************************************************
* Common Core utiliy functions
*/
class ViewUtils {

	/** A Bootstrap menu entries séparator */
	const BOOTSTRAP_MENU_SEP = '<li role="separator" class="divider"></li>';

	/** Special menu entries */
	const SETUP_MENU_SETUP = '<a href="?setup" title="Execute the setup">Setup</a>';
	const SETUP_MENU_LOG = '<a href="?log" title="Read log files">Log</a>';

	/***************************************************************************
	* Build the play Menu
	*/
	public static function buildPlayMenu($inPageMenu=false) {
		return self::buildMenu(CoreUtils::TARGET_T_PLAY, $inPageMenu);
	}

	/***************************************************************************
	* Build the config Menu
	*/
	public static function buildConfigMenu($inPageMenu=false) {
		$out = '';
		if (!$inPageMenu) {
			$out = '<li>'.self::SETUP_MENU_SETUP.'</li>';
			$out .= '<li>'.self::SETUP_MENU_LOG.'</li>';
			$out .= self::BOOTSTRAP_MENU_SEP;
		}

		return $out . self::buildMenu(CoreUtils::TARGET_T_CONFIG, $inPageMenu);
	}

	/***************************************************************************
	* Build a Menu
	*/
	private static function buildMenu($targetT, $inPageMenu) {
		if (!Setup::isOk()) return '';

		$out = '';
		$aManifestFeature = Config::getInstance()->getSubManifestTT_MT($targetT, CoreUtils::MODULE_T_FEATURE);
		$aManifestTtsengine = Config::getInstance()->getSubManifestTT_MT($targetT, CoreUtils::MODULE_T_TTSENGINE);

		if (count($aManifestFeature)>0)
			$out .= self::buildMenuEntries($targetT, $aManifestFeature, $inPageMenu);

		if (count($aManifestFeature)>0 and count($aManifestTtsengine)>0)
			if ($inPageMenu)
				$out .= '</ul><hr/><ul>';
			else
				$out .= self::BOOTSTRAP_MENU_SEP;

		if (count($aManifestTtsengine)>0)
			$out .= self::buildMenuEntries($targetT, $aManifestTtsengine, $inPageMenu);

		return $out;
	}

	/***************************************************************************
	* Build a Menu Entry from a manifest Array
	*/
	private static function buildMenuEntries($targetT, $aManifest, $inPageMenu) {
		$out = '';

		$target = $targetT==CoreUtils::TARGET_T_PLAY ? 'play' : 'config';

		foreach ($aManifest as $mId => $m) {
			if (count($m[$targetT]) == 1) {
				$out .= "<li><a href=\"?$target=$mId\" title=\"{$m['desc']}\">{$m['name']}</a></li>";
			} else {
				foreach ($m[$targetT] as $eId => $entry) {
					$out .= "<li><a href=\"?$target={$mId}_$eId\" title=\"{$m['desc']} : {$entry['desc']}\">{$m['name']} : {$entry['name']}</a></li>";
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
			$fContent = json_encode(Config::getInstance()->getModuleConfig($module, $submodule), JSON_PRETTY_PRINT);
		} catch (Exception $e) {
			$msg = "/!\ Fail to read the content of the file '$confFilePath' !";
			$fContent = $msg;
			Console::e('ViewUtils.configureFile', $msg, $e);
		}

		return <<<EED
					<label>Edit the file manually : </label>
					<code>$confFilePath</code>
					<br/>
					<br/>
					<label>File content</label>
					<textarea style="width:100%; height:32em; font-family:monospace">$fContent</textarea>
EED;
	}
}
