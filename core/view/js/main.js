// To call the PHP API whith Javascript (Jquery)



/***************************************************************************
* Document ready
*/
$(document).ready(function() {

	$('#calledUrl').click(openUrl);
	$('#indicator').removeClass('wait');
	$('#calledUrl').html(hightlightUrl($('#calledUrl').html()));

/*******************************************************************************
* Intercept form submission to redirect to Ajax Submit
*/
	$('#playForm').on('submit', function(e) {
		$('#indicator').removeClass().addClass('wait');
		$('#console').html('. . .');
		$('#say').html('. . .');

		e.preventDefault(); // Empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire
		var $this = $(this);

		$.ajax({
			url: $this.attr('action'),
			type: $this.method,
			data: $this.serialize(),
			dataType: 'json', // JSON
			success: function(response, textStatus, jqXHR){
				var rawUrl = baseUrl+this.url;
				$('#console').html(response.htmlConsole);
				$('#calledUrl').html(hightlightUrl(rawUrl));
				$('#say').html(response.say);
				$('#indicator').addClass(response.status).removeClass('wait');
			},
			error: function(xhr, ajaxOptions, thrownError){
				var rawUrl = baseUrl+'/'+this.url;
				$('#console').html(xhr.status + " : " + thrownError);
				$('#calledUrl').html(rawUrl);
				$('#indicator').addClass('ko').removeClass('wait');
			}
		});
	});
});


/*******************************************************************************
* Hightlight the differnets parts of the called URL.
*
* Available css class in #calledUrl : param, value
*/
function hightlightUrl(rawUrl) {
	rawUrl = rawUrl.replace(/%5B%5D/g, '[]');
	var _urlBase = rawUrl.split("?")[0];
	var _urlParam = rawUrl.split("?")[1];

	var clearUrl = _urlBase+'?';

	// Each param-value
	_urlParam.split("&").forEach(function(paramPair){
		paramArray = paramPair.split('=');
		// There is a value ?
		if (paramArray[1].length>0)
			clearUrl += '<span class="param">'+paramArray[0]+'</span>=<span class="value">'+paramArray[1]+'</span>&';
	});

	clearUrl = clearUrl.replace(/&+$/, "");
	return clearUrl;
}

/*******************************************************************************
* Open external API URL
*/
function openUrl () {
	window.open($('#calledUrl').text(), '_blank');

	return false;
}
