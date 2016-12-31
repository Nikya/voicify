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
			<a class="navbar-brand" href=".">Home Voicify</a>
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

	<div class="starter-template">
<!-- Content ------------------------------->
		<h2>
			<?php echo $title ?>
			<em><?php echo $desc ?></em>
		</h2>

		<div class="row" style="padding:2%;">
			<!-- LEFT -->
			<div class="col-sm-6">
				<!-- CONSOLE -->
				<div class="ccc">
					<h3>Console</h3>
					<input type=text id="calledUrl" value="<?php echo urldecode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>"/>
					<div id="indicator" class="ok">&nbsp;</div>
					<pre class="console"><?php CoreUtils::consolePrint(); ?></pre>
				</div>

				<!-- Say -->
				<div class="ccc">
					<h3>Saying</h3>
					<blockquote class="content" id="Saying">
						I have nothing to say !
					</blockquote >
				</div>

				<!-- README -->
				<div class="ccc">
					<h3>Read Me</h3>
					<div class="content markdown-body">
						<?php echo $readme ?>
					</div>
				</div>
			</div>

			<!-- RIGHT -->
			<div class="col-sm-6">
				<!-- ACTION -->
				<div class="ccc">
					<h3><?php echo $target ?></h3>
					<div class="content">
						Content <?php echo $target . ' &gt; ' . $module ?>
					</div>
				</div>
			</div>
		</div>



<!-- Fin Content ------------------------------->
	</div>
</body>
</html>
