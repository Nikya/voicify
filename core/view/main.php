<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="<?php echo $desc . ' ' . $subDesc ?>">
	<meta name="author" content="Nikya">
	<link rel="icon" href="core/view/aside/favicon.png">

	<title>Voicify : <?php echo $title ?></title>

	<!-- Bootstrap core CSS -->
	<link href="core/view/aside/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="core/view/aside/readme.css" rel="stylesheet">
	<link href="core/view/aside/main.css" rel="stylesheet">

	<!-- Chargement des scripts -->
	<script src="core/view/js/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="core/view/js/jquery.min.js"><\/script>')</script>
	<script src="core/view/aside/bootstrap/js/bootstrap.js"></script>
	<script src="core/view/js/angular.min.js"></script>
	<script src="core/view/js/angular-animate.min.js"></script>
	<script src="core/view/js/main.js"></script>

	<!-- Liaison PHP/Javascript -->
	<script>
		var currentUrl = '<?php echo "http://$_SERVER[HTTP_HOST]/voicify" ?>';
	</script>
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href=".">Voicify</div>
		<div id="navbar" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						Play<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<?php echo $playMenuHtml ?>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						Config<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<?php echo $configMenuHtml ?>
					</ul>
				</li>
			</ul>
		</div><!--/.nav-collapse -->
		</div>
	</nav>

	<div class="starter-template">
<!-- Content ------------------------------->
		<h2>
			<?php echo $title ?>
			<em><?php echo $desc ?></em>
		</h2>

		<?php include("./core/view/_$target.php"); ?>

<!-- Fin Content ------------------------------->
	</div>
</body>
</html>