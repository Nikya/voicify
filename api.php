<?php

// The output return
$output = array(
	'status'=>null,
	'say'=>null,
	'console'=>null
);

try {
	require_once('./core/coreLoader.php');

/*******************************************************************************
* Check the TARGET
*/
	// Mandatory vars
	$target = CoreUtils::TARGET_T_HOME;
	$fullModule = null;
	$module = null;
	$subModule = null;
	$say = null;

	Console::setDebug(isset($_GET['debugMode']));
	Console::d('Debug status', Console::isDebug() ? 'enable' : 'disable');

	if (!Setup::isOk())
		throw new Exception('Please run the Setup in config/setup Menu');

	if (!empty($_GET)) {
		if (isset($_GET[CoreUtils::TARGET_T_PLAY])) {
			$target = CoreUtils::TARGET_T_PLAY;
		}
		else if (isset($_GET[CoreUtils::TARGET_T_CONFIG])) {
			$target = CoreUtils::TARGET_T_CONFIG;
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
	if (!Config::getInstance()->isValidModule($target, $module, $subModule))
		throw new Exception('Unknow module or submodule');

	if (!Setup::isOk()) {
		throw new Exception('Please run the Setup in config/setup Menu');
	}

/*******************************************************************************
* Run API
*/
	include("./core/{$target}API.php");

	Console::getInstance()->toLogFile();

/*******************************************************************************
* Global Catch
*/
} catch (Exception $e) {
	$say = '?';
	Console::w('api', 'target' , $_GET);
	Console::w('api', 'module' , $module);
	Console::w('api', 'subModule' , $subModule);
	Console::e('api', 'Exeption', $e);
	http_response_code (400);
}


/*******************************************************************************
* Respond with Json
*/
$indicator = Console::getInstance()->indicator();

header('Content-type: application/json; charset=utf-8');
if ($indicator != 'ok') http_response_code (400);

$output['status'] = $indicator;
$output['say'] = $say;
$output['console'] = Console::getInstance()->getArrayConsole();

echo json_encode($output, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
