<?php


/*******************************************************************************
********************************************************************************
********************************************************************************
* To store calandar account information
*/
class CalandarAccountBean {

	// The agenda long Id seperator
	const A_LONG_ID_SEP = ':::';

	/** Google account */
	private $account;

	/** Technical long */
	private $longId;

	/** Technical Id */
	private $id;

	/** User agenda name */
	private $name;

	/***************************************************************************
	* Constructor : Init the API client
	*/
	public function __construct($aLongId, $aName) {
		$exALongId = SELF::explodeALongId($aLongId);
		$this->longId = $aLongId;
		$this->account = $exALongId[0];
		$this->id = $exALongId[1];
		$this->name = $aName;
	}

	/***************************************************************************
	* Séparer un long agenda Id en 2 partie : Account et AgendaId
	*
	* @param $aLongId Un id D'agenda long
	* @return array de 2 element : account, agendaId
	*/
	public static function explodeALongId($aLongId) {
		$ex = explode(SELF::A_LONG_ID_SEP, $aLongId);

		if (count($ex)!=2)
			throw new Exception("Invalid Agenda Long ID '$aLongId'");

		return $ex;
	}

	/***************************************************************************
	* Assemble un Account et AgendaId dans une long Agenda Id
	*
	* @param $gAccount Id de compte
	* @param $aId Id d'agenda
	*
	* @return A Agenda long Id
	*/
	public static function buildALongId($account, $id) {
		return $account . SELF::A_LONG_ID_SEP . $id;
	}

	/***************************************************************************
	* Chargement de la list des agendas sous forme d'un tableau de bean
	*
	* @return array de CalandarAccountBean
	*/
	public static function autoLoadList() {
		$agendaList = Config::getInstance()->getModuleConfig('breakingnews', 'main')['agendaList'];
		$agendaBeanList = array();

		foreach ($agendaList as $aLongId => $aName)
			array_push($agendaBeanList, new CalandarAccountBean($aLongId, $aName));

		return $agendaBeanList;
	}


	/***************************************************************************
	* To string value for display
	*/
	public function toString() {
		return $this->name . '(' . $this->longId .')';
	}

	/***************************************************************************
	* Getters
	*/
	public function getAccount() { return $this->account; }
	public function getLongId() { return $this->longId; }
	public function getId() { return $this->id; }
	public function getName() { return $this->name; }
}

/*******************************************************************************
********************************************************************************
********************************************************************************
* Handle the Google API to get a valid Google client then a Google Service
*/
require_once('core/vendors/google-api-php-client/autoload.php');
class UtilsGoogleApi {

	/***************************************************************************
	* Google API client information
	*/
	const CLIENT_ID = "825725303966-5fbsuc5qblialdvn189ingstbg3p8o3l.apps.googleusercontent.com";
	const PROJECT_ID = "homevoicify";
	const AUTH_URI = "https://accounts.google.com/o/oauth2/auth";
	const TOKEN_URI = "https://accounts.google.com/o/oauth2/token";
	const AUTH_PROVIDER_X509_CERT_URL = "https://www.googleapis.com/oauth2/v1/certs";
	const CLIENT_SECRET = "mQJRQTI1r_y1k2JDZ7ePHKaF";
	const REDIRECT_URI = "urn:ietf:wg:oauth:2.0:oob";

	/** The Google API client */
	private $gClient;

	/** Google account */
	private $gAccount;

	/***************************************************************************
	* Constructor : Init the API client
	*/
	public function __construct($gAccount) {
		$this->gAccount = $gAccount;
		$this->gClient = self::buildGClient();
	}

	/***************************************************************************
	* Constructor : Init the API client
	*/
	private static function buildGClient() {
		$gClient = new Google_Client();
		$gClient->setClientId(SELF::CLIENT_ID);
		$gClient->setClientSecret(SELF::CLIENT_SECRET);
		$gClient->setRedirectUri(SELF::REDIRECT_URI);
		$gClient->addScope("https://www.googleapis.com/auth/calendar.readonly");
		$gClient->setAccessType('offline');

		return $gClient;
	}

	/***************************************************************************
	* Pour obtenir une URL d'obtention d'autorisation Google
	*/
	public static function getAuthorizeUrl() {
		return self::buildGClient()->createAuthUrl();
	}

	/***************************************************************************
	* Connect and autorized account
	*/
	public function connect($code) {
		if (!isset($code) or empty($code))
			throw new Exception('Connect code invalid');

		try {
			$accessToken='45688';
			$this->gClient->authenticate($code);
			$accessToken = $this->gClient->getAccessToken();
		} catch (Exception $e) {
			throw new Exception('Google Access Token validation fail', 1, $e);
		}

		if ($accessToken!==null) {
			$this->saveToken($accessToken);
			$tmpAccessToken = $this->readToken();
			if (!isset($tmpAccessToken) or empty($tmpAccessToken))
				throw new Exception('Fail to save and read Access Token');
		}
		else
			throw new Exception('Fail to get Access Token');
	}

	/***************************************************************************
	* Save the token into the config token file
	*/
	public function saveToken($token) {
		$allToken = Config::getInstance()->getModuleConfig('breakingnews', 'googleApiToken', false);
		$allToken[$this->gAccount] = $token;
		Config::getInstance()->saveModuleConfig('breakingnews', 'googleApiToken', $allToken);
	}

	/***************************************************************************
	* Read the token from the config token file
	*/
	public function readToken() {
		$allToken = Config::getInstance()->getModuleConfig('breakingnews', 'googleApiToken', false);

		if(!array_key_exists($this->gAccount, $allToken))
			throw new Exception("No Agenda access permission for {$this->gAccount}", 1);

		return $allToken[$this->gAccount];
	}

	/***************************************************************************
	* Test to read some agenda entries
	*/
	public function delete() {
		$allToken = Config::getInstance()->getModuleConfig('breakingnews', 'googleApiToken', false);
		unset($allToken[$this->gAccount]);
		Config::getInstance()->saveModuleConfig('breakingnews', 'googleApiToken', $allToken);
	}

	/***************************************************************************
	* To get a Calendar Service
	*/
	public function getServiceCalendar() {
		$this->gClient->setAccessToken($this->readToken());
		return new Google_Service_Calendar($this->gClient);
	}
}

/*******************************************************************************
********************************************************************************
********************************************************************************
* Handle the Google API : Calendar services
*/
class UtilsGoogleAgenda {

	/* The Google Service Calendar */
	private $gServiceCalendar;

	private $calandarAccountBean;

	/***************************************************************************
	* Constructor
	*
	* @param gCalendar a Google Service Calendar
	*/
	public function __construct($gServiceCalendar, $calandarAccountBean) {
		$this->gServiceCalendar = $gServiceCalendar;
		$this->calandarAccountBean = $calandarAccountBean;
	}

	/***************************************************************************
	* Read a clandar range between Min and max date
	*
	* @param $dateMin Start date
	* @param $dateMax End date
	*
	* @return array of CalandarEntryBean
	*/
	private function readCalendarRange($dateMin, $dateMax) {
		$timeMin = date_format($dateMin, DATE_RFC3339);  //Note that the timestamps are in RFC 3339 format.
		$timeMax = date_format($dateMax, DATE_RFC3339);
		$dateMinStr = date_format($dateMin, 'd/m/y');
		$dateMaxStr = date_format($dateMax, 'd/m/y');

		if ($this->gServiceCalendar->events==null)
			throw new Exception("Fail to read calendar Range");

		$listEvents = $this->gServiceCalendar->events->listEvents(
			$this->calandarAccountBean->getId(),
			array('timeMin'=>$timeMin, 'timeMax'=>$timeMax, 'orderBy'=>'startTime', 'singleEvents'=>true)
		);

		$res = array();

		foreach ($listEvents->getItems() as $event) {
			$startDt =	date_create_from_format(DATE_RFC3339, $event->getStart()->getDateTime());
			$endDt =	date_create_from_format(DATE_RFC3339, $event->getEnd()->getDateTime());
			$summary = 	$event->getSummary();
			$private = false;

			if (strcasecmp($event->getVisibility(),'private')==0) {
				$summary = null;
				$private = true;
			}

			$ceb = new CalandarEntryBean ($startDt, $endDt, $summary, $private);
			array_push($res, $ceb);
		}

		return $res;
	}

	/***************************************************************************
	* To test a calandar reading over 10 days
	*
	* @return array of CalandarEntryBean
	*/
	public function test() {
		$dateMin = date_create();
		$dateMax = date_create();
		date_time_set($dateMin, 0, 0);
		date_time_set($dateMax, 23, 59);
		date_add($dateMax, date_interval_create_from_date_string('10 days'));

		return $this->readCalendarRange($dateMin, $dateMax);
	}

	/***************************************************************************
	* Read the today agenda
	*
	* @return array of CalandarEntryBean
	*/
	public function today() {
		$dateMin = date_create();
		$dateMax = date_create();
		date_time_set($dateMin, 0, 0);
		date_time_set($dateMax, 23, 59);

		return $this->readCalendarRange($dateMin, $dateMax);
	}
}


/*******************************************************************************
********************************************************************************
********************************************************************************
* An Agenda bean to store information
*/
class CalandarEntryBean extends stdClass {
	public function __construct($startDT, $endDT, $summary, $private=false) {
		$this->startDT = $startDT;
		$this->endDT = $endDT;
		$this->summary = $summary;
		$this->private = $private;
	}
}


/*******************************************************************************
********************************************************************************
********************************************************************************
* Le constructeur des brealing news
*/
class BreakingnewsBuilder {

	/** Contient des morceau du breakingnews */
	private $fullContent = array();

	/** L'instance de l'utilitaire des configurations */
	private $config;

	/** Donnée suplémentaires pour les BreakingFree */
	private $freeData;

	/** FAire marquer une pause dans la lecture */
	const PAUSE_TAG = " . . . ";

	/***************************************************************************
	* Le contructeur de la class
	*/
	public function __construct($freeData) {
		$this->config = Config::getInstance();
		$this->freeData = $freeData;
	}

	/***************************************************************************
	* Genere le BN en entier
	*/
	public function process() {
		$fullContent = array();

		$fullContent = array_merge($fullContent, $this->processIntro());
		$fullContent = array_merge($fullContent, $this->processTransitionAgenda());
		array_push($fullContent, self::PAUSE_TAG);
		$fullContent = array_merge($fullContent, $this->processAgenda());
		$fullContent = array_merge($fullContent, $this->processTransitionWeather());
		array_push($fullContent, self::PAUSE_TAG);
		$fullContent = array_merge($fullContent, $this->processWeather());
		array_push($fullContent, self::PAUSE_TAG);
		$fullContent = array_merge($fullContent, $this->processFree());
		array_push($fullContent, self::PAUSE_TAG);
		$fullContent = array_merge($fullContent, $this->processConclusion());

		$this->fullContent = self::addDot($fullContent);
	}

	/***************************************************************************
	* Obtenir le BN en entier
	*/
	public function getResult() {
		return $this->fullContent;
	}

	/***************************************************************************
	* Add a dot at the end of each sentaence if missing
	*/
	private function addDot($fullContent) {
		$newFullContent = array();

		foreach ($fullContent as $c) {
			$newC = trim($c);

			if (strlen($newC)>0 and !preg_match('/[\!\?\.]$/', $newC))
				$newC .= '.';

			array_push($newFullContent, $newC);
		}

		return $newFullContent;
	}

	/***************************************************************************
	* Get a frequenced text collection from configuration file Breakingtext
	*/
	private function getBreakingtext($colName) {
		if (array_key_exists($colName, $this->config->getModuleConfig('breakingnews', 'breakingtext')))
			return $this->config->getModuleConfig('breakingnews', 'breakingtext')[$colName];
		else
			throw new Exception("Unknow text collection '$colName' in the breakingtext collection");

	}

	/***************************************************************************
	* Get a frequenced text collection from configuration file Breakingtext
	*/
	private function getWeatherList() {
		return $this->config->getModuleConfig('breakingnews', 'main')['wetherList'];
	}

	/***************************************************************************
	* A sub process
	*/
	private function processIntro() {
		$col = $this->getBreakingtext('intro');
		$data = array(time());

		$tfy = new Textify($col, $data);
		$tfy->process();

		return array ($tfy->getFinalText());
	}

	/***************************************************************************
	* A sub process
	*/
	private function processConclusion() {
		$col = $this->getBreakingtext('conclusion');
		$data = array(time());

		$tfy = new Textify($col, $data);
		$tfy->process();

		return array ($tfy->getFinalText());
	}

	/***************************************************************************
	* A sub process
	*/
	private function processTransitionAgenda() {
		$col = $this->getBreakingtext('a_transition');

		$tfy = new Textify($col, array());
		$tfy->process();

		return array ($tfy->getFinalText());
	}

	/***************************************************************************
	* A sub process
	*/
	private function processTransitionWeather() {
		$col = $this->getBreakingtext('w_transition');

		$tfy = new Textify($col, array());
		$tfy->process();

		return array ($tfy->getFinalText());
	}

	/***************************************************************************
	* A sub process
	*/
	private function processAgenda() {
		$fullAgenda = array();
		$agendaBeanList = CalandarAccountBean::autoLoadList();
		shuffle($agendaBeanList);

		// Sauvegarder le contenue des agendas (Indexer par agenda Id)
		$aaError = array();
		$aaNo = array();
		$aaYes = array();

		// Génére chaque agenda brut
		foreach($agendaBeanList as $aBean) {
			$aBean->calandarEntries = null;
			try {
				$uGoogle = new UtilsGoogleApi($aBean->getAccount());
				$uCalendar = new UtilsGoogleAgenda($uGoogle->getServiceCalendar(), $aBean);
				$res = $uCalendar->today();

				// Pas de resultat
				if (count($res)>0) {
					$aBean->calandarEntries = $res;
					array_push($aaYes, $aBean);
				}
				else
					array_push($aaNo, $aBean);
			} catch (Exception $e) {
				Console::w(__FUNCTION__, "Fail to read the Agenda {$aBean->getName()}", $e);
				array_push($aaError, $aBean);
			}
		}

		$fullAgenda = array_merge($fullAgenda, $this->processAgendaYes($aaYes));
		$fullAgenda = array_merge($fullAgenda, $this->processAgendaNoContent($aaNo, 'a_no'));
		$fullAgenda = array_merge($fullAgenda, $this->processAgendaNoContent($aaError, 'a_error'));

		return $fullAgenda;
	}

	/***************************************************************************
	* A sub process
	*/
	private function processAgendaYes($agendaArray) {
		$aCount = count($agendaArray);
		$full = array();

		if ($aCount > 0) {
			$colFirst = $this->getBreakingtext('a_first');
			$colThen = $this->getBreakingtext('a_then');
			$colLast = $this->getBreakingtext('a_last');
			$col = null;

			for ($i = 0; $i < $aCount; $i++) {
				if ($i == 0) // First
					$col = $colFirst;
				else if ($i+1 == $aCount) // Last
					$col = $colLast;
				else  // Then
					$col = $colThen;

				$content = $this->calEntriesToString($agendaArray[$i]->calandarEntries);
				$data = array($agendaArray[$i]->getName(), $content);
				$tfy = new Textify($col, $data);
				$tfy->process();

				array_push($full, $tfy->getFinalText());
			}
		}

		return $full;
	}

	/***************************************************************************
	* A sub process
	*/
	private function processAgendaNoContent($agendaArray, $colType) {
		$aCount = count($agendaArray);
		$full = array();

		if ($aCount > 0) {
			if ($aCount == 1)
				$data = array($agendaArray[0]->getName(), $aCount);
			else
				$data = array(self::namesToString($agendaArray), $aCount);

			$col = $this->getBreakingtext($colType);
			$tfy = new Textify($col, $data);
			$tfy->process();

			array_push($full, $tfy->getFinalText());
		}

		return $full;
	}

	/***************************************************************************
	* Former une suite de nom d'agenda
	*
	* @return String Les de noms
	*/
	private static function namesToString ($agendaArray) {
		$names= '';
		for ($i = 0; $i < count($agendaArray); $i++) {
			$names .= $agendaArray[$i]->getName();

			if ($i+1 < count($agendaArray))
				$names .= ', ';
		}

		return $names;
	}

	/***************************************************************************
	* Convertir en texte des entrées d'agenda
	*
	* @return String
	*/
	private function calEntriesToString ($calandarEntries) {
		$full = array();

		foreach ($calandarEntries as $entry) {
			Console::d(__FUNCTION__, 'event', $entry);
			$timeRange = $this->dateTimesToString($entry->startDT, $entry->endDT);

			if (!$entry->private)
				$desc = $entry->summary;
			else {
				$col = $this->getBreakingtext('a_private');
				$tfy = new Textify($col, null);
				$tfy->process();
				$desc = $tfy->getFinalText();
			}

			$fullStr = "$timeRange : $desc";
			array_push($full, $fullStr);
		}

		return implode('. ', $full);
	}

	/***************************************************************************
	* Convertir en texte la distance entre 2 dates
	*
	* @return String
	*/
	private function dateTimesToString ($startDT, $endDT) {
		$range = '';

		if (is_null($startDT) or empty($startDT)) {
			$col = $this->getBreakingtext('a_allday');
			$tfy = new Textify($col, null);
			$tfy->process();
			$range = $tfy->getFinalText();
		} else {
			$startToday = self::isToday($startDT);
			$endToday = self::isToday($endDT);
			$data = array($startDT->getTimestamp(), $endDT->getTimestamp());

			if ($startToday and $endToday) {
				$col = $this->getBreakingtext('a_inday');
				$tfy = new Textify($col, $data);
				$tfy->process();
				$range = $tfy->getFinalText();
			} else {
				$col = $this->getBreakingtext('a_bigduration');
				$tfy = new Textify($col, $data);
				$tfy->process();
				$range = $tfy->getFinalText();
			}
		}

		return $range;
	}

	/***************************************************************************
	* Verifie si une DateTime est aujourd'hui
	*
	* @return Boolean
	*/
	private function isToday ($pDt) {
		$today = new DateTime();
		$today->setTime(0, 0, 0);
		$dt = clone $pDt;
		$dt->setTime(0, 0, 0);
		$interval = date_diff($today, $dt);
		return $interval->days==0;
	}

	/***************************************************************************
	* La partie météo des BN
	*/
	private function processWeather() {
		$full = array();
		$weatherList = $this->getWeatherList();
		$weatheData = $this->loadCityWeather($weatherList);
		$ephemerisData = $this->loadSunEphemeris();

		$full = array_merge($full, $this->processSunrise($ephemerisData['sunrise']));
		foreach ($weatherList as $weatherId => $weatherName) {
			$full = array_merge($full, $this->processCondition($weatheData[$weatherId], $weatherName));
			$full = array_merge($full, $this->processTemperature($weatheData[$weatherId]));
		}

		$full = array_merge($full, $this->processSunset($ephemerisData['sunset']));

		return $full;
	}

	/***************************************************************************
	* Obtenir les météo de chaque ville
	*/
	private function loadCityWeather($weatherList) {
		$data = array();

		foreach ($weatherList as $weatherId => $weatherName) {
			$meteoBrowser = new MeteoFranceAPIBrowser($weatherId);

			$data[$weatherId]['morning']['description'] = $meteoBrowser->get('previsions.0_matin.description');
			$data[$weatherId]['afternoon']['description'] = $meteoBrowser->get('previsions.0_midi.description');
			$data[$weatherId]['morning']['temperature'] = $meteoBrowser->get('previsions.0_matin.temperatureCarte');
			$data[$weatherId]['afternoon']['temperature'] = $meteoBrowser->get('previsions.0_midi.temperatureCarte');
		}

		return $data;
	}

	/***************************************************************************
	* Obtenir les information de levé et de couché du soleil
	* (seulemnt pour la première ville)
	*/
	private function loadSunEphemeris() {
		$data = array();

		$data['sunrise'] = date_sunrise(time(), SUNFUNCS_RET_TIMESTAMP); //, $latitude, $longitude, $zenith, $gmtoffset);
		$data['sunset'] = date_sunset(time(), SUNFUNCS_RET_TIMESTAMP); //, $latitude, $longitude, $zenith, $gmtoffset);

		return $data;
	}

	/***************************************************************************
	* A sub process : Sunrise
	*/
	private function processSunrise ($sunriseTS) {
		// Calcule de l'interval entre maintenant et le sunrise
		$sunriseDT = new DateTime();
		$sunriseDT->setTimestamp($sunriseTS);
		$nowDT = new DateTime('now');

		$interval = $nowDT->diff($sunriseDT);
		$data = array($interval->h, $interval->i);

		// événement passé
		if ($interval->invert == 1)
			$col = $this->getBreakingtext('w_sunrise_p');
		// événement futur
		else
			$col = $this->getBreakingtext('w_sunrise_f');

		$tfy = new Textify($col, $data);
		$tfy->process();

		return array ($tfy->getFinalText());
	}

	/***************************************************************************
	* A sub process : Sunset
	*/
	private function processSunset ($sunsetTS) {
		$data = array();
		$col = $this->getBreakingtext('w_sunset');
		$data[0] = $sunsetTS;

		$tfy = new Textify($col, $data);
		$tfy->process();

		return array ($tfy->getFinalText());
	}

	/***************************************************************************
	* A sub process
	*/
	private function processCondition($weatherData, $weatherName) {
		$mDesc = $weatherData['morning']['description'];
		$aDesc = $weatherData['afternoon']['description'];
		$col = null;
		$data = array($weatherName, $mDesc, $aDesc);

		// Si aucune condition trouvée
		if ($mDesc==null and $aDesc==null)
			return array('');
		// Si pas de condition trouvée pour la matinée
		// Ou condition identiques
		elseif ($mDesc == null or strcasecmp($mDesc, $aDesc)==0) {
			$col = $this->getBreakingtext('w_description_mono');
			$data = array($weatherName, $aDesc, $aDesc);
		}
		// Sinon description double
		else
			$col = $this->getBreakingtext('w_description_double');

		$tfy = new Textify($col, $data);
		$tfy->process();

		return array ($tfy->getFinalText());
	}

	/***************************************************************************
	* A sub process
	*/
	private function processTemperature($weatherData) {
		$mT = $weatherData['morning']['temperature'];
		$aT = $weatherData['afternoon']['temperature'];
		$col = $this->getBreakingtext('w_temperature_double');
		$data = array($mT, $aT);

		// Si aucune trouvée
		if ($mT==null and $aT==null)
			return array('');
		// Si pas trouvée pour la matinée
		elseif ($mT == null) {
			$data = array($aT, $aT);
		}

		$tfy = new Textify($col, $data);
		$tfy->process();

		return array ($tfy->getFinalText());
	}

	/***************************************************************************
	* A sub process
	*/
	private function processFree() {
		$full = array();

		for ($i=1; true; $i++) {
			try {
				$col = $this->getBreakingtext("free_$i");
				$tfy = new Textify($col, $this->freeData);
				$tfy->process();
				array_push($full, $tfy->getFinalText());
			} catch (Exception $e) {
				Console::d(__CLASS__, __FUNCTION__, $e);
				break;
			}
		}

		return $full;
	}
}

/*******************************************************************************
********************************************************************************
********************************************************************************
*  Class pour piloter l'API mobile de Meteo France
*/
class MeteoFranceAPIBrowser {

	/** Base URL part of the online API */
	const BASE_URL = 'http://www.meteo-france.mobi/ws/getDetail/france/{cityId}.json';

	/** Current builded URL */
	private $currentUrl;

	/** cURL handler */
	private $ch = null;

	/** city name */
	private $cityId;

	/***************************************************************************
	* Browser constructor
	*
	* @param $cityId The city Id
	*/
	public function __construct($cityId) {
		$this->cityId = urlencode($cityId);
		$this->readData();
	}

	/***************************************************************************
	* Browser destructor
	*/
	public function __destruct() {
		if(!is_null($this->ch))
			curl_close($this->ch);
	}

	/***************************************************************************
	* Execute the http request
	*
	* @return Array of result from the Json response
	*
	*/
	private function readData() {
		$this->ch = curl_init();

		$this->currentUrl = str_replace('{cityId}', $this->cityId, SELF::BASE_URL);

		curl_setopt($this->ch, CURLOPT_URL, $this->currentUrl);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

		$curlRes = curl_exec($this->ch);
		$this->assertSuccess($curlRes);

		$this->data = json_decode($curlRes, true);
	}

	/***************************************************************************
	* Check if returned result is a succes
	*
	* @throws Exception if is a fail
	*
	*/
	private function assertSuccess($curlRes) {
		// Check cURL success
		if ($curlRes===false) {
			$errorMsg = curl_error($this->ch);
			throw new Exception(__CLASS__." cURL error : $errorMsg with URL {$this->currentUrl}");
		}

		// Check HTTP success
		$httpCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
		if(intval($httpCode/100) != 2) {
			$errorMsg = print_r($curlRes,true);
			throw new Exception(__CLASS__." HTTP error [$httpCode] for {$this->currentUrl}. $errorMsg.");
		}

		// Check API success
		$jsonObj = json_decode($curlRes);
		if (is_object($jsonObj)) {
			if( is_null($jsonObj->result->ville)) {
				$errorMsg = print_r($curlRes,true);
				throw new Exception(__CLASS__." API error for {$this->currentUrl}. $errorMsg.");
			}
		} else {
			$errorMsg = json_last_error_msg();
			throw new Exception(__CLASS__." Unknow API return content for $this->currentUrl. $errorMsg :  Content=$curlRes");
		}
	}

	/***************************************************************************
	* To get the raw inner data
	*/
	public function getRawData() {
		return $this->data;
	}

	/***************************************************************************
	* To get a value from the API result
	*
	* @param $path A data path to read
	*
	* @return A value
	*
	*/
	public function get($path) {
		$iList = explode('.', $path);
		$sData = $this->data['result'];

		try {
			foreach ($iList as $i) {
				if (array_key_exists($i, $sData))
					$sData = $sData[$i];
				else
					throw new Exception('GetMeteoFranceDataFail');
			}
		}
		catch (Exception $e) {
			return null;
		}

		return trim($sData);
	}
}
