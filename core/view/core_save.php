<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="The Home voicify start page">
	<meta name="author" content="Nikya">
	<link rel="icon" href="core/view/aside/favicon.ico">

	<title>Home Voicify</title>

	<!-- Bootstrap core CSS -->
	<link href="core/view/aside/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="core/view/aside/main.css" rel="stylesheet">
	<link href="core/view/aside/animations.css" rel="stylesheet">
	<link href="core/view/aside/readme.css" rel="stylesheet">

	<!-- Chargement des scripts -->
	<script src="core/view/aside/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="core/view/aside/jquery.min.js"><\/script>')</script>
	<script src="core/view/aside/bootstrap/js/bootstrap.min.js"></script>
	<script src="core/view/aside/angular.min.js"></script>
	<script src="core/view/aside/angular-animate.min.js"></script>
	<script src="core/view/aside/main.js"></script>

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
			<a class="navbar-brand" href="/voicify">Home Voicify</a>
		</div>
		<div id="navbar" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						Play<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="?play=voicekey">Voicekey</a></li>
						<li><a href="?play=clock">Clock</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						Config<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="?config=wording">Config:wording</a></li>
						<li><a href="?config=global">Config:Global</a></li>
					</ul>
				</li>
			</ul>
		</div><!--/.nav-collapse -->
		</div>
	</nav>

	<div class="starter-template" id="content">
<!-- Content ------------------------------->
		<pre class="console"><?php
			// Affichage des erreurs PHP
			ini_set('display_errors',1);
			error_reporting(E_ALL);

			require_once('core/init.php');

			$title = '';
			$target = 'core/view/home.php';

			if ( (isset($_GET['play']) and $_GET['play']=="voicekey") ) {
				$title = 'Play Voicekey';
				$target = 'core/view/playVoicekey.php';
			} elseif ( (isset($_GET['play']) and $_GET['play']=="clock") ) {
				$title = 'Play the Speaking Clock';
				$target = 'core/view/playClock.php';
			} elseif (isset($_GET['config']) and $_GET['config']=="wording") {
				$title = 'Wordings configuration';
				$target = 'core/view/configWording.html';
			} elseif (isset($_GET['config']) and $_GET['config']=="global") {
				$title = 'Global configuration';
				$target = 'core/view/configGlobal.php';
			}
		?></pre>

		<h2><?php echo $title ?></h2>
		<div class="container"><?php include($target) ?></div>

<!-- Fin Content ------------------------------->
	</div>
</body>
</html>
