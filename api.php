<?php
	// Affichage des erreurs PHP
	error_reporting(E_ALL);
	ini_set('display_errors', 'On');

	// Redirection du système de remonté d'erreur
	set_error_handler('myError');
	set_exception_handler('myErrorException');

	// Return only Json
	header('Content-type: application/json; charset=utf-8');

	////////////////////////////////////////////////////////////////////////////
	// Controleur
	if (!isset($_GET['action']) or empty($_GET['action'])) {
		throw new Exception("No action to prosess");
	} else {
		require_once('core/loader.php');
		$action = $_GET['action'];

		switch ($action) {
			case "get_voicekey":
				getVoicekey();
				break;
			case "post_voicekey":
				postVoicekey();
				break;
			default:
				throw new Exception("Unknow action to prosess '$action'");
		}
	}

	////////////////////////////////////////////////////////////////////////////
	// Controleur action Get Voicekey au format Json
	function getVoicekey() {
		$textCollection = TextCollection::getInstance();
		echo $textCollection->getAllVoicekeyToJson();
	}

	////////////////////////////////////////////////////////////////////////////
	// Controleur action post Voicekey au format Json
	function postVoicekey() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$dataStr = file_get_contents("php://input");
			$textCollection = TextCollection::getInstance();
			echo $textCollection->setAllVoicekeyFromJsonStr($dataStr);

			$a = array(
				"success" => true,
				"msg" => "ok"
			);
			echo json_encode($a);
		} else
			throw new Exception("Is not a POST method");
	}

	////////////////////////////////////////////////////////////////////////////
	// Error
	function myError($level, $message, $file, $line, $context) {
		if (error_reporting() === 0)
			return;

		$fMessage = "#$level line $line of $file. $message";

		$a = array(
			"success" => false,
			"error" => $fMessage
		);
		echo json_encode($a);

		die();
	}

	////////////////////////////////////////////////////////////////////////////
	// Exeption
	function myErrorException($e) {
		// Redirect to the error Handler
		myError(
			18000,
			$e->getMessage(),
			$e->getFile(),
			$e->getLine(),
			$e->getTrace()
		);
	}
