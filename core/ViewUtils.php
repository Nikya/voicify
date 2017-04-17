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
		$strOpt = '';

		foreach ($iArray as $k => $v) {
			if (strcmp($selected, $k)==0) {
				$strOpt .= "<option value=\"$k\" selected>$v (Default)</option>";
				$selected = true;
			}
			else
				$strOpt .= "<option value=\"$k\">$v</option>";
		}

		if ($selected!==null and $selected!==true)
			$strOpt = "<option></option>" . $strOpt;

		return $strOpt;
	}
}
