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
<script type="text/javascript">

PL.js(['http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js'],function(){
		//For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection. --> 
		var swfVersionStr = "10.0.0";
		swfVersionStr = "0.0.0";
		//<!-- To use express install, set to playerProductInstall.swf, otherwise the empty string. -->
		var xiSwfUrlStr = "playerProductInstall.swf";

		//get query to it
		var flashvars = {};
		var hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++)
		{
		hash = hashes[i].split('=');
		flashvars[hash[0]] = hash[1];
		console.log('query:'+hash[0]+'='+hash[1]);
		}
		flashvars.platform_id = '<?php echo  $pid;?>';//flashvars.fb_sig_user;
		flashvars.pconf = '../../test_config.php';
		flashvars.languagetype = "0";
//flashvars.platform = "facebook";
flashvars.platform = "<?php echo  $platform;?>";
flashvars.platform = 'dev';
var flash_width = 760+200;
var flash_height = 700;
var params = {};
params.quality = "high";
params.bgcolor = "#F0F8FF";
params.allowscriptaccess = "always";  // must be always since html is different domain from swf :(
params.allowfullscreen = "true";
params.wmode = "opaque";
flashvars.gaIsDebug = 0;
flashvars.gaTrackKeys = 'UA-11480477-8';
flashvars.STAGE_WIDTH = flash_width;
//params.wmode = "window";//chinese input
params.flashvars = flashvars;
var attributes = {};
attributes.id = "flash_run_id";
attributes.name = "SuperMall";
attributes.align = "t";
attributes.salign = "tl";
/*flashDivId*/
swfobject.embedSWF(
		"/work/mall/Venus/to-company/MallLoader.swf?v=", "flashDivId", 

		flash_width, flash_height, 
		swfVersionStr, xiSwfUrlStr, 
		flashvars, params, attributes);

swfobject.createCSS("flashContainer", "display:block;text-align:left;");
});
var config = {
     //fbd : 1,//init fb debug? 
      logcb : function(r){//log init callback
	      console.log('log callback' +  window.location.href);
      },
after_fbinit : function(){//before FB.init callback
		       console.log('in loader after_fbinit');
			       PF.get_pid();
			       PF.set_page_ok();
			       PF.get_friends();

	       },
before_fbinit : function(){//after FB.init callback
			console.log('in loader before_fbinit');    
		},
cb:function(){//after config callback
	   PL.js(['jsflash.js']);
   }
}

PL.init('../js/config.js',config);
</script>



