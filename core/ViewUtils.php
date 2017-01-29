<?php


/*******************************************************************************
* Common Core utiliy functions
*/
class ViewUtils {

	/***************************************************************************
	* Build the play Menu
	*/
	public static function buildPlayMenu() {
		$manifestMain = CoreUtils::getManifestMain();

		$menu = '';
		foreach ($manifestMain['FEATURE'] as $id => $manifest) {
			foreach ($manifest['play'] as $playName) {
				$playId = $id . ($playName != 'play' ? "_$playName" : '');
				$menu .= <<<EOLI
					<li><a href="?play=$playId" title="{$manifest['desc']}">{$manifest['name']} <em>$playId</em></a></li>
EOLI;
			}
		}

		$menu .= '<li role="separator" class="divider"></li>';

		foreach ($manifestMain['TTSENGINE'] as $id => $manifest) {
			foreach ($manifest['play'] as $playName) {
				$playId = $id . ($playName != 'play' ? "_$playName" : '');
				$menu .= <<<EOLI
					<li><a href="?play=$playId" title="{$manifest['desc']}">{$manifest['name']} <em>$playId</em></a></li>
EOLI;
			}
		}

		return $menu;
	}

	/*******************************************************************************
	* Build the play Menu
	*/
	function buildConfigMenu() {
		$manifestMain = CoreUtils::getManifestMain();

		$menu = '<li><a href="?config=setup" title="Recharger tous les modules">Recharger <em>setup</em></a></li><li role="separator" class="divider"></li>';
		foreach ($manifestMain['FEATURE'] as $id => $manifest) {
			foreach ($manifest['config'] as $configName) {
				$configId = $id . ($configName != 'config' ? "_$configName" : '');
				$menu .= <<<EOLI
					<li><a href="?config=$configId" title="{$manifest['desc']}">{$manifest['name']} <em>$configId</em></a></li>
EOLI;
			}
		}

		$menu .= '<li role="separator" class="divider"></li>';

		foreach ($manifestMain['TTSENGINE'] as $id => $manifest) {
			foreach ($manifest['config'] as $configName) {
				$configId = $id . ($configName != 'config' ? "_$configName" : '');
				$menu .= <<<EOLI
					<li><a href="?config=$configId" title="{$manifest['desc']}">{$manifest['name']} <em>$configId</em></a></li>
EOLI;
			}
		}

		return $menu;
	}

	/*******************************************************************************
	* Build the play Menu
	*/
}
