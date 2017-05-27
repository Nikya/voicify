<?php

/*******************************************************************************
* Utils and common functionality for the current module
*/
class Utils {

	// The target hour to play with
	private $hour;

	// The target timestamp to play with
	private $timestamp;

	// All other data devived from the target hour
	private $vars = null;

	// All other data devived from the target hour
	private $varsTexted = null;

	/** To catch up with a slight time lag (in seconde) */
	const SHIFTDELAY = 199;

	/***************************************************************************
	* Constructor
	*/
	public function __construct($hour=null) {
		$this->timestamp = time()+SELF::SHIFTDELAY;

		if ($hour!=null)
			$this->hour = intval($hour);
		else
			$this->hour = intval(date("H", $this->timestamp));

		$this->genVars();
	}

	/***************************************************************************
	* Generate all vars for the current hour
	*/
	public function genVars() {
		$h = $this->hour;

		// Raw vars
		$gVars = array();
		$i = 0;
		$gVars[$i] = $h;
		$i=1;
		for(; $h+$i<24; $i++)
			$gVars[$i] = $h+$i;
		for(; $i<24; $i++)
			$gVars[$i] = $h-24+$i;

		// Texted vars
		$gVarsT = array();
		for ($i=0; $i<24; $i++) {
			$val = $gVars[$i];
			if($i==0)
				$desc = "Current hour";
			else if ($i==12)
				$desc = "Current hour±12";
			else if ($i<12)
				$desc = "Current hour+".$i;
			else if ($i>12)
				$desc = "Current hour-".(24-$i);
			$gVarsT[$i] = array('val' => $val, 'desc' => $desc);
		}

		// Special
		$i=24;
		$gVars[$i] = $h <= 12 ? $h : $h-12;
		$gVarsT[$i] = array('val' => $gVars[$i] , 'desc' => 'Current hour (12h format)');
		$i=25;
		$gVars[$i] = $this->timestamp;
		$gVarsT[$i] = array('val' => $gVars[$i] , 'desc' => 'Current timestamp');

		$this->vars = $gVars;
		$this->varsTexted = $gVarsT;
	}

	/***************************************************************************
	* Build vars LI for display
	*/
	public function varsToList() {
		$strVars = '';

		foreach ($this->varsTexted as $i => $var)
			$strVars .= "<li><code>&lbrace;$i&rbrace;</code>={$var['val']} : {$var['desc']}</li>";

		return $strVars;
	}

	/***************************************************************************
	* Build all text list
	*/
	public function textOptions() {
		$strTrgTxt = '';
		$conf = Config::getInstance();
		$cHour = $conf->getModuleConfig('speakingclock', 'hour');
		$cNeutral = $conf->getModuleConfig('speakingclock', 'neutral');

		$strTrgTxt .= '<optgroup label="Neutral">';
		for ($i=0; $i<count($cNeutral); $i++) {
			$nTxt = $cNeutral[$i];
			$strTrgTxt .= "<option value=\"n.$i\">{$nTxt['text']}</option>";
		}

		foreach ($cHour as $hId => $hTxts) {
			$strTrgTxt .= '<optgroup label="'.$hId.'">';
			for ($i=0; $i<count($hTxts); $i++) {
				$hTxt = $hTxts[$i];
				$strTrgTxt .= "<option value=\"$hId.$i\">{$hTxt['text']}</option>";
			}
		}

		return $strTrgTxt;
	}

	/***************************************************************************
	* Getters
	*/
	public function getVars() { return $this->vars; }
	public function getHour() {	return $this->hour; }
	public function getTimestampToStr() { return MessageFormatter::formatMessage('fr_FR', ' {0,date,FULL} {0,time,MEDIUM}', array($this->timestamp)); }

}
