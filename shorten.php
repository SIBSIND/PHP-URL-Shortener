<?php
/*
 * First authored by Brian Cray
 * License: http://creativecommons.org/licenses/by/3.0/
 * Contact the author at http://briancray.com/
 */
 
ini_set('display_errors', 0);

$url_to_shorten = get_magic_quotes_gpc() ? stripslashes(trim($_REQUEST['longurl'])) : trim($_REQUEST['longurl']);

if(!empty($url_to_shorten) && preg_match('|^https?://|', $url_to_shorten) && filter_var($url_to_shorten, FILTER_VALIDATE_URL) !== FALSE)
{
	require('config.php');

	// check if the client IP is allowed to shorten
	if($_SERVER['REMOTE_ADDR'] != LIMIT_TO_IP)
	{
		die('You are not allowed to shorten URLs with this service.');
	}
	
	// check if the URL is valid
	if(CHECK_URL)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_to_shorten);
		curl_setopt($ch, CURLOPT_NOBODY, TRUE);
		curl_setopt($ch,  CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($ch);
		if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == '404')
		{
			die('Not a valid URL');
		}
		curl_close($ch);
	}
	
	// check if the URL has already been shortened
	$results = mysql_query('SELECT id FROM ' . DB_TABLE. ' WHERE long_url="' . mysql_real_escape_string($url_to_shorten) . '"'); 
	if (mysql_num_rows($results) != 0) {
		// URL has already been shortened
		$already_shortened = mysql_result($results, 0, 0);
		$shortened_url = getShortenedURLFromID($already_shortened);
	}
	else
	{
		// URL not in database, insert
		// Generate a short URL
		$short_url =  substr(base_convert(md5($url_to_shorten),16, 36),0,SHORT_LENGTH);
		$long_url = mysql_real_escape_string($url_to_shorten);	
		$remote_addr = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
		mysql_query('LOCK TABLES ' . DB_TABLE . ' WRITE;');
		$result = mysql_query('INSERT INTO ' . DB_TABLE . ' (long_url, short_url, created, creator) VALUES ("' . $long_url . '", "' . $short_url .  '", "' . time() . '", "' . $remote_addr . '")');
		if (!$result) {
			die("Insert into DB failed");
		}
		$shortened_url = getShortenedURLFromID(mysql_insert_id());
		mysql_query('UNLOCK TABLES');
	}
	echo BASE_HREF . $shortened_url;
}

function getShortenedURLFromID ($integer)
{
	$shorturl = mysql_result(mysql_query('SELECT short_url FROM ' . DB_TABLE. ' WHERE id=' . mysql_real_escape_string($integer) ),0,0);
	return $shorturl;
}
?>
