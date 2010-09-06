<?php
require_once ('../config.php');

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
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"> </script>
<script src="<?php echo RenrenConfig::$resource_urlp;?>js/jsflash.js"></script>
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
<p>需要安装Adobe Flash Player version 10.0.0或者更新版本 "Music: Kevin MacLeod".</p>
<script type="text/javascript"> 
var pageHost = ((document.location.protocol == "https:") ? "https://" :	"http://"); 
document.write("<a href='http://www.adobe.com/go/getflashplayer'><img src='" 
		+ pageHost + "www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a>" ); 
</script></div>
</div>




</body>
</html>

<script type="text/javascript" src="../static/js/loader.js"></script>
<script type="text/javascript">


swf_install = false;
function install_swf(pid){

	  if(swf_install || !pid)
		   return ;
	   swf_install = true;
	
	  //alert('begin install swf');
	//For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection. --> 
		var swfVersionStr = "10.0.0";
	swfVersionStr = "0.0.0";
	//<!-- To use express install, set to playerProductInstall.swf, otherwise the empty string. -->
		var xiSwfUrlStr = "playerProductInstall.swf";

	//get query to it
	var flashvars = PL.query_json();
	flashvars.platform_id = pid;
	flashvars.pconf = '../static/flash/o_0_mall_config.xml';
	flashvars.languagetype = "0";
	flashvars.STAGE_WIDTH = "760";
	//flashvars.fb_sig_app_secret = "60d180ac578ce34093b3ce2d1d450f84";
	flashvars.platform = "renren";
	var flash_width = 760;
	var flash_height = 700;
	var params = {};
	params.quality = "high";
	params.bgcolor = "#F0F8FF";
	params.allowscriptaccess = "always";  // must be always since html is different domain from swf :(
	params.allowfullscreen = "true";
	params.wmode = "window";//opaque
	params.flashvars = flashvars;
	var attributes = {};
	attributes.id = "flash_run_id";
	attributes.name = "SuperMall";
	attributes.align = "t";
	attributes.salign = "tl";
	/*flashDivId*/
	//params.base = "http://127.0.0.1/work/mall/Venus/to-company/";
	swfobject.embedSWF(
			"../static/flash/MallLoader.swf?v=", "flashDivId", 
			flash_width, flash_height, 
			swfVersionStr, xiSwfUrlStr, 
			flashvars, params, attributes);
	//  alert('end install swf');
	
	
} 

pid = PL.conf('pid')||query_json.xn_sig_user;
pid && install_swf(pid);
var config = {
		useparent:false,
		//log:1,//init log? server can force debug, just for develop
		fb:1,//init fb?
		//fbd:1,//init fb debug? 
	logcb:function(r){//log init callback
		      console.log('index log callback' +  window.location.href);
	      },
	after_fbinit: function(){//before FB.init callback
	    	  console.log('index after_fbinit');
	    	   pid = PL.conf('pid')||query_json.xn_sig_user;
	  		   if(!pid){
	  			   var getpid = function(r){
	  				   pid = r.uid;
	  				   PL.conf('pid',pid);
	  				   console.log(pid);
	    			   install_swf(pid);
	  				   
	  			   }
	  			   XN.Main.get_sessionState().waitUntilReady(
	  					   function(){
	  						   XN.Main.apiClient.users_getLoggedInUser(getpid);
	  					   });
	  			   
	  		   }else{
	   			  install_swf(pid);
	  		   }
	  		   PF.set_page_ok();	  


	},
	before_fbinit:function(){//after FB.init callback
		    	
		      },
	cb:function(){//after config callback
		 
	   }
	}

	PL.init('../static/js/config.js',config);
</script>
<?php include FB_CURR.'/cs/gajs_init.php';?> 



