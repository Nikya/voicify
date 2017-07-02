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
		$allToken = Config::getInstance()->getModuleConfig('breakingnews', 'googleApiToken');
		$allToken[$this->gAccount] = $token;
		Config::getInstance()->saveModuleConfig('breakingnews', 'googleApiToken', $allToken);
	}

	/***************************************************************************
	* Read the token from the config token file
	*/
	public function readToken() {
		$allToken = Config::getInstance()->getModuleConfig('breakingnews', 'googleApiToken');

		if(!array_key_exists($this->gAccount, $allToken))
			throw new Exception("No Agenda access permission for {$this->gAccount}", 1);

		return $allToken[$this->gAccount];
	}

	/***************************************************************************
	* Test to read some agenda entries
	*/
	public function delete() {
		$allToken = Config::getInstance()->getModuleConfig('breakingnews', 'googleApiToken');
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
*
* Général
	* `intro` : Introduction avec date du jour
	* `conclusion` : Conclusion
* Agenda
	* `a_transition` : Introduction aux agendas
	* `a_first` : Lecture premier agenda
	* `a_then` : Lecture agenda suivant
	* `a_last` : Lecture dernier agenda
	* `a_no` : L'agenda est vide
	* `a_nos` : Tous les agendas sont vides
	* `a_error` : Erreur de lecture d'un agenda
* Météo
	* `w_transition` : Introduction à la météo
	* `w_sunrise_f` : Lévée de soleil futur
	* `w_sunrise_p` : Lévée de soleil passé
	* `w_sunset` : Couché de soleil
	* `w_description_mono` : Conditions de la journée
	* `w_description_double` : Conditions matin puis après-midi
	* `w_temperature` : Température actuel, min puis max
*/
class BreakingnewsBuilder {

	/** Contient des morceau du breakingnews */
	private $fullContent = array();

	/** L'instance de l'utilitaire des configurations */
	private $config;

	/***************************************************************************
	* Le contructeur de la class
	*/
	public function __construct() {
		$this->config = Config::getInstance();
	}

	/***************************************************************************
	* Genere le BN en entier
	*/
	public function process() {
		$fullContent = array();

		$fullContent = array_merge($fullContent, $this->processIntro());
		$fullContent = array_merge($fullContent, $this->processTransitionAgenda());
		$fullContent = array_merge($fullContent, $this->processAgenda());
		//$fullContent = array_merge($fullContent, $this->processTransitionWeather());
		//$fullContent = array_merge($fullContent, $this->processWeather());
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

			if (!preg_match('/[\!\?\.]$/', $newC))
				$newC .= '.';

			array_push($newFullContent, $newC);
		}

		return $newFullContent;
	}

	/***************************************************************************
	* Get a frequenced text collection from configuration file Breakingtext
	*/
	private function getBreakingtext($colName) {
		return $this->config->getModuleConfig('breakingnews', 'breakingtext')[$colName];
	}

	/***************************************************************************
	* Sub Process */
	private function processIntro() {
		$col = $this->getBreakingtext('intro');
		$data = array(time());

		$tfy = new Textify($col, $data);
		$tfy->process();

		return array ($tfy->getFinalText());
	}

	/***************************************************************************
	* Sub Process */
	private function processConclusion() {
		$col = $this->getBreakingtext('conclusion');

		$tfy = new Textify($col, array());
		$tfy->process();

		return array ($tfy->getFinalText());
	}

	/***************************************************************************
	* Sub Process */
	private function processTransitionAgenda() {
		$col = $this->getBreakingtext('a_transition');

		$tfy = new Textify($col, array());
		$tfy->process();

		return array ($tfy->getFinalText());
	}

	/***************************************************************************
	* Sub Process */
	private function processTransitionWeather() {
		$col = $this->getBreakingtext('w_transition');

		$tfy = new Textify($col, array());
		$tfy->process();

		return array ($tfy->getFinalText());
	}

	/***************************************************************************
	* Sub Process */
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
	* Sub Process */
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
	* Sub Process */
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
	*
	*/
	private function processWeather() {
		$full = array();

		$meteo = new MeteoCore();
		$weatherData = $meteo->getBreakingnewsWeatherData();

		// Intro
		array_push($txtArray, rand1FromArray($text['breakingnews']['w_intro']));
		array_push($txtArray, 'SLEEP_2');

		// sunrise
		array_push($txtArray, buildWhether_sunrise($weatherData));
		array_push($txtArray, ' ... ');

		// Condition
		$morningDescription = $weatherData['morning']['description'];
		$afternoonDescription = $weatherData['afternoon']['description'];
		// Si même condition pour le matin et l'apres midi
		if (strcasecmp($morningDescription,$afternoonDescription)==0)
			$phrase = rand1FromArray($text['breakingnews']['w_description_mono']);
		//  ou si pas de condition trouvée pour la matinée
		elseif (empty($morningDescription)) {
			$phrase = rand1FromArray($text['breakingnews']['w_description_mono']);
			$morningDescription = $afternoonDescription;
		}
		else
			$phrase = rand1FromArray($text['breakingnews']['w_description_double']);

		$phraseFormater = msgfmt_create('fr_FR', $phrase);
		$phraseFinal = msgfmt_format($phraseFormater, array($morningDescription, $afternoonDescription));
		array_push($txtArray, $phraseFinal);
		array_push($txtArray, 'SLEEP_2');

		// Temperature
		$morningTemperature = $weatherData['morning']['temperature'];
		$afternoonTemperature = $weatherData['afternoon']['temperature'];
		$currentTemperature = $weatherData['temperature_exterieur'];
		if (empty($morningTemperature))
			$morningTemperature = $afternoonTemperature;

		$phrase = rand1FromArray($text['breakingnews']['w_temperature']);
		$phraseFormater = msgfmt_create('fr_FR', $phrase);
		$phraseFinal = msgfmt_format($phraseFormater, array($currentTemperature, $morningTemperature, $afternoonTemperature));
		array_push($txtArray, $phraseFinal);

		// sunset
		array_push($txtArray, ' ... ');
		array_push($txtArray, buildWhether_sunset($weatherData));

		return $txtArray;
	}
	private function buildWhether_old() {
		global $text;
		$txtArray = array();

		$meteo = new MeteoCore();
		$weatherData = $meteo->getBreakingnewsWeatherData();

		// Intro
		array_push($txtArray, rand1FromArray($text['breakingnews']['w_intro']));
		array_push($txtArray, 'SLEEP_2');

		// sunrise
		array_push($txtArray, buildWhether_sunrise($weatherData));
		array_push($txtArray, ' ... ');

		// Condition
		$morningDescription = $weatherData['morning']['description'];
		$afternoonDescription = $weatherData['afternoon']['description'];
		// Si même condition pour le matin et l'apres midi
		if (strcasecmp($morningDescription,$afternoonDescription)==0)
			$phrase = rand1FromArray($text['breakingnews']['w_description_mono']);
		//  ou si pas de condition trouvée pour la matinée
		elseif (empty($morningDescription)) {
			$phrase = rand1FromArray($text['breakingnews']['w_description_mono']);
			$morningDescription = $afternoonDescription;
		}
		else
			$phrase = rand1FromArray($text['breakingnews']['w_description_double']);

		$phraseFormater = msgfmt_create('fr_FR', $phrase);
		$phraseFinal = msgfmt_format($phraseFormater, array($morningDescription, $afternoonDescription));
		array_push($txtArray, $phraseFinal);
		array_push($txtArray, 'SLEEP_2');

		// Temperature
		$morningTemperature = $weatherData['morning']['temperature'];
		$afternoonTemperature = $weatherData['afternoon']['temperature'];
		$currentTemperature = $weatherData['temperature_exterieur'];
		if (empty($morningTemperature))
			$morningTemperature = $afternoonTemperature;

		$phrase = rand1FromArray($text['breakingnews']['w_temperature']);
		$phraseFormater = msgfmt_create('fr_FR', $phrase);
		$phraseFinal = msgfmt_format($phraseFormater, array($currentTemperature, $morningTemperature, $afternoonTemperature));
		array_push($txtArray, $phraseFinal);

		// sunset
		array_push($txtArray, ' ... ');
		array_push($txtArray, buildWhether_sunset($weatherData));

		return $txtArray;
	}

	/** Returne une phrase pour decrire le levé du soleil */
	private function buildWhether_sunrise ($weatherData) {
		global $text;

		// Création des dateTime
		$sunriseDT = new DateTime();
		$sunriseDT->setTimestamp($weatherData['ephemeris']['sunrise']);
		$nowDT = new DateTime('now');

		// Calcule de l'interval
		$interval = $sunriseDT->diff($nowDT);

		// Si interval est supérieur à l'heure
		$intervalStrFinal = null;
		if (intval($interval->format('%h') > 0))
			$intervalStrFinal = $interval->format('%h heures %i minutes');
		else
			$intervalStrFinal = $interval->format('%i minutes');

		// Si l'évènement ne sais pas encore produit
		$phrase = null;
		if (intval($interval->format('%R1') < 0))
			$phrase = rand1FromArray($text['breakingnews']['w_sunrise_f']);
		else
			$phrase = rand1FromArray($text['breakingnews']['w_sunrise_p']);

		$phraseFormater = msgfmt_create('fr_FR', $phrase);
		$phraseFinal = msgfmt_format($phraseFormater, array($intervalStrFinal));

		return $phraseFinal;
	}

	/** Returne une phrase pour decrire le levé du soleil */
	private function buildWhether_sunset ($weatherData) {
		global $text;

		$sunsetTS = $weatherData['ephemeris']['sunset'];

		$phrase = rand1FromArray($text['breakingnews']['w_sunset']);

		$phraseFormater = msgfmt_create('fr_FR', $phrase);
		$phraseFinal = msgfmt_format($phraseFormater, array($sunsetTS));

		return $phraseFinal;
	}
}


/*******************************************************************************
********************************************************************************
********************************************************************************
* Class pour lire les informations Meteo
*
* Utilise : http://www.meteo-france.mobi/home#!ville_synthese_999999
*/
class UtilMeteoAPI {

	/** Toute les donnée accumulées */
	private $data = array();

	/** Id de la ville  */
	private $cityId;

	/***************************************************************************
	* Constructor
	*/
	public function __construct($cityId) {
		$this->cityId = $cityId;
	}

	/***************************************************************************
	* Charger les données
	*/
	public function process() {
		$this->loadWeatherData();
		$this->loadSunEphemeris();
	}

	/***************************************************************************
	* Obtenir les donnée chargées
	*/
	public function getData() {
		return $this->data();
	}

	/** Obetenir les donnée pour la météo du breakingnews en provenance de la meteo elle même */
	public function loadWeatherData() {
		$meteoBrowser = new MeteoFranceAPIBrowser($cityId);
		//traceDebug(__FUNCTION__, $meteoBrowser->getRawData());

		// Description forcast
		$data['morning']['description'] = $meteoBrowser->get('previsions.0_matin.description');
		$data['afternoon']['description'] = $meteoBrowser->get('previsions.0_midi.description');

		// temperature
		$data['morning']['temperature'] = $meteoBrowser->get('previsions.0_matin.temperatureCarte');
		$data['afternoon']['temperature'] = $meteoBrowser->get('previsions.0_midi.temperatureCarte');

		return $data;
	}

	/** Obtenir les information de levé et de couché du soleil
	*
	*/
	public function getSunEphemeris() {
		$data = array();
		$latitude = 45.7;
		$longitude = 3.1;
		$zenith = 90+50/60;
		$gmtoffset = ConfigCore::get("global.gmtoffset");

		$data['ephemeris']['sunrise'] = date_sunrise(time(), SUNFUNCS_RET_TIMESTAMP, $latitude, $longitude, $zenith, $gmtoffset);
		$data['ephemeris']['sunset'] = date_sunset(time(), SUNFUNCS_RET_TIMESTAMP, $latitude, $longitude, $zenith, $gmtoffset);

		return $data;
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
			throw new Exception(__CLASS__." \ncURL error : $errorMsg \nwith URL {$this->currentUrl}");
		}

		// Check HTTP success
		$httpCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
		if($httpCode/100 != 2) {
			$errorMsg = print_r($curlRes,true);
			throw new Exception(__CLASS__." \nHTTP error [$httpCode] \nfor $this->currentUrl. \n$errorMsg.");
		}

		// Check API success
		$jsonObj = json_decode($curlRes);
		if (is_object($jsonObj)) {
			if( is_null($jsonObj->result->ville)) {
				$errorMsg = print_r($curlRes,true);
				throw new Exception(__CLASS__." \nAPI error \nfor $this->currentUrl. \n$errorMsg.");
			}
		} else {
			$errorMsg = json_last_error_msg();
			throw new Exception(__CLASS__." \nUnknow API return object \nfor $this->currentUrl. \n$errorMsg.");
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
			traceWarn(__METHOD__, "GetMeteoFranceDataFail unknow [$path] in the data API");
			return null;
		}

		return trim($sData);
	}
}
