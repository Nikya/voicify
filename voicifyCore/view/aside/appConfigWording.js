var app = angular.module('appConfigWording', []);

app.controller('configWordingCtrl', function ($scope, $http) {

	$scope.consoleDebug = true;
	$scope.console = "";
	$scope.voicekeyList;
	$scope.newVoicekey="";

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

	$scope.addVoicekey = function() {
		if ($scope.newVoicekey.trim()) {
			$scope.newVoicekey = $scope.newVoicekey.replace(/[^A-Z0-9]/gi, '');
			if (!$scope.voicekeyList.hasOwnProperty($scope.newVoicekey)) {
				$scope.voicekeyList[$scope.newVoicekey] = [];
				//$scope.newVoicekey = '';
				$scope.trace(0, $scope.voicekeyList);
			} else
				$scope.trace(2, $scope.newVoicekey + " existe déja !");
		}
	};

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

app.filter('placeholderize', function($sce) {
	return function(value) {
		var fValue = value.replace(/({.+?})/g, "<span class=\"placeholder\">$1</span>");
		return $sce.trustAsHtml(fValue);
	};
});
