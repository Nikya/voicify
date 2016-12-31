<!-- CONSOLE -->
<div class="ccc">
	<h3>Console</h3>
	<input type=text id="calledUrl" value="<?php echo urldecode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>"/>
	<div id="indicator" class="<?php echo CoreUtils::consoleIndicator(); ?>">&nbsp;</div>
	<ul class="console"><?php CoreUtils::consolePrint(); ?></ul>
</div>
