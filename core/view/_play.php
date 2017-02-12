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
				<!-- Play Form -->
				<form id="playForm" method="get" action="api.php">
					<input type="hidden" name="play" value="<?php echo $fullModule ?>">
					<?php include($incPath) ?>

					<hr id="formBottom" />

					<button type="submit" class="btn btn-primary">Play</button>
					<fieldset class="checkbox">
						<label for="debugMode"><input type="checkbox" name="debugMode" value="1" id="debugMode">Debug mode</label>
						<small class="text-muted">: To display more details in the speak result</small>
					</fieldset>
				</form>

			</div>
		</div>
	</div>
</div>
