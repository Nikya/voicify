<form>
	<fieldset class="form-group">
		<label for="exampleInputEmail1">Email address</label>
		<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
		<small class="text-muted">We'll never share your email with anyone else.</small>
	</fieldset>
	<button type="submit" class="btn btn-primary">Submit</button>
</form>

<pre class="console"><?php
	echo "<p>File path : ".CONF_FILE_GLOBAL."</p><hr/>";

	print_r(json_decode(file_get_contents(CONF_FILE_GLOBAL)));
?></pre>
