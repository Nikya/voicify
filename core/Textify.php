<?php

/*******************************************************************************
* To generate text
*	* from a frequenced text collection
*	* With some data to inject
*	* Search for subvoicekey from data
*
*/
class Textify {

	/** List of the original frequenced text */
	private $oFreqTextList;

	/** List of original data to inject */
	private $oData;

	/** List of substitued data to inject */
	private $data;

	/** Selected index of the text to use */
	private $selectedIndex;

	/** The final generated text */
	private $finalText;

	/***************************************************************************
	* Constructor
	*
	* @param $freqTextList List of the frequenced text
	* @param $data List of data to inject
	*/
	public function __construct($freqTextList, $data) {
		$this->oFreqTextList = $freqTextList;
		$this->oData =  $data;
	}

	/***************************************************************************
	* The full processs to generate the final text
	*/
	public function process() {
		$this->selectedIndex = self::pickIndex($this->oFreqTextList);
		$this->substituteSubvoicekey();
		$this->injectData();
	}

	/***************************************************************************
	* Select a text index in the collection : randomly but weighted by the frequency
	*/
	private static function pickIndex($freqTextList) {
		$freqIndexList = array();

		// Apply the frequency on the text
		// For each frequenced text
		foreach ($freqTextList as $i => $fText) {
			// Increment the frequency until to reach the expected frequency
			for($f=1; $f<=$fText['frequency']; $f++)
				array_push($freqIndexList, $i);
		}

		// Select randomly the index in the frequenced index List
		return $freqIndexList[array_rand($freqIndexList, 1)];
	}

	/***************************************************************************
	* Replace some data by a subvoicekey if exists
	*/
	private function substituteSubvoicekey() {
		$this->data = array();

		$config = Config::getInstance();
		$colSubvoicekey = $config->getModuleConfig('voicekey', 'subvoicekey');

		foreach ($this->oData as $od) {
			if (array_key_exists($od, $colSubvoicekey))
				array_push($this->data, $colSubvoicekey[$od][self::pickIndex($colSubvoicekey[$od])]['text']);
			else
				array_push($this->data, $od);
		}
	}

	/***************************************************************************
	* Inject the data into the selected text and put the result in the final text
	*/
	private function injectData() {
		$t = $this->getSelectedText();
		$d = $this->data;
		$l = 'fr_FR';

		$this->finalText = SELF::sInjectData($t, $d);
	}

	/***************************************************************************
	* Static version of inject data
	*/
	public static function sInjectData($t, $d) {
		$l = 'fr_FR';
		return MessageFormatter::formatMessage($l, $t, $d);
	}

	/***************************************************************************
	* Getters
	*/
	public function getOFreqTextList() {
		return $this->oFreqTextList;
	}
	public function getOData() {
		return $this->oData;
	}
	public function getSelectedText() {
		return $this->oFreqTextList[$this->selectedIndex]['text'];
	}
	public function getData() {
		return $this->data;
	}
	public function getFinalText() {
		return $this->finalText;
	}
}
