# Home Voicify - ToDo

Voir [Release](https://github.com/Nikya/voicify/releases) pour le contenue disponible.

###### Poid des fonctionalités en points Fibonacci : 1, 2, 3, 5, 8, 13, 21

## Ready to release
* [x] (5) Breaking news avec sections de texte libre (Temp extérieur, trafic, qualité air, ...)


## Continuer Ici
* [ ] ??


## Prio 1 : Indispensable à la prochaine release
* [ ] (13) Mécanisme de configuration générique pour les fichier Json
* [ ] (8) Imperihome : Prendre en compte le sytéme de prefix intégré


## Prio 2 : Souhaité pour la prochaine release
* [ ] (21) Mécanisme de configuration spécifique au collection de textes
* [ ] (5) Màj des docs générales, README, en ligne (Textify ICU)


## Prio 3 : Apports sympatiques
* [ ] (8) Vérifier si disponibilité de mise à jours
* [ ] (8) Générateur d'URL d'API pour tout les voicekey


## Prio 4 : Apports mineurs
* [ ] (5) Setup : Fonction de nettoyage des fichier temp (et des sauvegardes de conf)
* [ ] (21) Gestion des updates de modules : Mise à jour des fichier de configuration


--------------------------------------------------------------------------------
# Modules
## TTS engine
* [ ] (3) Jeedom Say (existe toujours ?)
* [ ] (13) Google Home (réalisable ?)
* [ ] (13) Amazon echo alexa (réalisable ?)
* [ ] (3) Sarha

## FEATURE
* [ ] ??

--------------------------------------------------------------------------------
# Nota
## ICU Doc

Voir ICU http://site.icu-project.org/ !!!!!!!!!!!!!!!!!!!
http://userguide.icu-project.org/formatparse
http://php.net/manual/fr/class.messageformatter.php
http://icu-project.org/apiref/icu4c/classMessageFormat.html#details

## Realease Gen
Technique de génération de la release note :

	git log `git describe --tags --abbrev=0`..HEAD --oneline
