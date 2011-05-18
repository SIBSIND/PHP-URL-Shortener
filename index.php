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
<label for="longurl">URL to shorten</label><br /> <input type="text" name="longurl" id="longurl" ><br /> <input type="submit" id="shortenbutton" value="Shorten">
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
</body>
</html>
