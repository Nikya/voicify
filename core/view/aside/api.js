// To call the PHP API whith Javascript (Jquery)

var apiUrl = 'api.php';

$(document).ready(function() {

	// PLAY VOICEKEY7
	$('#playVoicekey').on('submit', function(e) {
		$('#wait').show();
		$('#consolePlay').html('. . .');

		e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire
		var $this = $(this);

		$.ajax({
			url: apiUrl,
			type: $this.method,
			data: $this.serialize(),
			dataType: 'json', // JSON
			success: function(response, textStatus, jqXHR){
				var rawUrl = currentUrl+'/'+this.url;
				$('#consolePlay').html(JSON.stringify(response, null, 2) + "\n\nurl: " + rawUrl);
				$('#calledUrl').html(hightlightUrl(rawUrl));
				$('.phrase').html(response.text);
				$('#wait').hide();
			},
			error: function(xhr, ajaxOptions, thrownError){
				var rawUrl = currentUrl+'/'+this.url;
				$('#consolePlay').html(xhr.status + " : " + thrownError);
				$('#calledUrl').html(rawUrl);
				$('#wait').hide();
			}
		});
	});

	$('#wait').hide();
});

/** Hightlight the differnets parts of the called URL.
*
* Available css class in #calledUrl : base, target, param, value
*/
function hightlightUrl(rawUrl) {
	var clearUrl = '';
	var rawUrl = rawUrl.replace(/%5B%5D/g, '[]');
	var _urlBase = rawUrl.split(apiUrl+"?")[0];
	var _urlParam = rawUrl.split(apiUrl+"?")[1];

	// Base
	clearUrl += '<span class="base">'+_urlBase+'</span>';
	// Taget
	clearUrl += '<span class="target">'+apiUrl+'</span>?';

	// Each param-value
	_urlParam.split("&").forEach(function(paramPair){
		paramArray = paramPair.split('=');
		// There is a value ?
		if (paramArray[1].length>0)
			clearUrl += '<span class="param">'+paramArray[0]+'</span>=<span class="value">'+paramArray[1]+'</span>&';
	});

	//var span = document.createElement('span');
	//span.innerHTML = _urlBase;
	//span.className = "base";
	//clearUrl.innerHTML += span;


	//( (currentUrl+'/'+this.url).replace(/vars%5B%5D/g, 'vars[]') );
	return clearUrl;
}
