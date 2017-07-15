<div class="ccc">
	<h3>Logs</h3>
	<div class="content">
		<small class="text-muted">Log files path : <code><?php echo CoreUtils::PATH_TEMP ?></code></small>
		<br/>
		<p>Select a log to display : </p>
		<ul>
			<?php echo filesToLinkList() ?>
		<ul>
	</div>
</div>

<?php if(isset($_GET['fileId']) and !empty($_GET['fileId'])) { ?>

	<div class="ccc">
		<h3>File content</h3>
		<div class="content" style="padding:0">
			<table class="table table-condensed" id='logTable'>
				<thead>
					<tr>
						<th>Time</th>
						<th>Order</th>
						<th>lvl</th>
						<th>Tag</th>
						<th>Message</th>
						<th>Mixed</th>
					</tr>
				</thead>
				<tbody>
					<?php echo filesToLogTable($_GET['fileId']) ?>
				</tbody>
			</table>
		</div>
	</div>

<?php } ?>

<?php
/***************************************************************************
* To read create a log file list
*/
function filesToLinkList() {
	$ulHtml = '';
	$filePattern = '/log_(.*).csv/i';

	$tmpContent = scandir(CoreUtils::PATH_TEMP, SCANDIR_SORT_DESCENDING);

	if ($tmpContent===false) {
		Console::e('log.filesToLinkList', 'Fail to read the folder content', CoreUtils::PATH_TEMP);
		return '';
	}

	foreach ($tmpContent as $c) {
		if(preg_match($filePattern, $c, $matches)) {
			$fId = $matches[1];
			$ulHtml .= "<li><a href=\"?log&fileId=$fId\">$fId</a></li>";
		}
	}

	return $ulHtml;
}

/***************************************************************************
* To read and display a log file content
*/
function filesToLogTable($fId) {
	$fileName = CoreUtils::PATH_TEMP."log_$fId.csv";
	$tHtml = '';

	if (!file_exists($fileName)) {
		Console::e('log.filesToLogTable', 'Log file not exists.', $fileName);
		return '';
	}

	if ($file = fopen($fileName, "r")) {
		while(!feof($file)) {
			$line = trim(fgets($file));
			$eLine = explode(';', $line);

			// Good line
			if (count($eLine)>=5) {
				$mixed = count($eLine)>=6 ? $eLine[5] : '--';

				switch (trim($eLine[2])) {
					case 'I':
						$cssLvl = 'lvlok'; break;
					case 'D':
						$cssLvl = 'lvld'; break;
					case 'W':
						$cssLvl = 'lvlwarn'; break;
					case 'E':
						$cssLvl = 'lvlko'; break;
				}

				$tHtml = <<<EOR
					<tr>
						<td>{$eLine[0]}</td>
						<td>{$eLine[1]}</td>
						<td class='lvl $cssLvl'>{$eLine[2]}</td>
						<td>{$eLine[3]}</td>
						<td>{$eLine[4]}</td>
						<td>$mixed</td>
					</tr>$tHtml
EOR;
			}
			// Unknow format
			else {
				$tHtml = '<tr><td colspan="6">'.$line.'</td></tr>'.$tHtml;
			}
		}
		fclose($file);
	}

	return $tHtml;
}
