<?php

/*******************************************************************************
* Utils fucntionality to Use the google API
*/
require_once('core/vendors/google-api-php-client/autoload.php');
class UtilsGoogleApi {

	// The agenda long Id seperator
	const A_LONG_ID_SEP = ':::';

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

	/** Technical Id */
	private $aId;

	/** Google account */
	private $aAccount;

	/** User agenda name */
	private $aName;

	/***************************************************************************
	* Constructor : Init the API client
	*/
	public function __construct($aLongId, $aName) {
		$exALongId = self::explodeALongId($aLongId);
		$this->aAccount = $exALongId[0];
		$this->aId = $exALongId[1];
		$this->aName = $aName;

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
	* Séparer un long agenda Id en 2 partie : Account et AgendaId
	*
	* @param $aLongId Un id D'agenda long
	* @return array de 2 element : account, agendaId
	*/
	public static function explodeALongId($aLongId) {
		return explode(SELF::A_LONG_ID_SEP, $aLongId);
	}

	/***************************************************************************
	* Assemble un Account et AgendaId dans une long Agenda Id
	*
	* @param $aAccount Id de compte
	* @param $aId Id d'agenda
	*
	* @return A Agenda long Id
	*/
	public static function buildALongId($aAccount, $aId) {
		return $aAccount . SELF::A_LONG_ID_SEP . $aId;
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
		$allToken[$this->aAccount] = $token;
		Config::getInstance()->saveModuleConfig('breakingnews', 'googleApiToken', $allToken);
	}

	/***************************************************************************
	* Read the token from the config token file
	*/
	public function readToken() {
		$allToken = Config::getInstance()->getModuleConfig('breakingnews', 'googleApiToken');

		if(!array_key_exists($this->aAccount, $allToken))
			throw new Exception("No Agenda access permission for {$this->aAccount}", 1);

		return $allToken[$this->aAccount];
	}

	/***************************************************************************
	* Test to read some agenda entries
	*/
	public function test() {
		$token = $this->readToken();

		$this->gClient->setAccessToken($token);
		$service = new Google_Service_Calendar($this->gClient);

		// Filtrage : RDV de la semaine à venir
		$dateMin = date_create();
		$dateMax = date_create();
		date_time_set($dateMin, 0, 0);
		date_time_set($dateMax, 23, 59);
		date_add($dateMax, date_interval_create_from_date_string('10 days'));
		$timeMin = date_format($dateMin, DATE_RFC3339);  //Note that the timestamps are in RFC 3339 format.
		$timeMax = date_format($dateMax, DATE_RFC3339);
		$dateMinStr = date_format($dateMin, 'd/m/y');
		$dateMaxStr = date_format($dateMax, 'd/m/y');

		$listEvents = $service->events->listEvents(
			$this->aId,
			array('timeMin'=>$timeMin, 'timeMax'=>$timeMax, 'orderBy'=>'startTime', 'singleEvents'=>true)
		);

		$res = array();
		foreach ($listEvents->getItems() as $event) {
			$timeRange =  '';
			$start = $event->getStart()->getDateTime();
			if (!is_null($start)) {
				$start = date_create_from_format(DATE_RFC3339, $start);
				$startStr = date_format($start, 'd/m/y H:i');
				$end = $event->getEnd()->getDateTime();
				$end = date_create_from_format(DATE_RFC3339, $end);
				$endStr = date_format($end, 'd/m/y H:i');
				$timeRange = $startStr. ' - ' .$endStr;
			} else
				$timeRange = 'Toute la journée';

			if (strcasecmp($event->getVisibility(),'private')==0)
				$summary = "Evenement privé";
			else
				$summary = $event->getSummary();

			array_push($res, "$timeRange : $summary");
		}

		return $res;
	}

	/***************************************************************************
	* Test to read some agenda entries
	*/
	public function delete() {
		$allToken = Config::getInstance()->getModuleConfig('breakingnews', 'googleApiToken');
		unset($allToken[$this->aAccount]);
		Config::getInstance()->saveModuleConfig('breakingnews', 'googleApiToken', $allToken);
	}
}
