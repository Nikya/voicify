<?php
	require_once('./core/CoreUtils.php');

	try {

		// Run Setup if needed
		if (!Setup::check()) {
			CoreUtils::consoleW('CoreUtils.constructor', 'Need to execute Setup');
			Setup::exec();
		}

		try {
			// Define target
			$target = 'home';
			$module = null;
			$title = 'Home Voicify';
			$desc = "Welcome";
			$readme = null;

			CoreUtils::consoleD('index', 'Starting. $title', array($target, $module));

			if (!empty($_GET)) {
				if (isset($_GET['play'])) {
					$target = 'play';
					$module = $_GET['play'];

					$title = 'Home Voicify';
					$desc = "Welcome";
					$readme = null;
				}
				else if (isset($_GET['config'])) {
					$target = 'config';
					$module = $_GET['config'];
				}
				else {
					CoreUtils::consoleW('index', 'Unknow target or module : ' . print_r($_GET, true));
				}
			}

		} catch (Exception $e) {
			CoreUtils::consoleD('index.exception', $e->getMessage() .  print_r($_GET, true), $e);
		}

		include('./core/view/main.php');
	} catch (Exception $e) {
		echo 'Fail to initilise : ' . $e->getMessage() . '<pre>' . $e->getTraceAsString() . '</pre>';
	}
