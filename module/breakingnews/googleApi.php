<?php

// Systéme d'intéroguation de l'API Google PHP

require_once ('vendor/google-api-php-client/autoload.php');

/***************************************
* Pour obtenir une URL d'obtention d'autorisation Google
*/
function gAuthorizeUrl() {
  global $config;

  $client = createGoogleClient();
  $authUrl = $client->createAuthUrl();

  return $authUrl;
}

/***************************************
* Enregistrer une compte autorisé
* @param $gAccount Le compte Google
* @param $code Le code d'autorisation fournie
*/
function gConnect($gAccount, $code) {
  if (isset($code)) {
    $client = createGoogleClient();
    $client->authenticate($code);
    $accessToken = $client->getAccessToken();
    if ($accessToken!==null) {
      traceInfo(__FUNCTION__, 'Access Token available');
      gcWrite($gAccount, $accessToken);
    }
    else
      traceWarn(__FUNCTION__, "Can't get Access Token for '.$gAccount");
  }

  $accessTokenTest = gcLoad()[$gAccount];
  if (isset($accessTokenTest) and !empty($accessTokenTest)) {
    traceInfo(__FUNCTION__, "Access Token correctement enregistré pour $gAccount");
  }
}

/***************************************
* Lire une partie de l'agenda pour tester l'accés à l'API
* @param $gAccount Le compte Google
*/
function gTest($gAccount, $agendaId) {
    $tokenData = gcLoad()[$gAccount];

    if (!isset($tokenData) or empty($tokenData)) {
      traceError(__FUNCTION__, $tokenData);
      throw new Exception('Token Data incorrecte pour '.$gAccount);
    }
    else {
      $client = createGoogleClient();
      $client->setAccessToken($tokenData);
      $service = new Google_Service_Calendar($client);
      $calendarList = $service->calendarList->listCalendarList();

      // RDV de la semaine à venir
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
        $agendaId,
        array('timeMin'=>$timeMin, 'timeMax'=>$timeMax, 'orderBy'=>'startTime', 'singleEvents'=>true)
      );

      echo "<h1>Mes rendez-vous à venir du $dateMinStr au $dateMaxStr</h1><ol>";

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

        echo "<li><em>$timeRange</em> : <b>$summary</b></li>";
      }
    }
    echo "</ol>";
}

/***************************************
* Agenda du jour pour le compte spécifié
*
* @param $gAccount Le compte Google
* @return Un tableau de RDV [debut, fin, summary]
*/
function agendaToday($gAccount, $agendaId) {
  $todayArray = null;

  try {
    $tokenData = gcLoad()[$gAccount];

    if (!isset($tokenData) or empty($tokenData)) {
      traceError(__FUNCTION__, $tokenData);
      return null;
    }
    else {
      $client = createGoogleClient();
      $client->setAccessToken($tokenData);
      $service = new Google_Service_Calendar($client);

      // RDV de la semaine à venir
      $dateMin = date_create();
      $dateMax = date_create();
      date_time_set($dateMin, 0, 0);
      date_time_set($dateMax, 23, 59);
      $timeMin = date_format($dateMin, DATE_RFC3339);  //Note that the timestamps are in RFC 3339 format.
      $timeMax = date_format($dateMax, DATE_RFC3339);

      $listEvents = $service->events->listEvents(
        $agendaId,
        array('timeMin'=>$timeMin, 'timeMax'=>$timeMax, 'orderBy'=>'startTime', 'singleEvents'=>true)
      );

      $todayArray = array();
      foreach ($listEvents->getItems() as $event) {
        $start = date_create_from_format(DATE_RFC3339, $event->getStart()->getDateTime());
        $end = date_create_from_format(DATE_RFC3339,  $event->getEnd()->getDateTime());
        if (strcasecmp($event->getVisibility(),'private')==0)
          $summary = "Evenement privé";
        else
          $summary = $event->getSummary();

        $eArray = array (
          'start' => $start,
          'end' => $end,
          'summary' => $summary
        );

        array_push($todayArray, $eArray);
      }

      return $todayArray;
    }
  } catch (Exception $e) {
    traceWarn(__FUNCTION__, $e->getMessage());
  }
}

/***************************************
* Pour créer un client Google
* @return La client créé
*/
function createGoogleClient() {
  global $config;

  $client = new Google_Client();
  $client->setClientId($config['google-api']['client_id']);
  $client->setClientSecret($config['google-api']['client_secret']);
  $client->setRedirectUri($config['google-api']['redirect_uri']);
  $client->addScope("https://www.googleapis.com/auth/calendar.readonly");
  $client->setAccessType('offline');

  return $client;
}

/***************************************
* Supprimer les information de token du compte
* @param $gAccount Compte a reset
*/
function gcReset($gAccount) {
  traceInfo(__FUNCTION__, $gAccount);
  gcWrite($gAccount, '');
}

/***************************************
* Configuration de Google API et des Tokens
*/
$tokenFile = 'config/google-api-token.json';

/***************************************
* Charge les fichier de conf
*/
function gcLoad() {
  global $tokenFile;

  $tokenDataStr = file_get_contents ($tokenFile);
  $tokenData = json_decode ($tokenDataStr, true);

  return $tokenData;
}

/***************************************
* Ecrit les fichier de conf
*/
function gcWrite($gAccount, $token) {
  global $tokenFile;
  $tokenData = gcLoad();

  $tokenData['timestamp'] = time();
  $tokenData[$gAccount] = $token;
  $tokenDataStr = json_encode($tokenData);
  $res = file_put_contents ($tokenFile, $tokenDataStr);

  if ($res===FALSE)
    traceWarn(__FUNCTION__, 'Problème de persistence du token dans : '.$tokenFile);
}

/***************************************
* Affiche les fichier de conf
*/
function gcTrace() {
  global $gConf;

  $gConfTabStr = print_r($gConf, true);
  traceDebug(__FUNCTION__, $gConfTabStr);
}
