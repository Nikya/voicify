# Le lecteur de base

_Permet de jouer des textes brutes et tester les différents TTS_.
Ce module contient également des paramètres de base et commun à tout _Home Voicify_

## Play

Le texte à saisir est un simple texte brut.
Il est possible ensuite de selectionner un _Moteur de TTS_ spécifique ou laisser celui par défaut.

## Paramétrage

* **defaultTtsEngine** : Le _Moteur de TTS_ à utiliser si aucun n'est spécifié
* **Locale** : Langue à utiliser
	* pour afficher les README (Si disponible),
	* pour le systéme de _message formater_ interne (Textify)
* **prefix** : Instruction à effectuer avant l'appel de la génération du texte par un TTSEngine (Utile Pour positionner un _Jingle_ avant la prononciation d'un texte)
	* `default` : Aucune pré-action
	* **Un nombre entier** : Un delais d'attente avant la génération du texte
	* **Un texte** : Le TTEngine interprètera ce texte comme il le souhaite si compatible. Pourrait être le nom d'un Jingle
	* **prefixSwitch** : Une sous liste de prefix qui remplacera dans certaines situations le prefix par defaut
		* Est constitué de 3 parties : où l'utilisation de joker `*` est possible
			* nom d'un TTSEngine
			* nom d'un module
			* nom d'un sous module

## Version

Module 1.1 pour Voicify 2.
