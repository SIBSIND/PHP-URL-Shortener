<?php

require('config.php');

$shorturl = substr($_GET['url'],0,-1);
$results = mysql_query('SELECT * FROM ' . DB_TABLE . ' WHERE short_url="' . mysql_real_escape_string($shorturl) . '"');
if ((!$results) || (mysql_num_rows($results) == 0)) {
	header('HTTP/1.1 404 Not Found');
	die("404 - This short URL does not exist. ");
} else {
	$id = mysql_result($results, 0, 0);
	$longurl = mysql_result($results, 0, 1);
	$created = mysql_result($results, 0, 3);
	$creator = mysql_result($results, 0, 4);
	$clicks = mysql_result($results, 0, 5);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>URL shortener - Stats for <?php echo $shorturl ?></title>
<meta name="robots" content="noindex, nofollow">
<style type="text/css">
#longurl { font-size:20px; padding:10px ; width: 600px}
body { font-family: "Lucida Grande" }
li { padding: 4px; }
</style>
</head>
<body>
<h1>URL Shortener - Stats for <?php echo $shorturl ?></h1>
<h3><?php echo $clicks ?> clicks</h3>
<ul>
<li>Original URL: <a href="<?php echo $longurl ?>"><?php echo $longurl ?></a> </li>
<li>Created on <?php echo date("r", $created) ?> by <?php echo $creator ?></li>
</ul>
</body>
</html>
