<?php
	require_once('./core/CoreUtils.php');
	require_once('./core/ViewUtils.php');

	try {
/*******************************************************************************
* Read target
*/

		// Mandatory vars
		$target = 'home';
		$module = null;
		$title = 'Home';
		$desc = 'Welcome';
		$readme = null;

		if (!empty($_GET)) {
			if (isset($_GET['play'])) {
				$target = 'play';
				$module = $_GET['play'];
			}
			else if (isset($_GET['config'])) {
				$target = 'config';
				$module = $_GET['config'];
			}
			else if (isset($_GET['setup'])) {
				$target = 'setup';
				$module = $_GET['setup'];
			}
			else {
				CoreUtils::consoleW('index', 'Unknow target or module : ' . print_r($_GET, true));
			}
		}

/*******************************************************************************
* Load target
*/
		switch ($target) {
			/** PLAY ***********************************************************/
			case 'play':
				// TODO impl Play
				break;

			/** CONFIG *********************************************************/
			case 'config':
				// TODO impl Config
				break;

			/** SETUP *********************************************************/
			case 'setup':
				if (!empty($module) and strcasecmp($module, 'run')==0)
					Setup::exec();
				$title = 'Setup';
				$desc = 'Check and initialize the system';
				break;

			case 'home':
			default:
				// TODO impl home normal et fail et SEtup
				break;
		}
	} catch (Exception $e) {
		CoreUtils::consoleW('index', 'target' , $_GET);
		CoreUtils::consoleE('index.exception', $e->getMessage(), $e);
	}

	if (!Setup::isOk()) {
		CoreUtils::consoleE('CoreUtils.constructor', 'Please run the Setup in config/setup Menu');
	}

/*******************************************************************************
* Build Menus
*/
	$playMenuHtml = '';
	$playMenuHtml .= buildMenu(CoreUtils::MENU_T_PLAYER, CoreUtils::MODULE_T_FEATURE);
	$playMenuHtml .= buildMenuSep();
	$playMenuHtml .= buildMenu(CoreUtils::MENU_T_PLAYER, CoreUtils::MODULE_T_TTSENGINE);

	$configMenuHtml = '<li><a href="?setup" title="Execute the setup">Setup</a></li><li role="separator" class="divider"></li>';
	$configMenuHtml .= buildMenu(CoreUtils::MENU_T_CONFIGURATOR, CoreUtils::MODULE_T_FEATURE);
	$configMenuHtml .= buildMenuSep();
	$configMenuHtml .= buildMenu(CoreUtils::MENU_T_CONFIGURATOR, CoreUtils::MODULE_T_TTSENGINE);

	function buildMenu($menuT, $moduleT) {
		if (!Setup::isOk()) return '';
		
		$aModules = CoreUtils::getManifestMain()[$moduleT];
		$out = '';

		$target = $menuT==CoreUtils::MENU_T_PLAYER ? 'play' : 'config';

		foreach ($aModules as $mId => $m) {
			if (count($m[$menuT]) == 1) {
				$out .= "<li><a href=\"?$target=$mId\" title=\"{$m['desc']}\">{$m['name']}</a></li>";
			} else {
				foreach ($m[$menuT] as $eId => $entry) {
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
* DISPLAY
*/
	include('./core/view/main.php');
