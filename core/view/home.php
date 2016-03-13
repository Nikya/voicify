<pre class="console"><?php

	# Load Lib
	spl_autoload_register(function($class){
		require './core/common/php-markdown/'.preg_replace('{\\\\|_(?!.*\\\\)}', DIRECTORY_SEPARATOR, ltrim($class, '\\')).'.php';
	});
	use \Michelf\Markdown;

	# Read file and pass content through the Markdown parser
	$readmeMd = file_get_contents('README.md');
	$readmeHtml = Markdown::defaultTransform($readmeMd);
?></pre>

<section id="readme">
	<?php echo $readmeHtml; ?>
</section
