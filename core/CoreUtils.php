<?php

	require_once('./core/Setup.php');
	require_once('./core/JsonUtils.php');
	require_once('./core/vendors/erusev/parsedown/Parsedown.php');

/*******************************************************************************
* Common Core utiliy functions
*/
class CoreUtils {

	/** To store console messages */
	private static $aConsole = array();

	/***************************************************************************
	* To add a message into the console output
	*/
	public static function console ($lvl, $tag, $msg, $mixed=null) {
		array_push(self::$aConsole, array(
			'lvl' => $lvl,
			'tag' => $tag,
			'msg' => $msg,
			'mixed' => $mixed
		));
	}
	public function consoleD ($tag, $msg, $mixed=null) {	self::console('D', $tag, $msg, $mixed); }
	public function consoleI ($tag, $msg, $mixed=null) {	self::console('I', $tag, $msg, $mixed); }
	public function consoleW ($tag, $msg, $mixed=null) {	self::console('W', $tag, $msg, $mixed); }
	public function consoleE ($tag, $msg, $mixed=null) {	self::console('E', $tag, $msg, $mixed); }

	/***************************************************************************
	* Common Core utiliy functions
	*/
	public static function consolePrint() {
		$out = '';
		$lvl2Indicator = array(
			'D' => 'ok',
			'I' => 'ok',
			'W' => 'warn',
			'E' => 'ko'
		);

		foreach (self::$aConsole as $entry) {
			$mixed = $entry['mixed'];
			$fMixed = $mixed != null ? print_r($mixed, true) : '';

			if (is_object($mixed))
				if ($mixed instanceof Exception)
					$fMixed = $mixed->getTraceAsString();


			//$out .= "[${entry['lvl']} | ${entry['tag']}]\t${entry['msg']}$fMixed\n\n";
			$out .= <<<EOE
				<li>
					<span class="lvl{$lvl2Indicator[$entry['lvl']]} lvl">{$entry['lvl']} - {$entry['tag']}</span>
					<p>{$entry['msg']}</p>
					<pre>$fMixed</pre>
				</li>
EOE;
		}

		echo $out;
		self::$aConsole = array();
	}

	/***************************************************************************
	* Common Core utiliy functions
	*/
	public static function consoleIndicator() {
		$cptW = 0;

		foreach (self::$aConsole as $entry) {
			if ($entry['lvl'] == 'E')
				return 'ko';
			else if ($entry['lvl'] == 'W')
				$cptW++;
		}

		return $cptW>0 ? 'warn' : 'ok';
	}

	/***************************************************************************
	* Parse a Mardown file into a formated text
	*/
	public static function mdParse($path) {
		$c = file_get_contents($path);

		if ($c===false)
			throw new Exception("Fail to read the Markdown file '$path' ");

		return Parsedown::instance()->text($c);
	}

	/***************************************************************************
	* To store already read config */
	private static $configs = array();

	/* Load a config Json file into an array */
	public static function getConfig($module, $confName='main') {
		$key = $module . ($confName!=null ? '_'.$confName : '');

		if (!array_key_exists($key, $configs)) {
			$configs[$key] = JsonUtils::jFile2Array("./config/$key.json");
		}

		return $configs[$key];
	}

	/***************************************************************************
	* Check if a module existe and is installed
	*/
	public static function existFeatureModule($id) {
		$modules = self::getConfig('modules', null);

		return array_key_exists($id, $modules['feature']);
	}

	/***************************************************************************
	* Check if a module existe and is installed
	*/
	public static function existTtsengineModule($id) {
		$modules = self::getConfig('modules', null);

		return array_key_exists($id, $modules['ttsengine']);
	}

	/***************************************************************************
	* Check if a module existe and is installed
	*/
	public static function getFeatureModules() {
		return array_key_exists($id, $modules['ttsengine']);
	}
}
