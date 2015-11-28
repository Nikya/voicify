<?php
	// Affichage des erreurs PHP
	ini_set('display_errors',1);
	error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="The Home voicify start page">
	<meta name="author" content="Nikya">
	<link rel="icon" href="voicifyCore/view/favicon.ico">

	<title>Home Voicify</title>

	<!-- Bootstrap core CSS -->
	<link href="voicifyCore/view/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="voicifyCore/view/main.css" rel="stylesheet">
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
				<li><a href="/voicify?voicekey">Voicekey</a></li>
				<li><a href="/voicify/?config=text">Config:Text</a></li>
				<li><a href="/voicify/?config=global">Config:Global</a></li>
			</ul>
		</div><!--/.nav-collapse -->
		</div>
	</nav>

	<div class="starter-template" id="content">
<!-- Content ------------------------------->
		<?php if (isset($_GET['voicekey'])) { ?>
			<h2>Voicekey</h2>
			<div class="container"><?php include("voicifyCore/view/voicekey.php") ?></div>

		<?php } elseif (isset($_GET['config']) and $_GET['config']=="text") { ?>
			<h2>Texts configuration</h2>
			<div class="container"><?php include("voicifyCore/view/configText.php") ?></div>

		<?php } elseif (isset($_GET['config']) and $_GET['config']=="global") { ?>
			<h2>Global configuration</h2>
			<div class="container"><?php include("voicifyCore/view/configGlobal.php") ?></div>

		<?php } else { ?>
			<h1>Home Voicify</h1>
			<div class="container"><p class="lead">Your home can talk now!</p></div>
		<?php } ?>
<!-- Content ------------------------------->
	</div>

	<!-- Bootstrap core JavaScript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="voicifyCore/view/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="voicifyCore/view/jquery.min.js"><\/script>')</script>
	<script src="voicifyCore/view/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
