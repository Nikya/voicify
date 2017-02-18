<?php
	require_once('./core/coreLoader.php');
	require_once('./core/ViewUtils.php');

	try {
/*******************************************************************************
* Check the TARGET
*/
		// Mandatory vars
		$target = CoreUtils::TARGET_T_HOME;
		$fullModule = null;
		$module = null;
		$subModule = null;
		$title = 'Home';
		$desc = 'Welcome';
		$subTitle = null;
		$subDesc = null;
		$readmeHtml = null;

		// DEBUG mode
		Console::setDebug(true);
		Console::d('Debug status', Console::isDebug() ? 'enable' : 'disable');

		if (!empty($_GET)) {
			if (isset($_GET[CoreUtils::TARGET_T_PLAY])) {
				$target = CoreUtils::TARGET_T_PLAY;
			}
			else if (isset($_GET[CoreUtils::TARGET_T_CONFIG])) {
				$target = CoreUtils::TARGET_T_CONFIG;
			}
			else if (isset($_GET[CoreUtils::TARGET_T_SETUP])) {
				$target = CoreUtils::TARGET_T_SETUP;
			}
			else {
				throw new Exception('Unknow target');
			}

			$fullModule = $_GET[$target];
			$exTargetV = explode('_', $fullModule);
			$module = $exTargetV[0];
			$subModule = count($exTargetV) > 1 ? $exTargetV[1] : substr($target, 0, 1) . 'Main';
		}

/*******************************************************************************
* Check the MODULE + SUB MODULE
*/

		switch ($target) {
			case CoreUtils::TARGET_T_PLAY:
			case CoreUtils::TARGET_T_CONFIG:
				if (!Config::getInstance()->isValidModule($target, $module, $subModule))
					throw new Exception('Unknow module or submodule');
				else {
					$manifest = Config::getInstance()->getModuleManifest($module);
					$title = $manifest['name'];
					$desc = $manifest['desc'];
					$subTitle = $manifest[$target][$subModule]['name'];
					$subDesc =  $manifest[$target][$subModule]['desc'];
					$readmeHtml = CoreUtils::getModuleReadme($module);
				}
				break;

			// SETUP
			case 'setup':
				if (!empty($module) and strcasecmp($module, 'run')==0) {
					try {
						Setup::exec();
					} catch (Exception $e) {
						Console::e('Setup fail', $e->getMessage(), $e);
					}
				}
				$title = 'Setup';
				$desc = 'Check and initialize the system';
				break;

			case 'home':
			default:
				$readmeHtml = CoreUtils::mdParse('README.md');
				break;
		}
	} catch (Exception $e) {
		Console::w('index.target', 'target' , $_GET);
		Console::w('index.module', 'module' , $module);
		Console::w('index.subModule', 'subModule' , $subModule);
		Console::e('index.exception', $e->getMessage(), $e);
		$target = CoreUtils::TARGET_T_HOME;
	}

	if (!Setup::isOk()) {
		Console::e('index.setup', 'Please run the Setup. Go to the menu config>>setup');
	}

/*******************************************************************************
* Build Menus
*/
	$playMenuHtml = '';
	$playMenuHtml .= buildMenu(CoreUtils::TARGET_T_PLAY, CoreUtils::MODULE_T_FEATURE);
	$playMenuHtml .= buildMenuSep();
	$playMenuHtml .= buildMenu(CoreUtils::TARGET_T_PLAY, CoreUtils::MODULE_T_TTSENGINE);

	$configMenuHtml = '<li><a href="?setup" title="Execute the setup">Setup</a></li><li role="separator" class="divider"></li>';
	$configMenuHtml .= buildMenu(CoreUtils::TARGET_T_CONFIG, CoreUtils::MODULE_T_FEATURE);
	$configMenuHtml .= buildMenuSep();
	$configMenuHtml .= buildMenu(CoreUtils::TARGET_T_CONFIG, CoreUtils::MODULE_T_TTSENGINE);

	function buildMenu($targetT, $moduleT) {
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

	function buildMenuSep() {
		return '<li role="separator" class="divider"></li>';
	}

/*******************************************************************************
* Build API URL
*/
	$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$exUrl = explode('?', $fullUrl);

	if (count($exUrl) > 1)
		$baseUrl = $exUrl[0];
	else
		$baseUrl = $fullUrl;

	$baseApiUrl = "{$baseUrl}api.php?$target=$fullModule";

/*******************************************************************************
* DISPLAY
*/
	include('./core/view/main.php');
