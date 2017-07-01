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
    * heure de lever et de coucher du soleil
    * Conditions pour la matinée puis pour l'aprés-midi
    * Température
        * Actuelement extérieur
        * Matinée
        * Aprés-midi

## Liens utiles
* [Météo France](http://www.meteo-france.mobi)
* [Google Agenda](https://www.google.com/calendar/)

## Version
Module 1.0

## Paramétrage
* **Breakingtext** : Les textes utilisés sont multiples et paramétrables
* **Weather** : Configurer la ville utilisée pour la météo
* **GAgenda** : Configuration des agendas Google à utiliser

### Detail des _Breakingtext_


* Général
    * `intro` : Introduction avec date du jour
    * `conclusion` : Conclusion
* Agenda
    * `a_transition` : Introduction aux agendas
    * `a_first` : Lecture premier agenda
    * `a_then` : Lecture agenda suivant
    * `a_last` : Lecture dernier agenda
    * `a_no` : L'agenda est vide
    * `a_nos` : Tous les agendas sont vides
    * `a_error` : Erreur de lecture d'un agenda
* Météo
    * `w_transition` : Introduction à la météo
    * `w_sunrise_f` : Lévée de soleil futur
    * `w_sunrise_p` : Lévée de soleil passé
    * `w_sunset` : Couché de soleil
    * `w_description_mono` : Conditions de la journée
    * `w_description_double` : Conditions matin puis après-midi
    * `w_temperature` : Température actuel, min puis max
