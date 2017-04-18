## Build TO-DO list
* [ ] Build de certains TTS engine principaux
* [ ] MàJ Doc MD
* [ ] Go to prod
* [ ] Publish Github
* [ ] Rework coté eedomus

* [x] Continuer dans playAPI pour avoir des page de play générique
* [x] Au setup verifier que les module type TTS en un pTtsEngineApi.php file CONTINUER ICI puis commit
* [x] Liste déroulante des TTSEngine disponibles

* [x] Avoir un grand menu détaillé sur la home page rangé différament du menu : Par module
* [ ] Avoir une page basique de config : Pour le module 'Base' pourvoir choisir juste le moteur par défaut
* [x] Avoir un module PLAY tout simple pour tester du text simple des différents moteurs
	* [x] Idéal pour mettre en place du fonctionnement d'une page play vers sont API
	* [x] Mettre en place un moteur TTS appelé DummyTSS : Logger dans un fichier les textes reçues
	* [x] Listing des TTS engine
* [x] Naviguer vers les pages du module
* [x] Class Config : Avoir des sous methode de lecture du manafiest Main
	* [x] Seulement les plays, seulement les config, ...
	* [x] retournant un nouveau manifest avec les info du module et les sous infos
	* [x] NOTA un sous module quand est le seul dans le module est prefixé par pMAin ou cMain, ....
	* [x] Puis utiliser ce principe pour reconstruire les menu
	* [x] Puis utiliser se principe pour faire les check et les cahrgements dans l'INDEX
* [x] Séparer le CoreUtils en class utilisataire distinct
* [x] Comment contruire les pages en fonciton des pages déclarés dans les manifest : Construction du menu
* [x] Page Home et constrution du menu
* [x] Page de Setup pour déclancher l'installation et voir les modules instalés
* [x] Chaque module peut etre jouable et/ou configurable
* [x] Un seul genre de module typé FEATURE ou TTS engine dans les manifest
* [x] Tout mettr dans les manifest : voir le manifest du TEMPALATE
* [x] Extraire pour mutualiser des composants de vue : Console, saying, README, ... : Créer des ccc (trouver un meilleur nom)

## Doc
* [ ] Revoir le README general
* [ ] Revoir tous les autre README
* [ ] Créer un README pour la creation de MODULE : CONTRIBUTE

## Feature list
* [ ] Breaking news
* [ ] Reminder
* [ ] Séparer les voicekey des subvoicekey
	* [ ] Les subvoicekey sont utilisable dans tous les autre foncitonalités

## TTS engine
* [ ] Jarvis
* [ ] Open karotz
* [ ] Jeedom Say
* [ ] Amazon Echo
* [ ] Google Home
* [ ] Sarha
