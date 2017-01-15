<?php
	require_once('./core/CoreUtils.php');
	require_once('./core/ViewUtils.php');

	try {
		/***********************************************************************
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

		/***********************************************************************
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
* DISPLAY
*/

	//$playMenuHtml = buildPlayMenu();
	//$configMenuHtml = buildConfigMenu();

	include('./core/view/main.php');
