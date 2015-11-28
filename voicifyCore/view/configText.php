<form>
	<fieldset class="form-group">
		<label for="exampleInputEmail1">Email address</label>
		<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
		<small class="text-muted">We'll never share your email with anyone else.</small>
	</fieldset>
	<button type="submit" class="btn btn-primary">Submit</button>
</form>

<pre class="console"><?php
	$fContent = file_get_contents("voicifyCore/config/text.json");
	$jDecode = json_decode($fContent);

	switch (json_last_error()) {
		case JSON_ERROR_NONE:
			echo ' - Aucune erreur';
		break;
		case JSON_ERROR_DEPTH:
			echo ' - Profondeur maximale atteinte';
		break;
		case JSON_ERROR_STATE_MISMATCH:
			echo ' - Inadéquation des modes ou underflow';
		break;
		case JSON_ERROR_CTRL_CHAR:
			echo ' - Erreur lors du contrôle des caractères';
		break;
		case JSON_ERROR_SYNTAX:
			echo ' - Erreur de syntaxe ; JSON malformé';
		break;
		case JSON_ERROR_UTF8:
			echo ' - Caractères UTF-8 malformés, probablement une erreur d\'encodage';
		break;
		default:
			echo ' - Erreur inconnue';
		break;
	}

	print_r($jDecode);

?></pre>
