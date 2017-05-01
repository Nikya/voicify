# Home Voicify

**Système de génération de _notification vocal variées_ pour les maisons domotisées**.

* Version : `1.1`
* Auteur : [Nikya](https://github.com/Nikya)
* Origine du projet : [GitHub/Nikya/Voicify](https://github.com/Nikya/voicify)

## Présentation

*Donner de la voix à votre maison domotisée !*

Certaines actions de votre domotique méritent une _notification vocale_ :

> La porte du garage est restée ouverte !

> Bon retour chez vous Monsieur, la température intérieure est de 22°

> Armement de l'alarme, vous avez deux minutes pour évacuer les lieux.

> Pensez à sortir la poubelle du tri sélectif pour demain.

**Home Voicify est un système qui permet de générer ce genre de notification mais avec des variations.**

#### Variation des notifications
Le principal avantage de _Home Voicify_ est que pour un même type de notification, il va générer des phrases qui varient : il **évite la lassitude** ou **évite de s'habituer** à une notification et donc à la longue un risque de ne plus y prêter attention.

Avec une notification qui serait "_oublie fermer porte garage_", il est possible de paramétrer des phrases différentes comme :

> La porte du garage, est restée ouverte.

> Un oublie de la porte du garage.

> Qui voudrait bien fermer la porte du garage ?


 _Home Voicify_ gère l'injection de variables dans le texte de la notification, par exemple une température qui peut varier :  

> Bon retour chez vous Monsieur, la température intérieure est de `22°`.

## Fonctionnalités

Il est compatible avec plusieurs systèmes de _génération de synthèse vocale_ et posséde plusieurs _fonctionnalités_ exploitant ce concept de '_notification vocale variée_'.

#### Modules _FEATURE_
Liste des fonctionnalités déjà présentes :

* [**Voicekey**](./module/voicekey/README.md) : Pour un certain mot clé, pouvoir définir des variations de texte.
* [**Speaking-Clock**](./module/speakingclock/README.md) : Une horloge parlante qui annoncera l'heure mais jamais de la même façon

#### Modules _TTSENGINE_
Liste des système de génération de synthèse vocale compatibles (TTS) :

* [**Freerabbits**](./module/freerabbits/README.md) : Envoie de la notification vers un OpenKarotz sous FreeRabbits OS (Openkarotz)
* [**Jarvis**](./module/jarvis/README.md) : Envoie de la notification vers un système Jarvis
* [**ImperiHome**](./module/openkarotz/README.md) : Envoie de la notification vers une applciation ImperiHome

## Mise en service

### Prerequis

- Avoir installé _git_
- Un serveur Web _PHP_
	- Version >5.6
	- Extension `intl` : [Internationalization](http://php.net/manual/intl.installation.php)
	- Extension `curl` : [lib curl](http://php.net/manual/curl.setup.php)

### Installer

Cloner le projet dans un répertoire _web_ d'un serveur PHP.

	git clone https://github.com/Nikya/voicify

### Vérifier

Droits suffisants de _lecture/écriture_ sur les dossiers de **configuration** et **temporaire** :

```shell
sudo chmod 777 config
sudo chmod 777 temp
```

### Initialiser

* Aller sur l'URL du serveur Web-PHP où est installé _Home Voicify_.
* Executer le `Setup` dans le menu `Config/setup` de  _Home Voicify_

Une fois le `Setup` accomplie, utiliser les différents menu pour accéder aux fonctionnalités et les tester.

## Utiliser

Chaque page est constituée de 3 parties :

* **Read Me** : Contient la documentation
* **Action** : Contient les éléments d'intération
* **Console** : Affiche les résultats de l'intération
	* **URL** : Contient l'URL appelée par l'intération
	* **Indicateur** : LA couleur indique le bon déroulement ou non de l'intérration
	* **Output** : Affiche un détail du bon déroulement ou non de l'intérration
	* **Saying** : Affiche le texte final envoyé au systéme de notification vocale

Chaque utilisation d'une fonctionalité génére une _**URL** d'API_ dans la _console_, il suffit ensuite de copier/coller cette URL vers un sytéme externe (Votre solution domotique) pour re-déclancher la même action.

## Contribuer

Vous pouvez contribuer à la vie du projet par l'utilisation classique de projet sur GitHub (issues, pull request, ...).  

Vous pouvez proposer vos propores modules de _fonctionalité_ ou de _TTS engine_ : il existe un modules caché nommé `moduleTemplate` qui a vocation a être dupliqué pour servire de modèle de création.

## A venir

- Général
	- Page de configuration des modules en mode éditable
- Modules _FEATURE_
	- **Breaking news / Report** : Génération d'un compte rendu ou d'un journal personalisé
- Modules _TTSENGINE_
	- **Amazon Echo**
	- **Google Home**
	- **Sarha**
