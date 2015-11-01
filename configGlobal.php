<?php
	// Affichage des erreurs
	ini_set('display_errors',1);
	error_reporting(E_ALL);
?>

<h1>Global configuration</h1>

<a href="index.php">&lt;&lt;Return</a>
<hr/>

<form>
	param1 : <input type="text" name="">
	</br>
	</br>
	<input type="submit" value="Save">
</form>

<!----------------------------------------->
<hr/>
<pre><?php
	//print_r(json_decode(file_get_contents("voicify/config/global.ini")));
	print_r(parse_ini_file("voicify/config/global.ini", true));
?></pre>
