<?php


/*******************************************************************************
* Common Core utiliy functions
*/
class ViewUtils {

	/***************************************************************************
	* Build a Menu
	*/
	public static function buildMenu($targetT, $moduleT) {
		if (!Setup::isOk()) return '';

		$subManifest = Config::getInstance()->getSubManifestTT_MT($targetT, $moduleT);
		$out = '';

		$target = $targetT==CoreUtils::TARGET_T_PLAY ? 'play' : 'config';

		foreach ($subManifest as $mId => $m) {
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
	* Build a Menu separator
	*/
	public static function buildMenuSep() {
		return '<li role="separator" class="divider"></li>';
	}

	/***************************************************************************
	* Build dropdown select options
	*/
	public static function buildDropdownSelectOpt($iArray, $selected=null) {
		$strOpt = '<option value="" selected>(Default)</option>';

		foreach ($iArray as $k => $v) {
			$strOpt .= "<option value=\"$k\">$v</option>";
		}

		return $strOpt;
	}

	/***************************************************************************
	* Afficher un fichier de configuration au format brut
	*/
	public static function displayConfFile($confFilePath) {
		$out = "<p>Edit manualy this file :</p> <pre><strong>$confFilePath</strong><hr/>";

		try {
			$c = print_r(JsonUtils::jFile2Array($confFilePath), true);
			$out .= $c;
		} catch (Exception $e) {
			Console::i('ViewUtils::displayConfFile', "Fail to read the file $confFilePath", $e);
			$out .= 'Invalid Json file ! ' . $e->getMessage();
		}

		return $out . "</pre>";
	}
}
