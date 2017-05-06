// To call the PHP API whith Javascript (Jquery)



/***************************************************************************
* Document ready
*/
$(document).ready(function() {

	$('#calledUrl').click(openUrl);
	$('#calledUrl').html(hightlightUrl($('#calledUrl').html()));
	$('#console').html(fConsole(phpConsole, true));


	$('#indicator').removeClass('wait');

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
				$('#console').html(fConsole(response.console));
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
* Format a Json console Data to HTML elements
*/
function fConsole(consoleEntriesMixed, isString=false) {

	if(!isString)
		consoleEntries = consoleEntriesMixed;
	else {
		try {
			consoleEntries = JSON.parse(consoleEntriesMixed);
		} catch(e) {
			return consoleEntriesMixed + ' <br/><br/><strong>'+e.message+'</strong>';
		}
	}


	var out = '';
	var lvl2Indicator = {
		"D":	"d",
		"I":	"ok",
		"W":	"warn",
		"E":	"ko"
	};

	var eConsole = document.createElement("UL");

	for (var i = 0; i < consoleEntries.length; i++) {
		var cEntry = consoleEntries[i];
		var eEntry = document.createElement("LI");

		// Tag + Lvl
		var txtTag = document.createTextNode(cEntry.lvl + '| ' +cEntry.tag);
		var eTag = document.createElement("SPAN")
		eTag.className = 'lvl'+lvl2Indicator[cEntry.lvl]+ ' lvl';
		eTag.appendChild(txtTag);
		eEntry.appendChild(eTag);

		// Message
		var txtMsg = document.createTextNode(cEntry.msg);
		var eMsg = document.createElement("P")
		eMsg.appendChild(txtMsg);
		eEntry.appendChild(eMsg);

		// Mixed
		if (cEntry.mixed!=null) {
			var txtMixed = document.createTextNode(cEntry.mixed);
			var eMixed = document.createElement("PRE");
			eMixed.appendChild(txtMixed);
			eEntry.appendChild(eMixed);
		}

		// Console full
		eConsole.appendChild(eEntry);
	}

	return eConsole;
}

/*******************************************************************************
* Open external API URL
*/
function openUrl () {
	window.open($('#calledUrl').text(), '_blank');

	return false;
}
