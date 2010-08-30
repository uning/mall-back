<?php
require_once ('../../config.php');

$pid = $_GET ['pid'];
if (! $pid) {
	$pid = $_COOKIE ['user_name'];
		if (! $pid) {
			$pid = 'test' . rand ( 1, 1000 );
		}
}
setcookie ( 'user_name', $pid, 0, '/' );

$platform = $_GET ['platform'];
if (! $platform) {
	$platform = $_COOKIE ['platform'];
		if (! $platform) {
			$platform = 'test';
		}
}
setcookie ( 'platform', $platform, 0, '/' );

?>
<html xmlns="http://www.w3.org/1999/xhtml"
xmlns:xn="http://www.renren.com/2009/xnml">
<head>
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>活力商城</title>

<style>
body {
margin: 0px;
	font-family: Lucida Grande, Verdana, Arial, Helvetica, sans-serif;
	font-size: 75%;
width: 100%;
}
</style>
</head>
<body>
<div class="flashContainer"
style="background: black; text-align: center">
<div id="flashDivId"><!-- alternative content here.... -->
<p>To view this page ensure that Adobe Flash Player version 10.0.0 or
greater is installed. "Music: Kevin MacLeod".</p>
<script type="text/javascript"> 
var pageHost = ((document.location.protocol == "https:") ? "https://" :	"http://"); 
document.write("<a href='http://www.adobe.com/go/getflashplayer'><img src='" 
		+ pageHost + "www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a>" ); 
</script></div>
</div>




</body>
</html>

<script type="text/javascript" src="../js/loader.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
<script type="text/javascript">

	//For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection. --> 
	var swfVersionStr = "10.0.0";
	swfVersionStr = "0.0.0";
	//<!-- To use express install, set to playerProductInstall.swf, otherwise the empty string. -->
	var xiSwfUrlStr = "playerProductInstall.swf";

	//get query to it
	var flashvars = {};
	var hash;
	flashvars.platform_id = '<?php echo  $pid;?>';//flashvars.fb_sig_user;
	flashvars.pconf = 'o_0_mall_config.xml';
	flashvars.languagetype = "0";
	//flashvars.platform = "facebook";
	flashvars.platform = "dev"
	var flash_width = 760;
	var flash_height = 700;
	var params = {};
	params.quality = "high";
	params.bgcolor = "#F0F8FF";
	params.allowscriptaccess = "always";  // must be always since html is different domain from swf :(
	params.allowfullscreen = "true";
	params.wmode = "opaque";
	params.flashvars = flashvars;
	var attributes = {};
	attributes.id = "flash_run_id";
	attributes.name = "SuperMall";
	attributes.align = "t";
	attributes.salign = "tl";
	/*flashDivId*/
	swfobject.embedSWF(
		"MallLoader.swf?v=", "flashDivId", 

		flash_width, flash_height, 
		swfVersionStr, xiSwfUrlStr, 
		flashvars, params, attributes);


</script>



