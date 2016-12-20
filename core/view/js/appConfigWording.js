/** L'application de configuration */
var app = angular.module('appConfigWording', ['ngAnimate']);

/******************************************/
/** Controleur Principale */
app.controller('configWordingCtrl', function ($scope, $http) {

	$scope.consoleDebug = true;
	$scope.console = "";
	$scope.voicekeyList;
	$scope.prefixList;
	$scope.newVoicekey="";

	/** Récupération des voicekey distant */
	$http({
		method: 'GET',
		url: 'api.php?action=get_voicekey'
	}).then(function successCallback(response) {
		if(response.data.success==false)
			$scope.console = "Fail get Json voicekeyList - "+ response.data.error;
		else
			$scope.voicekeyList = response.data.list;
	}, function errorCallback(response) {
		if (response===undefined || response.config===undefined)
			$scope.console = "Fail get Json voicekeyList";
		else
			$scope.console = '#' + response.status + " : " + response.statusText + " >> " + response.config.url;
	});

	/** Récupération de la liste de prefix distant */
	$http({
		method: 'GET',
		url: 'api.php?action=get_config_prefixList'
	}).then(function successCallback(response) {
		if(response.data.success==false)
			$scope.console = "Fail get Json get_config_prefixList - "+ response.data.error;
		else
			$scope.prefixList = response.data.list;
	}, function errorCallback(response) {
		if (response===undefined || response.config===undefined)
			$scope.console = "Fail get Json get_config_prefixList";
		else
			$scope.console = '#' + response.status + " : " + response.statusText + " >> " + response.config.url;
	});

	/** Fonction de sauvegarde global des modifications de voicekey */
	$scope.saveVoicekey = function() {
		$http({
			method: 'POST',
			url: 'api.php?action=post_voicekey',
			data: $scope.voicekeyList
		}).then(function successCallback(response) {
			if(!response.data.success)
				$scope.console = "Fail POST Json voicekeyList - "+ response.data.error;
			else
				$scope.console = "Sauvegarde réussie";
		}, function errorCallback(response) {
			console.log(response);
			if (response===undefined || response.config===undefined)
				$scope.console = "Fail post data Json voicekeyList";
			else
				$scope.console = '#' + response.status + " : " + response.statusText + " >> " + response.config.url;
		});
	};

	/** Fonction d'ajout d'un voicekey */
	$scope.addVoicekey = function() {
		if ($scope.newVoicekey.trim()) {
			$scope.newVoicekey = $scope.newVoicekey.replace(/[^A-Z0-9]/gi, '');
			if (!$scope.voicekeyList.hasOwnProperty($scope.newVoicekey)) {
				$scope.voicekeyList[$scope.newVoicekey] = {"cache":true, "prefix":"default", "textList":[]};
				$scope.newVoicekey = '';
				//$scope.trace(0, $scope.voicekeyList);
				location.hash = "vk_"+$scope.newVoicekey;
			} else
				$scope.trace(2, $scope.newVoicekey + " existe déja !");
		}
	};

	/** Fonction de supression d'un Vk complet */
	$scope.deleteVk = function(voicekey) {
		delete $scope.voicekeyList[voicekey];
	};

	/** Fonction d'édition d'un Vk */
	$scope.editVk = function(voicekey, voicekeyData) {
		$scope.$broadcast('editVkEvent', voicekey, voicekeyData);
	};

	/** Fonction d'edition d'un texte paramétré */
	$scope.editPtext = function(voicekey, i) {
		$scope.$broadcast('editPtextEvent', {voicekey:voicekey, i:i});
	};

	/** Fonction de supression d'un text paramétré */
	$scope.deletePtext = function(voicekey, i) {
		$scope.voicekeyList[voicekey]['textList'].splice(i, 1);
	};

	/** Fonction de supression d'un text paramétré */
	$scope.addPtext = function(voicekey, i) {
		$scope.$broadcast('addPtext', voicekey);
	};

	/** Tracer un mesage dans la console */
	$scope.trace = function (lvl, msg) {
		// Debug
		if (lvl==0 && $scope.consoleDebug)
			$scope.console =  msg;
		// User Info
		else if (lvl==1)
			$scope.console = msg;
		// User Warn
		else if (lvl==2)
			$scope.console = msg;
		// User Error
		else if (lvl==2)
			$scope.console = msg;
	}
});

/******************************************/
/** Controleur Secondaire pour edition de pText*/
app.controller('editTextCtrl', function ($scope) {
	/** Formulaire visible ? */
	$scope.editTextOn = false;

	/** Mode d'edition courant */
	var editTextMode = "EDIT";

	/** Index du pText en cours d'édition */
	var pTextIndex;

	/** Point d'entré en provenance du controleur principale pour edition */
	$scope.$on('editPtextEvent', function (event, args) {
		pTextIndex = args.i;

		var pText = $scope.voicekeyList[args.voicekey]['textList'][pTextIndex];

		$scope.edtVoicekey = args.voicekey;
		$scope.edtText = pText.text;
		$scope.edtFrequency = pText.frequency;
		$scope.cntPlaceholder = 0;
		updateBntPalceholder();
		$scope.editTextOn = true;
		editTextMode = "EDIT";
	});

	/** Point d'entré en provenance du controleur principale pour ajout */
	$scope.$on('addPtext', function (event, args) {
		$scope.edtVoicekey = args;
		$scope.edtText = "";
		$scope.edtFrequency = 1;
		$scope.cntPlaceholder = 0;
		updateBntPalceholder();
		$scope.editTextOn = true;
		editTextMode = "ADD";
	});

	/** Mise à jours du bouton d'ajout des placeholder */
	function updateBntPalceholder() {
		$scope.bntPalceholder = "{"+$scope.cntPlaceholder+"}";
	}

	/** Ajouter un placeholders au texte */
	$scope.concatPlaceholder = function() {
		$scope.edtText = $scope.edtText + " " + $scope.bntPalceholder;
		$scope.cntPlaceholder = $scope.cntPlaceholder+1;
		updateBntPalceholder();
	};

	/** Annuler l'edition */
	$scope.cancel = function() {
		$scope.editTextOn = false;
		location.hash = "vk_"+$scope.edtVoicekey;
	};

	/** Valider l'edition */
	$scope.valid = function() {
		if (editTextMode=="EDIT") {
			var pText = $scope.voicekeyList[$scope.edtVoicekey]['textList'][pTextIndex];
			pText.text=$scope.edtText;
			pText.frequency=$scope.edtFrequency;
			$scope.editTextOn = false;
			location.hash = "vk_"+$scope.edtVoicekey;
		} else if (editTextMode=="ADD") {
			var pText = {
				text : $scope.edtText,
				frequency : $scope.edtFrequency
			};

			$scope.voicekeyList[$scope.edtVoicekey]['textList'].push(pText);
			$scope.editTextOn = false;
			location.hash = "vk_"+$scope.edtVoicekey;
		}
	};
});


/******************************************/
/** Controleur Secondaire pour edition de VK */
app.controller('editVkCtrl', function ($scope) {
	/** Formulaire visible ? */
	$scope.editVkOn = false;

	var oldVoicekey;

	/** Point d'entré en provenance du controleur principale pour edition */
	$scope.$on('editVkEvent', function (event, voicekey, voicekeyData) {
		oldVoicekey = voicekey;
		$scope.edtVoicekey = voicekey;
		$scope.edtPrefix = voicekeyData.prefix;
		$scope.edtCache = voicekeyData.cache;
		$scope.editVkOn = true;
	});

	/** Annuler l'edition */
	$scope.cancel = function() {
		$scope.editVkOn = false;
		location.hash = "vk_"+oldVoicekey;
	};

	/** Valider l'edition */
	$scope.valid = function() {
		$scope.edtVoicekey = $scope.edtVoicekey.trim().replace(/[^A-Z0-9]/gi, '');
		if($scope.voicekeyList.hasOwnProperty(oldVoicekey)) {
			$scope.voicekeyList[oldVoicekey].prefix = $scope.edtPrefix;
			$scope.voicekeyList[oldVoicekey].cache = $scope.edtCache;

			if(!$scope.voicekeyList.hasOwnProperty($scope.edtVoicekey)) {
				$scope.voicekeyList[$scope.edtVoicekey] = $scope.voicekeyList[oldVoicekey];
				delete $scope.voicekeyList[oldVoicekey];
			}
		}
		$scope.editVkOn = false;
		location.hash = "rowsfooter";
	};
});

/******************************************/
/** Autres */

/** Filtre pour mise en évidence des placeholders des textes */
app.filter('placeholderize', function($sce) {
	return function(value) {
		var fValue = value.replace(/({.+?})/g, "<span class=\"placeholder\">$1</span>");
		return $sce.trustAsHtml(fValue);
	};
});
