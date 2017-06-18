<?php
/*******************************************************************************
* Configuration API
*******************************************************************************/

// Agenda Id is know
if(!isset($_GET['agendaLongId']) or empty($_GET['agendaLongId']))
	Console::e('cGAgenda.agendaLongId', "No agenda selected");
else {
	$aLongId = $_GET['agendaLongId'];
	$aList =  Config::getInstance()->GetModuleConfig('breakingnews', 'main')['agendaList'];
	if (!array_key_exists($aLongId, $aList))
		Console::e('cGAgenda', 'Unknows Agenda Id', $aLongId);
	else {
		$aName = $aList[$aLongId];
		$aLongName = "$aName | $aLongId";
		$uGoogle = new UtilsGoogleApi($aLongId, $aName);

		// Step 2
		if(isset($_GET['stepId']) and strcmp($_GET['stepId'],'step2')==0 ) {
		 	if (!isset($_GET['code']) or empty($_GET['code']))
				Console::e('cGAgenda.step2', "Connect code is missing");
			else {
				$code = $_GET['code'];
				try {
					$uGoogle->connect($code);
					Console::i('cGAgenda.step2', "Step 2 success for $aLongName");
				} catch (Exception $e) {
					Console::e('cGAgenda.step2', "Fail to connect $aLongName", $e);
				}
			}
		}

		// Step 3
		if(isset($_GET['stepId']) and strcmp($_GET['stepId'],'step3')==0 ) {
			try {
				$testRes = $uGoogle->test();
				Console::i('cGAgenda.step3', "Step 3 success for $aLongName", $testRes);
			} catch (Exception $e) {
				Console::e('cGAgenda.step3', "Fail to test $aLongName", $e);
			}
		}

		// Step delete
		if(isset($_GET['stepId']) and strcmp($_GET['stepId'],'delete')==0 ) {
			try {
				$testRes = $uGoogle->delete();
				Console::i('cGAgenda.delete', "Step 3 success for $aLongName", $testRes);
			} catch (Exception $e) {
				Console::e('cGAgenda.delete', "Fail to test $aLongName", $e);
			}
		}
	}
}
