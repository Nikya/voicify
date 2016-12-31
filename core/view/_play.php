<?php
	$readmeHtml = CoreUtils::mdParse('README.md');
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
			<h3><?php echo $target ?></h3>
			<div class="content">
				Content <?php echo $target . ' &gt; ' . $module ?>
			</div>
		</div>
	</div>
</div>
