<?php
	$incPath = CoreUtils::PATH_MODULE.$module.'/'.$subModule.'.php';

	function displayConfFile($confFilePath) {
		$c = print_r(JsonUtils::jFile2Array($confFilePath), true);
		//$c print_r(JsonUtils::jFile2Array(), true)

		echo "<p>Configuration screen not implemented !</p><p>Please manualy edit the file : <strong>$confFilePath</strong></p> <pre>$c</pre>";
	}
?>

<div class="row" style="padding:2%;">
	<!-- LEFT -->
	<div class="col-sm-6">
		<!-- CONSOLE -->
		<?php include("./core/view/__console.php"); ?>
	</div>

	<!-- RIGHT -->
	<div class="col-sm-6">
		<!-- CONFIG ACTION -->
		<div class="ccc">
			<h3>
				<?php echo $subTitle . ' ' . $target ?>
				<em><?php echo $subDesc ?></em>
			</h3>
			<div class="content" style="text-align:center">
				<button disabled style="margin-right:10%; width:30%" type="button" class="btn btn-default navbar-btn btn-success" title="Record all changes made to this page" ng-click="save()">Save <div class="miniBtHelper">all changes</div></button>
				<button disabled style="width:30%" type="button" class="btn btn-default navbar-btn btn-danger" title="Abandon all changes made on this page" onclick="location.reload();">Cancel <div class="miniBtHelper">all changes</div></button>
			</div>
		</div>
	</div>
</div>

<div class="row" style="padding:2%;">
	<div class="col-sm-12">
		<div class="ccc">
			<?php include($incPath) ?>
		</div>
	</div>
</div>

<div class="row" style="padding:2%;">
	<div class="col-sm-12">
		<?php include("./core/view/__readme.php"); ?>
	</div>
</div>
