<?php
require_once('../config.php');

//
$pid = $_REQUEST['xn_sig_user'];
$gflg = $_REQUEST['glink'];
if($gflg){
	$ts = TT::TTWeb();
        $data = $ts->getbyid($gflg);
	$bids = $data['rfids'];
	if(strstr($bids,$pid)){
	}else{//给玩家礼物
		$flag = $data['gift_id'];
                $fconf = ItemConfig::get($flag);//取礼物信息	
		$tu = TT::TTUser($pid);
		//	
		$bids .=','.$pid;
	}
	$ts->puto($data);
 
			
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xn="http://www.renren.com/2009/xnml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php //include FB_CURR.'/cs/check_connect_redirect.php';?>
<link rel="shortcut icon" href="<?php echo RenrenConfig::$resource_urlp;?>images/favicon.ico" type="image/x-icon" />
<script type="text/javascript">
var a='<?php echo $_REQUEST['a']; ?>';
</script>
<script src="<?php echo RenrenConfig::$resource_urlp;?>js/jquery-1.4.2.min.js"></script>
<script src="<?php echo RenrenConfig::$resource_urlp;?>js/loader.js"></script>
<script src="<?php echo RenrenConfig::$resource_urlp;?>js/jsflash.js"></script>
<script type="text/javascript"  src="http://static.connect.renren.com/js/v1.0/FeatureLoader.jsp"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"> </script>
</head>
<style>
body {
margin: 0px;
	font-family: Lucida Grande, Verdana, Arial, Helvetica, sans-serif;
	font-size: 75%;
width: 100%;
}
</style>
<body >

<div class="flashContainer"
style="background: black; text-align: center">
<div id="flashapp"><!-- alternative content here.... -->
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
			"../static/flash/MallLoader.swf?v=", "flashapp", 
			flash_width, flash_height, 
			swfVersionStr, xiSwfUrlStr, 
			flashvars, params, attributes);
	//  alert('end install swf');
	
	
} 
</script>
<script type="text/javascript">
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

