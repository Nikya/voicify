<?php

/*******************************************************************************
* Utils and common functionality for the current module
*/
class Utils {

	private $timestamp;

	/***************************************************************************
	* Constructor
	*/
	public function __construct($timestamp=null) {
		if ($timestamp!=null)
			$this->timestamp = $timestamp;
		else
			$this->timestamp = time()+120;
	}

	/***************************************************************************
	* Build vars
	*/
	public function varToLis() {
		$h = date("H",$this->timestamp);
		$i=0;
		$hi=$i;
		$genVars = array ();

		// 0
		$genVars[$i] = array(
			'val'=>$h,
			'desc' => 'Current hour'
		);

		// 1...12 => +1...+12
		for($i=1;$i<12; $i++) {
			$hi=$i;
			$h = date("H",$this->timestamp+(3600*$hi));
			$genVars[$i] = array(
				'val'=>$h+$i,
				'desc' => "Current hour+".$hi
			);
		}

		// +12
		$hi=$i=12;
		$h = date("H",$this->timestamp+(3600*$hi));
		$genVars[$i] = array(
			'val'=>$h+$i,
			'desc' => "Current hour+-".$hi
		);

		// 13...24 => -12...0
		for($i=$i+1;$i<24; $i++) {
			$hi=24-$i;
			$h = date("H",$this->timestamp-(3600*$i));
			$genVars[$i] = array(
				'val'=>$h+$i,
				'desc' => "Current hour-".$hi
			);
		}

		// +24
		$hi=$i=24;
		$h = date("H",$this->timestamp+(3600*$hi));
		$genVars[$i] = array(
			'val'=>$h+$i,
			'desc' => "Current hour"
		);

		// TS
		$i = 25;
		$genVars[$i] = array(
			'val'=>$this->timestamp,
			'desc' => "Current timestamp"
		);

		// vars list
		$strVars = '';
		foreach ($genVars as $i => $var) {
			$strVars .= "<li><code>&lbrace;$i&rbrace;</code>={$var['val']} : {$var['desc']}</li>";
		}

		return $strVars;
	}

	/***************************************************************************
	* Build full current date
	*/
	public function timestampToStr() {
		return MessageFormatter::formatMessage('fr_FR', ' {0,date,FULL} {0,time,MEDIUM}', array($this->timestamp));
	}

	/***************************************************************************
	* Build all text list
	*/
	public function textOptions() {
		$strTrgTxt = '';
		$conf = Config::getInstance();
		$cHour = $conf->getModuleConfig('speakingclock', 'hour');
		$cNeutral = $conf->getModuleConfig('speakingclock', 'neutral');

		foreach ($cHour as $hId => $hTxts) {
			$strTrgTxt .= '<optgroup label="'.$hId.'">';
			for ($i=0; $i<count($hTxts); $i++) {
				$hTxt = $hTxts[$i];
				$strTrgTxt .= "<option value=\"$hId.$i\">{$hTxt['text']}</option>";
			}
		}
		$strTrgTxt .= '<optgroup label="Neutral">';
		for ($i=0; $i<count($cNeutral); $i++) {
			$nTxt = $cNeutral[$i];
			$strTrgTxt .= "<option value=\"n.$i\">{$nTxt['text']}</option>";
		}

		return $strTrgTxt;
	}
}
