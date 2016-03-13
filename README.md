# Home Voicify

**Système de génération de notification vocal pour les maisons domotisées**

### Version

**Beta** en construction

Version : **0.0.01.02**

- main : 0.0
- audio_system : 01
- wording_system : 02

### Présentation

*Donner de la voix à votre maison domotisée !*

Certaines actions de votre domotique méritent une notification vocal :

> Bon retour chez vous Monsieur, la température intérieur est de 22°

> La porte du garage principale est restée ouverte !

> Armement de l'alarme, vous avez deux minutes pour évacuer les lieux.

> Pensez à sortir la poubelle du tri sélectif pour demain.


**Avantages de ce système**

* Contient plusieurs moteurs de synthèse vocale (PicoTTS, Google, ...)
* Peut émettre la notification vers plusieurs types de cible (OpenKarotz, Squeezebox, ...)
* Pour une même notificaiton, peut générer plusieurs phrases différentes, paramétrable pour éviter la lassitude.

### Origines

Github : [https://github.com/Nikya/voicify](https://github.com/Nikya/voicify)

Auteurs : [sarakha63](https://github.com/sarakha63) & [Nikya](https://github.com/Nikya)

## Installation

Cloner le projet sur un serveur PHP.

	git clone https://github.com/Nikya/voicify
	git submodule init
	git submodule update

### Prerequis

- PHP version 5.6.+
	- Avec extension *[Internationalization](http://php.net/manual/fr/intl.installation.php)*.
* Python version x.x
* Manipulateur de MP3 : FFMPEG, ...

### A vérifier

Droits suffisants de lecture/ecriture sur les dossiers suivants :

- core/tmp
- core/config

## Utilisation

### Les concepts

#### Le *voicekey*

C'est un *mot-clé* qui contient plusieurs variations de textes différents correspondants à une seule et même notification.

Exemple du contenu d'un *voicekey* qui serait ``garage``

> La porte du garage, est restée ouverte.

> Un oublie de la porte du garage.

> Suis-je autorisée à fermer la porte du garage ?

Exemple du contenue d'un autre *voicekey* qui serait ``welcome``

> Bien le bonjour !

> Bon retour chez vous.


#### Le *placeholder*

C'est un emplacement dans le texte d'un *voicekey* qui dynamiquement sera remplacé par une valeur spécifique.

Il sont repésentés par la syntaxe suivante : ``{99}``

Exemple du contenue d'un *voicekey* qui serait ``garage``
> La porte du garage *{0}*, est restée ouverte.
>> La porte du garage *principal*, est restée ouverte.

> Un oublie de la porte du garage *{0}*.
>> Un oublie de la porte du garage *principal*.

> Suis-je autorisée à fermer la porte du garage *{0}* ?
>> Suis-je autorisée à fermer la porte du garage *principal* ?

Exemple du contenue d'un autre *voicekey* qui serait ``welcome``
> Bien le bonjour *{0}* ! La température intérieur est de *{1}*°.
>> Bien le bonjour *Maître* ! La température intérieur est de *22*°.

> J'ai régulé la température à *{1}*°, en anticipation de votre retour *{0}* !
>> J'ai régulé la température à *22*°, en anticipation de votre retour *Maître* !

> Bon retour chez vous *{0}*, où il y fait *{1}*°.
>> Bon retour chez vous *Maître*, où il y fait *22*°.

#### Le *subvoicekey*

C'est un *sous-mot-clé* qui designe un unique élément mais qui peut avoir plusieurs appellations. Il est destiné à être injecteé dans le texte d'un *voicekey*.

Exemples de *subvoicekey* ``tony`` :
> Tony

> Monsieur Stark

> Maître

Exemples de la combinaison de ce *subvoicekey* ``tony`` avec le *voicekey* ``welcome`` :

> Bon retour chez vous *{0}* !

>> Bon retour chez vous *Tony* !

>> Bon retour chez vous *Monsieur Stark* !

>> Bon retour chez vous *Maître* !

> Bien le bonjour *{0}* !

>> Bien le bonjour *Tony* !

>> Bien le bonjour *Monsieur Stark* !

>> Bien le bonjour *Maître* !

### Tester les notifications

Avec un navigateur web, accéder simplement à l'url du serveur :

Exemple :

	http:/example.com/voicify

#### La page *Play*

Cette page permet de tester la génération de notification à partir d'un *voicekey* choisie.

**Voicekey** : Permet de choisir un *voicekey* parmi ceux existants.

**Vars** : Permet d'injecteer des valeurs variables dans le texte d'un *voicekey* :  
La variable ``0`` sera injecteée dans le *placeholder* ``{0}`` s'il existe,  
La variable ``1`` sera injecteée dans le *placeholder* ``{1}`` s'il existe,  
Et ainsi de suite, sans limite au nombre de variables.  
Si la valeur de cette variable correspond à un *subvoicekey* connue, alors cette variable sera remplacée par une valeur possible du *subvoicekey*.

**Parle** : Déclanche la notification vocale selon les paramètres précédents

1. Génère la phrase
	1. Choisi une phrase de manière aléatoire parmi celles disponibles pour le *voicekey* choisi.
	1. Parmis les variables fournis, vérifie si elles correspondent à un *subvoicekey* connue et le cas échéant, en choisie une variante aléatoirement.
	1. injecte la valeur des varibles (ou la substitution d'un *subvoicekey*) dans le texte aux emplacements correspondants.
1. Génère le son du *texte parlé* correspondant à la phrase précédente, grâce au moteur *TTS*
1. Envoie le son de ce texte au système sonore.

L'action sur ce bouton permet donc d'entendre la notification générée.  
Le travail du moteur interne peut être vue dans la *console* de cette page.  
L'URL qui permet de re-générer cette notification est disponible dans le champs ``target URL``  
Et enfin, un aperçu de la phrase finale est visible dans le champs rose.

Exemple :

* **Voicekey** : ``welcome``  
* **var 0** : ``tony``  
* **var 1** : ``22.4``  

Peut générer les notifications suivantes :

> Bien le bonjour *Maître* ! La température intérieur est de *22.4*°.

> J'ai régulé la température à *22.4*°, en anticipation de votre retour *Tony* !

> Bon retour chez vous *Monsieur Stark*, où il y fait *22.4*°.

> Bon retour chez vous *Tony*, où il y fait *22.4*°.

### Déclencher depuis la domotique

Depuis un système domotique, il suffit d'ajouter à vos *scénarios / règles / programmes* un appel à l'URL *play* obtenue au paragraphe précédent :

Exemple :

	http:/example.com/voicify/?voicekey=welcome&vars[]=tony&vars[]=22.1

Où certaines adaptations peuvent être nécessaires :

* ``voicekey=welcome`` : Correspond au *voicekey* à notifier  
* ``&vars[]=tony`` :  La 1er occurence de ``&vars[]`` correspond à la valeur de la variable à injecter dans le *placeholder* ``{0}``. Dans cet exemple il s'agit d'un *subvoicekey*
* ``&vars[]=22.4`` :  La 2eme occurence de ``&vars[]`` correspond à la valeur de la variable à injecter dans le *placeholder* ``{1}``. Dans cet exemple il s'agit d'une température qui devra être calculée en amont par le système domotique.
* ``&vars[]=`` : Sont les variables suivantes qui seront injectées dans les *placeholders* suivant ``{2}, {3}, ...`` dans l'ordre de leur apparition dans l'URL. Dans cet exemple, elles ne sont pas utilisées et peuvent donc être supprimées.

### Configurer les textes

Avec un navigateur web, accéder simplement à l'url de votre serveur :

Exemple :

	http:/example.com/voicify

#### Le menu config:Wording

Permet de configurer les *voicekeys* et les *subvoicekeys* :

* Ajouter / modifier / supprimer des *voicekeys* et *subvoicekeys*
* Ajouter / modifier / supprimer des textes à l'intérieur d'un *voicekey* ou *subvoicekey*

#### La fréquence

Pour un *voicekey* donné (ou *subvoicekey*), le système va choisir de manière **aléatoire** une phrase parmi celles correspondantes.  
La fréquence permet d'influer sur ce coté aléatoire.  
Une phrase avec une fréquence élevé sera plus souvent choisie qu'une phrase à fréquence plus faible.  
Une fréquence à 0 permet d'exclure la sélection de cette phrase. (Utile pour exclure des phrases saisonières ou qui ont fini par lasser)

## Fonctionnalités à venir

* Page d'édition des *subvoicekeys*
* Horloge parlante
* ...
