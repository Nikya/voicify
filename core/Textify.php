<?php

/*******************************************************************************
* To generate text from text collection with some data
*/
class Textify {

	/** Original freqTextCollection */
	private $freqTextCollection;

	/** Data to inject (or extract subvoicekey) */
	private $data;

	/***************************************************************************
	* Constructor
	*/
	private function __construct($freqTextCollection, $data) {
		$this->freqTextCollection = $freqTextCollection;
		$this->data = $data;
	}
}
