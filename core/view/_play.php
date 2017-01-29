<?php
	$incPath = CoreUtils::PATH_MODULE.$module.'/'.$subModule.'.php';
?>

<div class="row" style="padding:2%;">
	<!-- LEFT -->
	<div class="col-sm-6">
		<!-- CONSOLE -->
		<?php include("./core/view/__console.php"); ?>

		<!-- Say -->
		<?php include("./core/view/__say.php"); ?>

		<!-- README -->
		<?php include("./core/view/__readme.php"); ?>
	</div>

	<!-- RIGHT -->
	<div class="col-sm-6">
		<!-- PLAY ACTION -->
		<div class="ccc">
			<h3>
				<?php echo $subTitle . ' ' . $target ?>
				<em><?php echo $subDesc ?></em>
			</h3>
			<div class="content">
				<?php include($incPath) ?>
			</div>
		</div>
	</div>
</div>
