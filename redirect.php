<?php
/*
 * First authored by Brian Cray
 * License: http://creativecommons.org/licenses/by/3.0/
 * Contact the author at http://briancray.com/
 */

ini_set('display_errors', 0);

$url = $_GET['url'];

if(!preg_match('|^[0-9a-zA-Z]{1,10}$|', $url))
{
	die('That is not a valid short url');
}

require('config.php');

if(CACHE)
{
	if(file_exists(CACHE_DIR . $url)) { 
		$long_url = file_get_contents(CACHE_DIR . $url);
	}
	if(empty($long_url) || !preg_match('|^https?://|', $long_url))
	{
		$long_url = getLongURL($url);
		@mkdir(CACHE_DIR, 0777);
		if (is_writeable(CACHE_DIR) && $handle = fopen(CACHE_DIR . $url, 'w+')) {
			fwrite($handle, $long_url);
			fclose($handle);
		} else {
			trigger_error("Your cache directory, " . CACHE_DIR . " does not exist or is not writeable. continuing anyway, but consider setting CACHE to false.", E_USER_WARNING);
		}
	}
}
else
{
	$long_url = getLongURL($url);
}

if(TRACK)
{
	mysql_query('UPDATE ' . DB_TABLE . ' SET referrals=referrals+1 WHERE short_url="' . mysql_real_escape_string($url) . '"');
}

header('HTTP/1.1 301 Moved Permanently');
header('Location: ' .  $long_url);
exit;

function getLongURL($shorturl) {
	$results = mysql_query('SELECT long_url FROM ' . DB_TABLE . ' WHERE short_url="' . mysql_real_escape_string($shorturl) . '"');
	if ((!$results) || (mysql_num_rows($results) == 0)) {
		header('HTTP/1.1 404 Not Found');
		die("404 - This short URL does not exist. ");
	} else {
		$long_url = mysql_result($results, 0, 0);
		return $long_url;
	}
}

?>
