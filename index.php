<?php
	// Affichage des erreurs PHP
	ini_set('display_errors',1);
	error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="The Home voicify start page">
	<meta name="author" content="Nikya">
	<link rel="icon" href="voicifyCore/view/aside/favicon.ico">

	<title>Home Voicify</title>

	<!-- Bootstrap core CSS -->
	<link href="voicifyCore/view/aside/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="voicifyCore/view/aside/main.css" rel="stylesheet">
	<link href="voicifyCore/view/aside/animations.css" rel="stylesheet">
	<link href="voicifyCore/view/aside/readme.css" rel="stylesheet">

	<!-- Chargement des scripts -->
	<script src="voicifyCore/view/aside/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="voicifyCore/view/aside/jquery.min.js"><\/script>')</script>
	<script src="voicifyCore/view/aside/bootstrap/js/bootstrap.min.js"></script>
	<script src="voicifyCore/view/aside/angular.min.js"></script>
	<script src="voicifyCore/view/aside/angular-animate.min.js"></script>
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
				<li><a href="/voicify?voicekey">Play</a></li>
				<li><a href="/voicify/?config=wording">Config:wording</a></li>
				<li><a href="/voicify/?config=global">Config:Global</a></li>
			</ul>
		</div><!--/.nav-collapse -->
		</div>
	</nav>

	<div class="starter-template" id="content">
<!-- Content ------------------------------->
		<pre class="console"><?php require_once('voicifyCore/loader.php') ?></pre>

		<?php if (isset($_GET['voicekey'])) { ?>
			<h2>Play</h2>
			<div class="container"><?php include("voicifyCore/view/play.php") ?></div>

		<?php } elseif (isset($_GET['config']) and $_GET['config']=="wording") { ?>
			<h2>Wordings configuration</h2>
			<div class="container"><?php include("voicifyCore/view/configWording.html") ?></div>

		<?php } elseif (isset($_GET['config']) and $_GET['config']=="global") { ?>
			<h2>Global configuration</h2>
			<div class="container"><?php include("voicifyCore/view/configGlobal.php") ?></div>

		<?php } else { ?>
			<div class="container"><?php include("voicifyCore/view/home.php") ?></div>
		<?php } ?>
<!-- Fin Content ------------------------------->
	</div>
</body>
</html>
