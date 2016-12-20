<?php

	require_once('./core/CoreUtils.php');

	$target = 'home';
	$module = null;

	try {
		$coreUtils = new CoreUtils();

		try {
			$coreUtils->consoleD('index', 'Starting');

			if (!empty($_GET)) {
				if (isset($_GET['play'])) {
					$target = 'play';
					$module = $_GET['play'];
				}
				else if (isset($_GET['config'])) {
					$target = 'config';
					$module = $_GET['config'];
				}
				else {
					$coreUtils->consoleD('index', 'Unknow target or module : ' . print_r($_GET, true));
				}
			}
		} catch (Exception $e) {
			$coreUtils->consoleD('index.exception', $e->getMessage() .  print_r($_GET, true), $e);
		}

		include('./core/view/core.php');
	} catch (Exception $e) {
		echo 'Fail to initilise : ' . $e->getMessage() . '<pre>' . $e->getTraceAsString() . '</pre>';
	}
