<!DOCTYPE html>
<html>
<head>
<title>URL shortener</title>
<meta name="robots" content="noindex, nofollow">
<style type="text/css">
#longurl { font-size:20px; padding:10px ; width: 600px}
body { font-family: "Lucida Grande" }
li { padding: 4px; }
</style>
</head>
<body>
<h1>URL Shortener</h1>
<form method="post" action="shorten.php" id="shortener">
<label for="longurl">URL to shorten</label><br /> <input type="text" name="longurl" id="longurl" value="<? echo $_GET['longurl'] ?>" ><br /> <input type="submit" id="shortenbutton" value="Shorten">
</form>
</form>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript">
$(function () {
	$('#shortener').submit(function () {
		$.ajax({data: {longurl: $('#longurl').val()}, url: 'shorten.php', complete: function (XMLHttpRequest, textStatus) {
			$('#statsbox').html('<li>' + $('#longurl').val() + ' shortened to ' + XMLHttpRequest.responseText + '</li>' + 
				'<li>Copy to clipboard using CMD/CTRL + C now</li>' + 
				'<li><a href="' + XMLHttpRequest.responseText + '+">View stats</a></li>');
			$('#shortenbutton').hide();
			$('#longurl').val(XMLHttpRequest.responseText);
			$('#longurl').focus();
			$('#longurl').select();
		}});
		return false;
	});
});
</script>
<br />
<div id="statsbox" name="statsbox"><div>
<br />
<br />
<h3>Bookmarklet</h3>
<?php
$name_parts = explode('.', $_SERVER['HTTP_HOST']);
$bookmark = $name_parts[0];
$bookmarklet = "<a href=\"javascript:void(location.href='http://" . $_SERVER['HTTP_HOST'] . "/?b=1&longurl='+encodeURIComponent(location.href))\">";
?>
<p>Drag <?php echo $bookmarklet ?>Shorten</a> to your bookmark toolbar to create a button that easily shortens URLs</p>
<h3>API</h3>
<p>Easily get a short URL by hitting http://<?php echo $_SERVER['HTTP_HOST'] ?>/shorten.php with the variable "longurl" as the URL-encoded URL you wish to shorten. </p>
<p>For example, in PHP:<br />
<pre>$shorturl = file_get_contents('http://<?php echo $_SERVER['HTTP_HOST'] ?>/shorten.php?longurl=' . urlencode($url));</pre></p>

<?php 

if ($_GET['b'] == 1) {
?>
<script type="text/javascript">
$.ajax({data: {longurl: $('#longurl').val()}, url: 'shorten.php', complete: function (XMLHttpRequest, textStatus) {
                        $('#statsbox').html('<li>' + $('#longurl').val() + ' shortened to ' + XMLHttpRequest.responseText + '</li>' + 
                                '<li>Copy to clipboard using CMD/CTRL + C now</li>' + 
                                '<li><a href="' + XMLHttpRequest.responseText + '+">View stats</a></li>');
                        $('#shortenbutton').hide();
                        $('#longurl').val(XMLHttpRequest.responseText);
                        $('#longurl').focus();
                        $('#longurl').select();
                }});
</script>
<?; 

}

?>
</body>
</html>
