# TODO list du projet

_Poid des fonctionalités en points Fibonacci : 1, 2, 3, 5, 8, 13, 21_

## Prio 1 : Indispansable à la prochaine release
* [ ] (13) Module textify
* [ ] (8) Module Voicekey
* [ ] (8) Module Speaking clock
* [ ] (3) Nettoyage Css et Javascrip non utilsés
* [ ] (3) ICU Doc (voir Google Drive 'zelda' + lien en bas de page)
* [ ] (5) Doc à jours (+liens vers cette todo list + Contrib à part + Test lien sur GitHub et redirigé en local ?.)


## Prio 2 : Souhaité pour la prochaine release
* [ ] (5) Mécanisme d'update des fichier de conf en mode texte pure


## Prio 3 : Apports sympatiques
* [ ] (13) Mécanisme d'update des fichier de conf en mode formulaire complet


## Prio 4 : Apports mineurs
* [ ] (3) Setup : Fonction de nettoyage des fichier temp (et des sauvegardes de conf)
* [ ] (13) Gestion des updates de modules : Mise à jour des fichier de configuration
* [ ] (5) README pour la creation de MODULE : CONTRIBUTE

--------------------------------------------------------------------------------
# Modules
## TTS engine
* [ ] (3) Jeedom Say existe toujours ?
* [ ] (3) eedomus say
* [ ] (13) Google Home
* [ ] (13) Amazon alexa
* [ ] (3) Sarha

## FEATURE
* [ ] (13) Breaking news / Report

--------------------------------------------------------------------------------
# Effectué

* [x] Revoir la sortie console : en provenance de PHP est au format Json : Mise en forme par Javascript
* [x] Rework ergonomique
* [x] Build des TTS engine principaux
* [x] Continuer dans playAPI pour avoir des page de play générique
* [x] Au setup verifier que les module type TTS en un pTtsEngineApi.php file CONTINUER ICI puis commit
* [x] Liste déroulante des TTSEngine disponibles
* [x] Avoir un grand menu détaillé sur la home page rangé différament du menu : Par module
* [x] Avoir une page basique de config : Pour le module 'Base' pourvoir choisir juste le moteur par défaut
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
* [x] Revoir le README general
* [x] Revoir tous les autre README

## ICU Doc
Voir ICU http://site.icu-project.org/ !!!!!!!!!!!!!!!!!!!
http://userguide.icu-project.org/formatparse
http://php.net/manual/fr/class.messageformatter.php
http://icu-project.org/apiref/icu4c/classMessageFormat.html#details
