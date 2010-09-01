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
	  return;
	
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
	flashvars.STAGE_WIDTH = "800";
	flashvars.CRITICAL_ERROR_SHOW = "0";
	flashvars.platform = "renren";
	var flash_width = 800;
	var flash_height = 700;
	var params = {};
	params.quality = "high";
	params.bgcolor = "#F0F8FF";
	params.allowscriptaccess = "always";  // must be always since html is different domain from swf :(
	params.allowfullscreen = "true";
	//params.wmode = "window";//opaque
	params.wmode = "opaque";
	params.flashvars = flashvars;
	var attributes = {};
	attributes.id = "flash_run_id";
	attributes.name = "SuperMall";
	attributes.align = "t";
	attributes.salign = "tl";
	/*flashDivId*/
	//params.base = "http://127.0.0.1/work/mall/Venus/to-company/";
	swfobject.embedSWF(
		"../static/flash/MallLoader.swf?v=<?php echo md5_file('../static/flash/MallLoader.swf');?>", "flashapp", 
			flash_width, flash_height, 
			swfVersionStr, xiSwfUrlStr, 
			flashvars, params, attributes);
	
} 
</script>


 

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


<!--div style="margin: 0 ">
<input type="button" onclick="openCinema()" value="test"></input>
</div-->


</body>
</html>
<script type="text/javascript">

function update_info()
{
	pid = PL.conf('pid')||query_json.xn_sig_user;
	XN.Main.get_sessionState().waitUntilReady(function(){
		var get_user=function (r){
			if(r[0]&&r[0].name){
				$.post("../ajax/save_info.php", r[0], function(){}, 'json');
			}
		}
		XN.Main.apiClient.users_getInfo([ pid ],["uid","name",
			"sex","star","zidou","vip","tinyurl","birthday","email_hash",
			],get_user);
			// ],Log.info.bind('XN.Main.apiClient.users_getInfo_update'));
	});
	XN.Main.get_sessionState().waitUntilReady(function(){
		var get_friends=function (r){
			if(r&&r[0]>0){
				$.post("../ajax/save_friends.php", {'pid':pid,'fids':r}, function(){}, 'json');
			}
		}
		XN.Main.apiClient.friends_getAppUsers(get_friends);
	});
}

pid = PL.conf('pid')||query_json.xn_sig_user;
pid && install_swf(pid);
var config = {
		useparent:false,
		log:1,//init log? server can force debug, just for develop
		fbd:1,//init fb?
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
	  				   
					   update_info();

	  			   }
	  			   XN.Main.get_sessionState().waitUntilReady(
	  					   function(){
	  						   XN.Main.apiClient.users_getLoggedInUser(getpid);
	  					   });
	  			   
	  		   }else{
	   			  install_swf(pid);
				  update_info();
	  		   }
	  		   //PF.set_page_ok();	  


	},
	before_fbinit:function(){//after FB.init callback
		    	
		      },
	cb:function(){//after config callback
		 
	   }
	}

	PL.init('../static/js/config.js',config);
</script>


