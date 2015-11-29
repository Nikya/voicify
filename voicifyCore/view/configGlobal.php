<form>
	<fieldset class="form-group">
		<label for="exampleInputEmail1">Email address</label>
		<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
		<small class="text-muted">We'll never share your email with anyone else.</small>
	</fieldset>
	<button type="submit" class="btn btn-primary">Submit</button>
</form>

<pre class="console"><?php
	echo "<p>File path : ".DIR_CONF_GLOBAL."</p><hr/>";

	//print_r(json_decode(file_get_contents("voicify/config/global.ini")));
	print_r(parse_ini_file("voicifyCore/config/global.ini", true));
?></pre>
