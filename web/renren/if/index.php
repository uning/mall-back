<?php
require_once('../config.php');

//
$pid = $_REQUEST['xn_sig_user'];
$session_key = $_REQUEST['xn_sig_session_key'];
$gflg = $_REQUEST['glink'];
$sess = TTGenid::getbypid($pid);
$uid = $sess['id'];
$tu = new   TTUser($uid);
 $iid = $tu->getdid('installbar',TT::OTHER_GROUP);
	 $barobj = $tu->getbyid($iid); 
	 $install_bar = true;
	 if($barobj == null || $barobj['email'] == null){
		$install_bar = true;
	}else{
	  $install_bar = false;
	 } 

if($gflg){
	
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
	//$ts->puto($data);
 
	
			
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xn="http://www.renren.com/2009/xnml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php //include FB_CURR.'/cs/check_connect_redirect.php';?>
<link rel="stylesheet"href="<?php echo RenrenConfig::$resource_urlp;?>css/main.css?7" />
<?php if($install_bar){ ?>
<script src="<?php echo RenrenConfig::$resource_urlp;?>js/install_bar.js?v=3"></script>
<link rel="stylesheet" href="<?php echo RenrenConfig::$resource_urlp;?>css/installbar.css?4" />
<?php } ?>
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
	flashvars.STAGE_WIDTH = "800";
	flashvars.CRITICAL_ERROR_SHOW = "0";
	flashvars.platform = "renren";
	flashvars.errorPage = '/bg/error_log.php';
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
	<div class='topbar'> 
 			<ul id="scrollBox">   
				  <li> 点击顾客，把他们送到他们想去的地方，会有小惊喜哦 </li>  
				  <li> 把顾客送到<span>电影院</span>，他们会很乐意看场电影 </li>  
				  <li> 多上货才能多赚钱 </li>  
				  <li> 离线的时候，商场依然是在运作的 </li>  
				  <li> <span>厕所</span>虽然不能收钱，但是可以提高人气 </li>  
				  <li> <span>电影院</span>要所有的座位都坐上人才能开演 </li>  
				  <li> 高等级的货车可以进取更多的货物 </li>  
				  <li> 可以使用道具使正在运货的货车增加运货量或者提高运货速度 </li>  
				  <li> <span>手扶梯</span>可以显著地改善很多顾客等电梯的状况 </li>  
				  <li> 如果你的商场有坐下休息的地方，顾客们会很高兴的 </li>  
				  <li> 每个商铺都有<span>等待队列</span>，当前物品卖完后会依次销售队列里的货物 </li>  
				  <li> 你的好友偶尔也会来你的商场购物 </li>  
				  <li> 全屏可以使你的视野更加开阔 </li>  
				  <li> 如果你的商场营业店铺太少的话顾客会不高兴的离开 </li>  
				  <li> 楼体、屋顶、地板、吊饰都是可以更换的 </li>  
				  <li> 经常点击游戏中好友分享的消息，可是一笔很可观的收入哦 </li>  
				  <li> 经常分享游戏中的消息，好友会因此得到一笔很不错的收入哦 </li>  

 			</ul>

    </div>
     <div id="navga">
    <div class="logo"><a href="<?php echo RenrenConfig::$canvas_url;?>" target="_top" title="开始游戏!">logo</a></div>
   <div id="tabs">
    <ul class="clearfix tcenter">       
        <li class="game" id="flashTab" ><a class="active" href="#switchToFlash" id="flash">游戏</a></li>
        <li class="freegift"><a href="../pop/gift.php" id="freeGift" >免费礼物</a></li>
        <li class="invite" ><a href="../pop/invite/invite.php" id="invite" >邀请好友</a></li>
        <li class="faq"><a id='faq'  href="<?php echo RenrenConfig::$resource_urlp;?>/help/FAQ.html">常见问题</a></li>
        <!--li class="problem"><a href="javascript:sendNotifcation();" class="fullpage" id="problem">问题反馈</a></li-->
        <li class="forum"><a href="<?php echo RenrenConfig::$group_url; ?>" class="fullpage" id="forum"  target='_blank'>论坛</a></li>
		<li class="payment" ><a  class='fullpage' href="<?php echo RenrenConfig::$canvas_url;?>pay.php"   target="_top" id ="pay">充值</a></li>
	</ul>
	</div>
    </div>
	<?php if($install_bar){ ?>
	<div style="display: block;" id="installBar">
		<div class="iBarStep done" id="iBarStepInstall">
			<div class="iBarDone">
				<img src="<?php echo RenrenConfig::$resource_urlp;?>/images/install_done.png">
			</div>
		</div>
		<div class="iBarStep" id="iBarStepFan">
			<div class="iBarAction">
				<a onclick="IBar.becomeFan(); return false;" href="#">
				<img border="0" src="<?php echo RenrenConfig::$resource_urlp;?>/images/fan_button.png"></a>
			</div>
			<div style="left: -12px;" class="iBarDone">
				<img src="<?php echo RenrenConfig::$resource_urlp;?>/images/fan_done.png">
			</div>
		</div>
		<div class="iBarStep" id="iBarStepEmail">
			<div class="iBarAction">
				<a onclick="XN.Connect.showPermissionDialog('email',IBar.permCallBack);return false;" href="#">
				<img border="0" src="<?php echo RenrenConfig::$resource_urlp;?>/images/email_button.png"></a>
			</div>
			<div class="iBarDone">
				<img src="<?php echo RenrenConfig::$resource_urlp;?>/images/email_done.png">
			</div>
		</div>
		<div id="progressBar" style="width: 241px;" class="stepcount_1">
			<div id="progressPercentage">
			</div>
		</div> 
	</div> 
	<?php } ?>
</div>

<div ><!-- style="background: url('../static/images/back.png') no-repeat;" -->
<div id="appFrame" class="flashVisible">
<div id="flashFrame"
	style="background: url('<?php echo RenrenConfig::$resource_urlp;?>/images/genericbg.jpg') no-repeat; margin-top: 0px; padding: 0px">



<div id="flashDIV" style="width: 800px; height: 700px; margin-top: 0px;">

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


		
		<div class="help">
			    <span style="width: 625px; float: left;">
					[<a href='http://group.renren.com/GetThread.do?id=331584998&parentpage=&curpage=0&label=&tribeId=336701942' target='_blank'>如何清除浏览器缓存</a>]&nbsp;
				  
				</span>
				<span style="width: 175px; float: right; text-align: right;">
					[<a target="_blank" href="http://msg.renren.com/SendMessage.do?id=253382225">联系客服</a>]&nbsp;
				</span>
	  </div>
		

		<div class='bottom'>
			<a target='_blank' href='http://page.renren.com/livemall'>
				<img src="<?php echo RenrenConfig::$resource_urlp ?>images/bottom.png" /> 
			</a>
		</div>
		
		<div class='xnid'>商场门牌号: <?php echo $_REQUEST['xn_sig_user'] ?></div>
		
		<div class='footnotice'>
			健康忠告：抵制不良游戏，拒绝盗版游戏。注意自我保护，预防受骗上当。适度游戏益脑，沉迷游戏伤身。合理安排时间，享受健康生活。				    				
		</div>




<!--div style="margin: 0 ">
<input type="button" onclick="openCinema()" value="test"></input>
</div-->


</body>
</html>
<script type="text/javascript">
 
<?php  if($barobj['fan']){
echo "var installStep = 2; ";
}else{
echo "var installStep = 1; ";
}?>
     
var pid= <?php echo $pid; ?>;
var session_key= "<?php echo $session_key; ?>";
 
    window.onload=function(){
	        if( typeof(IBar) != 'undefined' )
		        	IBar.init_bar();	 
			var o=document.getElementById('scrollBox');
			window.setInterval(function(){scrollup(o,24,0);},3000); 
	}
	function scrollup(o,d,c){
			if(d==c){
					var t=getFirstChild(o.firstChild).cloneNode(true);
					o.removeChild(getFirstChild(o.firstChild));
					o.appendChild(t);
					t.style.marginTop="0px";
			}else{
					c+=2;
					getFirstChild(o.firstChild).style.marginTop=-c+"px";
					window.setTimeout(function(){scrollup(o,d,c)},20);
			}
	}
	function getFirstChild(node){
			  while (node.nodeType!=1) {
					 node=node.nextSibling;
			  }
			  return node;
	}



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
	  		   PF.set_page_ok();	  


	},
	before_fbinit:function(){//after FB.init callback
		    	
		      },
	cb:function(){//after config callback
		 
	   }
	}

	PL.init('../static/js/config.js',config);
</script>


