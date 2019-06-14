# Breaking News
_Votre actualité personnelle, sous forme d'un petit journal audio._

**Contenue**

Annonce les informations suivantes :

1. Date
    * La date du jour
2. Google Agenda
    * Les rendez-vous du jour
    * Plusieurs agendas possibles
3. Météo
    * Heure de levé et de couché du soleil
    * Conditions météo
    * Températures prévisionnelles
4. Autres textes libres

## Liens utiles
* [apixu.com](https://www.apixu.com)
* [Google Agenda](https://www.google.com/calendar/)

## Version
Module 1.2

## Paramétrage
* **Breakingtext** : Les textes utilisés sont multiples et paramétrables
* **Main** :
    * Configurer les localisations utilisées pour la météo
    * lister les agendas à énoncer
* **Agendas Permission** : Configuration des authorisation d'agendas Google

### Listing des agendas

Un agenda est référencé par un _Agenda Id_ sous la forme suivante (format puis exemple) :

    "email@gmail.com:::agendaId": "Nom d'agenda"

    "tony.stark@gmail.com:::primary": "Tony perso"

* **Email** : L'adresse Gmail du compte à utiliser
* **Séparateur** : Un séparateur `:::` :
* **Agenda ID** : Identifiant de l'agenda dans le compte Gmail
    * Si l'identifiant est le même que l'adresse email du compte utilisateur ou est absent, il faut alors saisir `primary`
    * Sinon l'identifiant est l'adresse email d'un autre propriétaire ou alors sous la forme : `aabbccddeeff123456789@group.calendar.google.com`
    * Cet identifiant se trouve dans la configuration des agendas côté Google : `Google Agenda \ paramètres \ Agendas \ choisir un agenda \ Adresse URL de l'agenda \ Id de l'agenda`
* **Nom** : Un nom d'agenda libre, uniquement utile pour l'affichage et la diction

### Listing des villes

La météo est extraite de l'API de [apixu.com](https://www.apixu.com)  
Elle est énnoncée pour une liste de ville à définir dans la _weatherList Ids_

### Detail des _Breakingtext_

(Voir la [documentation en ligne](https://github.com/Nikya/voicify/wiki/Syntaxe-Textify) de la _syntaxe Textify_)

* Général
    * `intro` : Introduction avec date du jour
    * `conclusion` : Conclusion
* Agenda
    * `a_transition` : Introduction aux agendas
    * `a_first` : Lecture premier agenda
    * `a_then` : Lecture agenda suivant
    * `a_last` : Lecture dernier agenda
    * `a_no` : Annoncer les agendas vides
    * `a_error` : Annoncer les agendas en erreur
    * `a_private` : Description pour un évènement privé
    * `a_allday` : Durée d'un évènement sur toute la journée
    * `a_inday` : Durée d'un évènement
    * `a_bigduration` : Durée d'un évènement sur plusieurs jours
* Météo
    * `w_transition` : Introduction à la météo
    * `w_sunrise_f` : Lévée de soleil futur
    * `w_sunrise_p` : Lévée de soleil passé
    * `w_sunset` : Couché de soleil
    * `w_description_mono` : Conditions de la journée
    * `w_description_double` : Conditions matin puis après-midi (Pas utilisé avec l'API de météo actuelle)
    * `w_temperature_double` : Températures min puis max
    * `w_temperature_outside` : Température actuel extérieur

#### Les _Free Breakingtext_ et les champs vars

Il est possible d'ajouter des textes personalisés `free_99`.  
Ces textes _libres_ seront ajoutés en fin de journal.  
Les _vars_ y seront injectés.
