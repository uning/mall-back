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
<link rel="stylesheet"href="<?php echo RenrenConfig::$resource_urlp;?>css/main.css?2" />
<link rel="shortcut icon" href="<?php echo RenrenConfig::$resource_urlp;?>images/favicon.ico" type="image/x-icon" />
<script type="text/javascript">
var a='<?php echo $_REQUEST['a']; ?>';
</script>
<script src="<?php echo RenrenConfig::$resource_urlp;?>js/jquery-1.4.2.min.js"></script>
<script src="<?php echo RenrenConfig::$resource_urlp;?>js/loader.js"></script>
<script src="<?php echo RenrenConfig::$resource_urlp;?>js/stat/common.js"></script>
<script src="<?php echo RenrenConfig::$resource_urlp;?>js/jsflash.js"></script>
<script src="<?php echo RenrenConfig::$resource_urlp;?>js/pageUtil.js?v=12"></script>
<script type="text/javascript"  src="http://static.connect.renren.com/js/v1.0/FeatureLoader.jsp"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"> </script>
<script type="text/javascript">
swf_install = false;
function install_swf(pid){
	  if(swf_install || !pid)
		   return ;
	   swf_install = true;
	
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
	
} 
</script>


<?php
 include FB_CURR.'/cs/gajs_init.php';
?> 

 

</head>
<body style="overflow-x: hidden;overflow-y: hidden;background-color: #ffffff;" >



<div id="header">
    <div id="navga">
    <div class="logo"><a href="<?php echo RenrenConfig::$canvas_url;?>" target="_top" title="开始游戏!">logo</a></div>
   <div id="tabs">
    <ul class="clearfix tcenter">       
        <li class="game" id="flashTab" ><a class="active" href="#switchToFlash" id="flash">游戏</a></li>
        <li class="freegift"><a href="../pop/gift.php" id="freeGift" >免费礼物</a></li>
        <li class="invite" ><a href="../pop/invite/invite.php" id="invite" >邀请好友</a></li>
        <li class="faq"><a id='faq'  href="../static/help/FAQ.html" >常见问题</a></li>
        <!--li class="problem"><a href="javascript:sendNotifcation();" class="fullpage" id="problem">问题反馈</a></li-->
        <li class="forum"><a href="<?php echo RenrenConfig::$group_url; ?>" class="fullpage" id="forum"  target='_blank'>论坛</a></li>
		<li class="payment" ><a  class='fullpage' href="<?php echo RenrenConfig::$canvas_url;?>pay.php"   target="_top" id ="pay">充值</a></li>
	</ul>
	</div>
    </div>
</div>

<div ><!-- style="background: url('../static/images/back.png') no-repeat;" -->
<div id="appFrame" class="flashVisible">
<div id="flashFrame"
	style="background: url('../static/images/back.png') no-repeat; margin-top: 0px; padding: 0px">



<div id="flashDIV" style="width: 760px; height: 700px; margin-top: 0px;">

<div id="flashOuterContainer" style="margin-top: 0px;">
<div id="flashapp" style="margin-top: 0px;"><span
	style="font-size: 19px; font-family: tahoma; color: #4880d7; margin-top: 0px; padding-top: 0px">Loading
Game...</span><br />
<span
	style="font-size: 16px; font-family: tahoma; margin-top: 0px; padding-top: 0px">If
your game does not load within 10 seconds, you may need to upgrade your
version of Flash. Please do so by clicking <a
	href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash&promoid=BUIGP">here</a></span>
</div>
</div>
</div>
</div>
</div>

<div id="htmlFrame" class="offscreen" style="width:100%;vertical-align: middle;text-align: center;">

</div>
<div id="loadingFrame" style="display:none;background: url('../static/images/backsmall.png') no-repeat;"><img
	src="../static/images/loading.gif"/></div>
</div>
<!--div style="margin: 0 ">
<input type="button" onclick="openCinema()" value="test"></input>
</div-->


</body>
</html>
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


