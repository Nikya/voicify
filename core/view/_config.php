<?php
	$incPath = CoreUtils::PATH_MODULE.$module.'/'.$subModule.'.php';
	$incUPath = CoreUtils::PATH_MODULE.$module.'/Utils.php';
	if (file_exists($incUPath)) require_once($incUPath);

?>

<div class="ccc">
	<h3>
		<?php echo $subTitle . ' ' . $target ?>
		<em><?php echo $subDesc ?></em>
	</h3>
	<div class="content" style="text-align:center">
		<p><strong>Configuration screens are not available for the moment.</strong> (Upcoming feature)</p>
		<!--
		<button disabled style="margin-right:10%; width:30%" type="button" class="btn btn-default navbar-btn btn-success" title="Record all changes made to this page" ng-click="save()">Save <div class="miniBtHelper">all changes</div></button>
		<button disabled style="width:30%" type="button" class="btn btn-default navbar-btn btn-danger" title="Abandon all changes made on this page" onclick="location.reload();">Cancel <div class="miniBtHelper">all changes</div></button>
		-->
	</div>
</div>

<div class="ccc">
	<?php include($incPath) ?>
</div>
