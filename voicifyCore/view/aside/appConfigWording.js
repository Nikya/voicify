/** L'application de configuration */
var app = angular.module('appConfigWording', ['ngAnimate']);

/******************************************/
/** Controleur Principale */
app.controller('configWordingCtrl', function ($scope, $http) {

	$scope.consoleDebug = true;
	$scope.console = "";
	$scope.voicekeyList;
	$scope.newVoicekey="";

	/** Récupération des données distantes */
	$http({
		method: 'GET',
		url: 'control.php?action=get_voicekey'
	}).then(function successCallback(response) {
		$scope.voicekeyList = response.data;
		//$scope.console = $scope.voicekeyList;
	}, function errorCallback(response) {
		if (response===undefined || response.config===undefined)
			$scope.console = "Fail get Json voicekeyList";
		else
			$scope.console = '#' + response.status + " : " + response.statusText + " >> " + response.config.url;
	});

	/** Fonction d'ajout d'un voicekey */
	$scope.addVoicekey = function() {
		if ($scope.newVoicekey.trim()) {
			$scope.newVoicekey = $scope.newVoicekey.replace(/[^A-Z0-9]/gi, '');
			if (!$scope.voicekeyList.hasOwnProperty($scope.newVoicekey)) {
				$scope.voicekeyList[$scope.newVoicekey] = [];
				$scope.newVoicekey = '';
				//$scope.trace(0, $scope.voicekeyList);
				location.hash = "rowsfooter";
			} else
				$scope.trace(2, $scope.newVoicekey + " existe déja !");
		}
	};

	/** Fonction d'ajout d'un VK */
	$scope.editVk = function(voicekey, i) {
		$scope.$broadcast('editVkEvent', {voicekey:voicekey, i:i});
	};

	/** Fonction de supression d'un Vk complet */
	$scope.deleteVk = function(voicekey) {
		delete $scope.voicekeyList[voicekey];
	};

	/** Fonction d'édition d'un Vk */
	$scope.editVk = function(voicekey) {
		$scope.$broadcast('editVkEvent', voicekey);
	};

	/** Fonction d'edition d'un texte paramétré */
	$scope.editPtext = function(voicekey, i) {
		$scope.$broadcast('editPtextEvent', {voicekey:voicekey, i:i});
	};

	/** Fonction de supression d'un text paramétré */
	$scope.deletePtext = function(voicekey, i) {
		$scope.voicekeyList[voicekey].splice(i, 1);
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

		var pText = $scope.voicekeyList[args.voicekey][pTextIndex];

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
			var pText = $scope.voicekeyList[$scope.edtVoicekey][pTextIndex];
			pText.text=$scope.edtText;
			pText.frequency=$scope.edtFrequency;
			$scope.editTextOn = false;
			location.hash = "vk_"+$scope.edtVoicekey;
		} else if (editTextMode=="ADD") {
			var pText = {
				text : $scope.edtText,
				frequency : $scope.edtFrequency
			};

			$scope.voicekeyList[$scope.edtVoicekey].push(pText);
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
	$scope.$on('editVkEvent', function (event, voicekey) {
		oldVoicekey = voicekey;
		$scope.edtVoicekey = voicekey;
		$scope.editVkOn = true;
	});

	/** Annuler l'edition */
	$scope.cancel = function() {
		$scope.editVkOn = false;
		location.hash = "vk_"+oldVoicekey;
	};

	/** Valider l'edition */
	$scope.valid = function() {
		if($scope.voicekeyList.hasOwnProperty(oldVoicekey)) {
			$scope.voicekeyList[$scope.edtVoicekey] = $scope.voicekeyList[oldVoicekey];
			delete $scope.voicekeyList[oldVoicekey];
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
