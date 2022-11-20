# Jeedom Connect

Est un plugin permettant la connexion d'un **Jeedom** avec l'application mobile _Jeedom Connect_.

Ici, nous utiliseront uniquement la fonctionalité TTS.

- [Lien officiel et documentation](https://jared-94.github.io/JeedomConnectDoc/fr_FR/)
- [code source du plugin](https://github.com/jared-94/JeedomConnect)

## Test préalable

Créer est executer un nouveau scénario Jeedom qui a pour action d'utiliser le plugin _Jeedom Connect_ et sa fonctionalité TTS.  
Juste pour vérifier que votre installation de Jeedom + Jeedom Connect est opérationelle.

## Version

Module 1.0 pour Jeedom Connect 1.5.2

## Paramétrage

* **host** : URL/IP du Jeedom où est installé le plugin _Jeedom connect_ 
* **port** : Port où écoute Jeedom (par défaut 80)
* **jeedomApikey** : Une clé de sécurité Jeedom 
  * Emplacement : Coté Jeedom, réglage / Systeme / Configuration API)
* **commandId** : Numéro de la commande corresponde à la fonctionalité TTS du plugin Jeedom Connect
  * Emplacement : Menu Plugins > Communication > Jeedom Connect > Appareil Jeedom Connect > Commandes > Commande de type action > TTS
* **volume** : Volume du texte prononcé