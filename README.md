# Home Voicify

_**Donner de la voix à votre maison domotisée !**_

Origine du projet : [GitHub/Nikya/Voicify](https://github.com/Nikya/voicify)

## Présentation

**_Home Voicify_ est un système de génération de _notification vocal variable_ pour les maisons domotisées**.  
(Il génére des textes qui doivent être envoyés à un systéme TTS externe)

Certaines actions de votre domotique **méritent une _notification vocale_**:

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

* [**voicekey**](./module/voicekey/README.md) : Pour un certain mot clé déclancheur, obtenir une notificaiton vacale variante.
* [**speakingclock**](./module/speakingclock/README.md) : Une horloge parlante qui annoncera l'heure toujours de façon différente.
* [**breakingnews**](./module/breakingnews/README.md) : Un journal quotidien personalisé (météo, agenda, ...)

#### Module type _TTSENGINE_

Compatible avec plusieurs systèmes de _génération de synthèse vocale_ (TTS) :

* [**eedomusTts**](./module/eedomusTts/README.md) : La box domotique eedomus
* [**freerabbit**](./module/freerabbit/README.md) : Un OpenKarotz sous FreeRabbits OS
* [**jarvis**](./module/jarvis/README.md) :Un système Jarvis
* [**imperihome**](./module/imperihome/README.md) : L'applciation ImperiHome

## Mise en service

### Prerequis

- Avoir un serveur Web _PHP_
	- Version >5.6
	- Extension `intl` : [Internationalization](http://php.net/manual/intl.installation.php)
	- Extension `curl` : [lib curl](http://php.net/manual/curl.setup.php)

### Installer

Dans un _répertoire web_ du serveur PHP :

* Télécharger la [dernière realease](https://github.com/Nikya/voicify/releases/latest)
* ou [git-cloner](https://github.com/Nikya/voicify.git) le projet `git clone https://github.com/Nikya/voicify.git`

### Dossiers d'échanges

Créer à la racine du dossier _voicify_, 2 dossiers d'échanges : **configuration** et **temporaire**, puis leur donner des droits en _lecture/écriture_ :

```shell
mkdir config
mkdir temp
sudo chmod 777 config
sudo chmod 777 temp
```

### Initialiser

1. Aller sur l'URL du serveur Web-PHP où est installé _Home Voicify_.
1. Aller sur l'interface web de _Home Voicify_
1. Puis comme demandé, **executer le `Setup`**
1. Une fois le `Setup` accomplie, il ne reste plus qu'à utiliser ce système

## Utiliser

### Tester
Tester et jouer avec les fonctionalités du système grace au menu `Play`.

Chaque page _play_ est constituée de 3 parties :

* **Read Me** : Contient la documentation contextuel
* **Action** : Contient les éléments d'intérations
* **Console** : Affiche les résultats de l'intération :
	* **URL** : Contient l'URL d'API appelée par l'intération
	* **Indicateur** : La couleur indique le bon déroulement ou non de l'intérration
	* **Output** : Affiche un détail du bon déroulement ou non de l'intérration
	* **Saying** : Affiche le texte final envoyé au systéme de notification vocale

### Liaison domotique
Chaque utilisation d'une fonctionalité génére une _**URL d'API**_ dans la _console_.

Il suffit ensuite :
1. de _copier_ cette URL
2. de la _coller_ dans un composant de votre sytéme de domotique qui est capable d'appeler des URL externes.
3. De _programmer_ dans votre sytéme de domotique, un appel au moments opportun vers ce composant.

### Edition des textes

Les textes utilisés suivent une syntaxe particulière nommée _Textify_ : Voir la [documentation en ligne](https://github.com/Nikya/voicify/wiki/Syntaxe-Textify) pour exploiter au mieux cette syntaxe.

## Releasing

* [Release](https://github.com/Nikya/voicify/releases) : Détails et contenues des releases précédentes
* [ToDo](TODO.md) : Nouveau contenues à venir
* [Contributing](CONTRIBUTING.md) : Comment contribuer au projet
* [Licence](LICENSE) : GNU GENERAL PUBLIC LICENSE

## Remerciements

* Système d'affichage des fichiers Markdown [**parsedown**](https://github.com/erusev/parsedown) par [erusev](https://github.com/erusev)
