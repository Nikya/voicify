# Home Voicify
## Donner de la voix à votre maison domotisée !

**Est un système de génération de _notification vocal variées_ pour les maisons domotisées**.

* Version : `2.0`
* Auteur : [Nikya](https://github.com/Nikya)
* Origine : [GitHub/Nikya/Voicify](https://github.com/Nikya/voicify)

## Présentation

Certaines actions de votre domotique **méritent une _notification vocale_ **:

> La porte du garage est restée ouverte !

> Bon retour chez vous Monsieur, la température intérieure est de _22°_

> Armement de l'alarme, vous avez 2 minutes pour évacuer les lieux.

> Pensez à sortir la poubelle _du tri sélectif_ pour demain.

#### Variation des notifications

Le principal avantage de _Home Voicify_ est que pour un même type de notification, il va générer des phrases qui varient : il **évite la lassitude** ou **évite de s'habituer** à une notification et donc à la longue un risque de ne plus y prêter attention.

Avec une notification qui serait "_oublie fermer porte garage_", il est possible de paramétrer des phrases différentes comme :

> La porte du garage, est restée ouverte.

> Un oublie de la porte du garage.

> Qui voudrait bien fermer la porte du garage ?

 _Home Voicify_ gère l'injection de variables dans le texte de la notification, par exemple une température qui peut varier :  

> Bon retour chez vous Monsieur, la température intérieure est de _22°_.

## Fonctionnalités

#### Module type _FEATURE_

Plusieurs _fonctionnalités_ exploitent ce concept de _notification vocale variée_ :

* [**Voicekey**](./module/voicekey/README.md) : Pour un certain mot clé, pouvoir définir des variations de texte.
* [**Speaking-Clock**](./module/speakingclock/README.md) : Une horloge parlante qui annoncera l'heure toujours de façon différente.

#### Module type _TTSENGINE_

Compatible avec plusieurs systèmes de _génération de synthèse vocale_ (TTS) :

* [**Freerabbits**](./module/freerabbits/README.md) : Envoie de la notification vers un OpenKarotz sous FreeRabbits OS (Openkarotz)
* [**Jarvis**](./module/jarvis/README.md) : Envoie de la notification vers un système Jarvis
* [**ImperiHome**](./module/openkarotz/README.md) : Envoie de la notification vers une applciation ImperiHome

## Mise en service

### Prerequis

- Avoir installé _git_
- Avoir un serveur Web _PHP_
	- Version >5.6
	- Extension `intl` : [Internationalization](http://php.net/manual/intl.installation.php)
	- Extension `curl` : [lib curl](http://php.net/manual/curl.setup.php)

### Installer

Cloner [ce projet](https://github.com/Nikya/voicify) dans un répertoire _web_ du serveur PHP.

	git clone https://github.com/Nikya/voicify

### Vérifier

Droits de fichier suffisants de _lecture/écriture_ sur les dossiers de **configuration** et **temporaire** :

```shell
sudo chmod 777 config
sudo chmod 777 temp
```

### Initialiser

1. Aller sur l'URL du serveur Web-PHP où est installé _Home Voicify_.
1. Aller sur l'interface web de _Home Voicify_
1. Comme demandé, executer le `Setup`
1. Une fois le `Setup` accomplie, il ne reste plus qu'à utiliser ce système

## Utiliser

### Tester
Tester et jouer avec les fonctionalités du système grace au menu `Play`.

Chaque page est constituée de 3 parties :

* **Read Me** : Contient la documentation
* **Action** : Contient les éléments d'intérations
* **Console** : Affiche les résultats de l'intération :
	* **URL** : Contient l'URL appelée par l'intération
	* **Indicateur** : La couleur indique le bon déroulement ou non de l'intérration
	* **Output** : Affiche un détail du bon déroulement ou non de l'intérration
	* **Saying** : Affiche le texte final envoyé au systéme de notification vocale

### Liaison domotique
Chaque utilisation d'une fonctionalité génére une _**URL** d'API_ dans la _console_.

Il suffit ensuite :
1. de _copier_ cette URL
2. de la _coller_ dans un composant de votre sytéme de domotique qui est capable d'appeler des URL externes.
3. De _programmer_ dans votre domotique un appel vers cette URL au moments opportun.

## Contribuer

Vous pouvez contribuer à la vie du projet par l'utilisation classique de GitHub (issues, pull request, ...).  

Vous pouvez proposer vos propores modules de _fonctionalité_ ou de _TTS engine_ : il existe dans les fichiers du projet un modules caché nommé `moduleTemplate` qui a vocation a être dupliqué pour servire de modèle de création pour de nouveaux modules.

## A venir

- Général
	- Page de configuration des modules en mode éditable
- Modules _FEATURE_
	- **Breaking news / Report** : Génération d'un compte rendu ou d'un journal personalisé
- Modules _TTSENGINE_
	- **Amazon Echo**
	- **Google Home**
	- **Sarha**

Voir également : [TODO.md](TODO.md)

## Remerciements

* Système d'affichage des fichiers Markdown [**parsedown**](https://github.com/erusev/parsedown) par [erusev](https://github.com/erusev)
