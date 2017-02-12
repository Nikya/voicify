<?php
	// Affichage des erreurs PHP
	error_reporting(E_ALL);
	ini_set('display_errors', 'On');

	// Redirection du système de remonté d'erreur
	set_error_handler('respondError');
	set_exception_handler('redirectException');

	// Return only Json
	header('Content-type: application/json; charset=utf-8');

	////////////////////////////////////////////////////////////////////////////
	// Controleur
	if (!isset($_GET['action']) or empty($_GET['action'])) {
		throw new Exception("No action to prosess");
	} else {
		require_once('core/init.php');
		require_once('core/playApi.php');
		require_once('core/wordingApi.php');
		require_once('core/configApi.php');
		require_once('core/Voicify.php');
		require_once('core/VoicifyClock.php');

		$action = $_GET['action'];

		$apiRes = null;

		switch ($action) {
			case "get_voicekey":
				$apiRes = array('list' => getVoicekeyfull());
				break;
			case "post_voicekey":
				postVoicekeyJson();
				break;
			case "get_config_prefixList":
				$apiRes = array('list' => getPrefixList());
				break;
			case "play_voicekey":
				$apiRes = playVoicekey();
				break;
			case "play_clock":
				$apiRes = playClock();
				break;
			default:
				throw new Exception("Unknow action to prosess '$action'");
		}

		respond($apiRes);
	}

	////////////////////////////////////////////////////////////////////////////
	// Normal response
	function respond($resArray) {
		$preArray = array(
			'success' => true,
			'msg' => 'ok'
		);

		if ($resArray != null)
			echo json_encode(array_merge($preArray, $resArray));
		else
			echo json_encode($preArray);
	}

	////////////////////////////////////////////////////////////////////////////
	// Error response
	function respondError($level, $message, $file, $line, $context) {
		if (error_reporting() === 0)
			return;

		$fMessage = "#$level line $line of $file. $message";

		$a = array(
			"success" => false,
			"msg" => $fMessage
		);
		echo json_encode($a);

		die();
	}

	////////////////////////////////////////////////////////////////////////////
	// Exeption
	function redirectException($e) {
		// Redirect to the error Handler
		respondError(
			18000,
			$e->getMessage(),
			$e->getFile(),
			$e->getLine(),
			$e->getTrace()
		);
	}
