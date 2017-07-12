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
    * heure de levé et de couché du soleil
    * Conditions pour la matinée puis pour l'après-midi
    * Températures
        * Actuellement extérieur
        * Matinée
        * Après-midi

## Liens utiles
* [Météo France](http://www.meteo-france.mobi)
* [Google Agenda](https://www.google.com/calendar/)

## Version
Module 1.0

## Paramétrage
* **Breakingtext** : Les textes utilisés sont multiples et paramétrables
* **Main** : Configurer la ville utilisée pour la météo et lister les agendas voulues
* **GAgenda** : Configuration des authorisation d'agendas par Google

### Agendas Ids
 Un agenda est référencé sous la forme suivante (format et exemple) :

    "email@gmail.com:::agendaId": "Nom d'agenda"

    "tony.stark@gmail.com:::primary": "Tony perso"

* **Email** : L'adresse Gmail du compte à utiliser
* **Séparateur** : Un séparateur `:::` :
* **Agenda ID** : Identifiant de l'agneda dans le compte Gmail
    * Si l'identifiant est le même que l'adresse email du compte utilisateur, il faut alors saisir `primary`
    * Sinon l'identifiant est l'adresse email d'un autre propriétaire ou alors sous la forme : `aabbccddeeff123456789@group.calendar.google.com`
    * Cette identifiant se trouve dans la configuration des agendas côté Google : Google Agenda \ paramètres \ Agendas \ choisir un agenda \ Adresse URL de l'agenda \ Id de l'agenda
* **Nom** : Un nom d'agenda libre, uniquement utile pour l'affichage et la diction


### Detail des _Breakingtext_

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
* Météo
    * `w_transition` : Introduction à la météo
    * `w_sunrise_f` : Lévée de soleil futur
    * `w_sunrise_p` : Lévée de soleil passé
    * `w_sunset` : Couché de soleil
    * `w_description_mono` : Conditions de la journée
    * `w_description_double` : Conditions matin puis après-midi
    * `w_temperature` : Température actuel, min puis max
