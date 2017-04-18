<!-- CONSOLE -->
<div class="ccc">
	<h3>Console</h3>
	<div id="calledUrl"><?php echo $baseApiUrl; ?></div>
	<div id="indicator" class="<?php echo Console::getInstance()->indicator(); ?> wait">&nbsp;</div>
	<ul id="console"><?php echo Console::getInstance()->toHtml(); ?></ul>
</div>
