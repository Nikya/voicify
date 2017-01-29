<!-- CONSOLE -->
<div class="ccc">
	<h3>Console</h3>
	<input type=text id="calledUrl" value="<?php echo urldecode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>"/>
	<div id="indicator" class="<?php echo Console::Indicator(); ?>">&nbsp;</div>
	<ul class="console"><?php Console::toHtml(); ?></ul>
</div>
