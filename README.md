# Home Voicify

**Système de génération de _notification vocal variées_ pour les maisons domotisées**

* Version : `1.1`
* Auteur : [Nikya](https://github.com/Nikya)
* Projet : [GitHub/Nikya/Voicify](https://github.com/Nikya/voicify)

## Présentation

*Donner de la voix à votre maison domotisée !*

Certaines actions de votre domotique méritent une notification vocale :

> Bon retour chez vous Monsieur, la température intérieur est de 22°

> La porte du garage est restée ouverte !

> Armement de l'alarme, vous avez deux minutes pour évacuer les lieux.

> Pensez à sortir la poubelle du tri sélectif pour demain.

Home Voicify est un système qui permet de générer se genre de notification.

#### Variation des notifications
Le principale avantage de _Home Voicify_ est que pour un même type de notificaion, il va générer des phrases qui varient : il **avite la lassitude** ou **évite de s'habituer** à une notification et donc à la longue ne plus y preter attention.

Avec une notificaiton qui serait `oublie de fermer la porte du garage`, il est possible de paramètrer des phrases différentes comme :

> La porte du garage, est restée ouverte.

> Un oublie de la porte du garage.

> Qui voudrait bien fermer la porte du garage ?


 _Home Voicify_ gére l'injection de variable dans le texte de la notification, par exemple un température qui peut varier :  

> Bon retour chez vous Monsieur, la température intérieur est de `22°`.

## Modules

Il est compatible avec plusieurs systémes de _génération de synthése vocal_ et posséde plusieurs _fonctionalités_ exploitant ce concept de '_notification vocale varié_'.

#### _FEATURE_
Liste des foncitonalités déjà présentes :

* [**Voicekey**](./module/voicekey/README.md) : Pour un certains mot clé, pouvoir définir desvariation de texte et leurs fréquences d'utilisation.
* [**Speaking-Clock**](./module/speakingclock/README.md) : Une horloge parlante qui vous annoncera l'heure jamais de la même façon

#### _TTSENGINE_
Liste des systéme de génération de synthése vocale compatible (TTS) :

* [**Openkarotz**](./module/openkarotz/README.md) : Envoie de la notification vers un OpenKarotz
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

Cloner le projet sur le serveur PHP.

	git clone https://github.com/Nikya/voicify

### Vérifier

Droits suffisants de _lecture/ecriture_ sur les dossiers de **configuration** et **temporaire** :

```shell
	sudo chmod 777 config
	sudo chmod 777 temp
```

### Initialiser

* Aller sur l'URL du serveur Web-PHP où est installé _Home Voicify_.
* Executer le `Setup` dans le menu `Config/setup` de  _Home Voicify_

### Utiliser

Une fois le `Setup` accomplie, utiliser les différents menu pour accéder aux fonctionalités et les tester.

Chaque utilisation de fonctionalité génére une URL d'API dans la _console_, il suffit ensuite de copier/coller cette URL vers un sytéme externe (Votre solution domotique) pour re-déclancher l'action testée.

## A venir

- Général
	- Page de configuration des modules
- Modules _FEATURE_
	- **Breaking news / Report** : Génération d'un compte rendu ou d'un journal personalisé
	- **Reminder** : Génération de rappels
- Modules _TTSENGINE_
	- **Amazon Echo**
	- **Google Home**
	- **Sarha**
