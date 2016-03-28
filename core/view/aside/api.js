// To call the PHP API whith Javascript (Jquery)

var apiUrl = 'api.php';

$(document).ready(function() {

	// PLAY VOICEKEY
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
				$('#consolePlay').html(JSON.stringify(response, null, 2));
				$('#calledUrl').html( (currentUrl+'/'+this.url).replace(/vars%5B%5D/g, 'vars[]') );
				$('.phrase').html(response.phrase);
				$('#wait').hide();
			},
			error: function(xhr, ajaxOptions, thrownError){
				$('#consolePlay').html(xhr.status + " : " + thrownError);
				$('#calledUrl').html( (currentUrl+'/'+this.url).replace(/vars%5B%5D/g, 'vars[]') );
				$('#wait').hide();
			}
		});
	});

	$('#wait').hide();
});
