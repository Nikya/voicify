# Home Voicify - Release notes

Voir [TODO](TODO.md) pour le contenue à venir.

## 2.1.0

### Feature
* [NEW] [**breakingnews**](./module/breakingnews/README.md) : Un journal quotidien

### TTS Engine
* [NEW] [**eedomusTts**](./module/eedomusTts/README.md) : La box domotique eedomus
* [NEW] PREFIX : Possibilité d'ajouter un prefix avant la génération d'un TTS

### Other
* Check version beetwen a module and his configs files
* Check CORE version
* Add log reading page
* Apply CHMOD on file creation
* Other minor fixes ...

# 2.0

* Deep re-engineering of the system

# 1.x

* First attempt of the project

# Gen note

Technique de génération de la release note :

	git log `git describe --tags --abbrev=0`..HEAD --oneline
