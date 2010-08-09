<?php
require_once('config.php');

//
$pid = $_REQUEST['fb_sig_user'];
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php //include FB_CURR.'/cs/check_connect_redirect.php';?>
<link rel="stylesheet" href="<?php echo FB::$resource_urlp;?>css/main.css?2" />
<link rel="shortcut icon" href="<?php echo FB::$resource_urlp;?>images/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="<?php echo FB::$resource_urlp;?>css/boxy.css" type="text/css" />
<link rel='stylesheet' href='<?php echo FB::$resource_urlp;?>css/installbar.css' type='text/css' />
<script src="<?php echo FB::$resource_urlp;?>js/loader.js"></script>
<script type="text/javascript" src="static/js/stat/common.js"></script>

<link rel="stylesheet"
	href="<?php echo FB::$resource_urlp;?>css/main.css?2" />
<link rel="shortcut icon"
	href="<?php echo FB::$resource_urlp;?>images/favicon.ico"
	type="image/x-icon" />
<link rel="stylesheet"
	href="<?php echo FB::$resource_urlp;?>css/boxy.css" type="text/css" />
<link rel='stylesheet'
	href='<?php echo FB::$resource_urlp;?>css/installbar.css'
	type='text/css' />
<script type="text/javascript" src="static/js/loader.js"></script>
<script src="static/js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="static/js/fb_jsflash.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"> </script>
<script type="text/javascript">
 	// load the flash movie.
	//swfobject.embedSWF("/work/mall/Venus/to-company/MallLoader.swf?v=<?php echo md5_file('/work/mall/Venus/to-company/MallLoader.swf');?>", "flashapp", "760", "620", "9.0.0", "playerProductInstall.swf", flashVars, params, attrs);
	function install_swf(){
		
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
			//console.log('query:'+hash[0]+'='+hash[1]);
		}
		flashvars.platform_id = flashvars.fb_sig_user;
		flashvars.pconf = '0_mall_config.xml';
		flashvars.languagetype = "0";
		flashvars.fb_sig_app_secret = "60d180ac578ce34093b3ce2d1d450f84";
		flashvars.platform = "facebook";
		var flash_width = 760+200;
		var flash_height = 700;
		var params = {};
		params.quality = "high";
		params.bgcolor = "#F0F8FF";
		params.allowscriptaccess = "always";  // must be always since html is different domain from swf :(
		params.allowfullscreen = "true";
		params.wmode = "opaque";//opaque
		params.flashvars = flashvars;
		var attributes = {};
		attributes.id = "flash_run_id";
		attributes.name = "SuperMall";
		attributes.align = "t";
		attributes.salign = "tl";
		/*flashDivId*/
		
		params.base = "/work/mall/Venus/to-company/";
		
		swfobject.embedSWF(
				"/work/mall/Venus/to-company/MallLoader.swf?v=<?php echo md5_file('/work/mall/Venus/to-company/MallLoader.swf');?>", "flashapp", 
				flash_width, flash_height, 
				swfVersionStr, xiSwfUrlStr, 
				flashvars, params, attributes);
		
		//<!-- JavaScript enabled so display the  div in case it is not replaced with a swf object. -->

		swfobject.createCSS("flashContainer", "display:block;text-align:left;");
	}  
	
</script>


</head>
<body style="overflow: auto;">
<div id="FB_HiddenIFrameContainer"
	style="display: none; position: absolute; left: -100px; top: -100px; width: 0px; height: 0px;">
</div>

<div id="tabs">
<ul>
	<li class="first"><a title="free gift" href="pop/gift.php?">免费礼物</a></li>
	<li class="selected" id="flashTab"><a
		title='<?php echo get_lang('Play'); ?>' href="#switchToFlash"><?php echo get_lang('Play'); ?></a></li>
	<li class=''><a title='<?php echo get_lang('Neighbors'); ?>'
		href='<?php echo FB::$canvas_url;?>neighbors.php' target='_blank'><?php echo  get_lang('Neighbors'); ?></a></li>

	<li><a title="invite friends" href="pop/invite/invite.php?giftId=42442"
		id="invite">邀请好友</a></li>
	<li><a title="get coins" href="pop/invite/invite.php" target='_blank'>support
	us</a></li>
	<li class=''><a
		title='<?php echo get_lang('Love').' '.get_lang('Super Mall');?>?'
		href='http://www.facebook.com/apps/application.php?id=<?php echo FB::$app_id;?>'
		target='_blank'><?php echo get_lang('Love').' '.get_lang('Super Mall');?>?</a></li>
</ul>
</div>

<div id='installBarContainer'
	style='display: block; padding-bottom: 0px'>
<div class='top_bar'>
<table width="760" cellpadding="0" cellspacing="0"
	style="background: white; padding-bottom: 0px">
	<tr valign="top">
		<td><img src='<?php echo FB::$resource_urlp;?>images/bar/bar_logo.jpg' /></td>
		<td>
		<table cellpadding='0' cellspacing='0' width='652'>
			<tr>
				<td class="progress1_done"></td>
				<td id='step_button_2' class="progress2 button"><a href="#"><img
					src="<?php echo FB::$resource_urlp;?>images/facebook/buttons/permissions.png" /></a></td>
				<td id='step_button_3' class="progress3 button"><a href="#"><img
					src="<?php echo FB::$resource_urlp;?>images/facebook/buttons/bookmark.png" /></a></td>
				
				<td id='step_button_4' class="progress4 button"><a href="#"><img
					src="<?php echo FB::$resource_urlp;?>images/facebook/buttons/like.png" /></a></td>
				<td id='step_button_5' class="progress5 button"><a href="#"><img
					src="<?php echo FB::$resource_urlp;?>images/facebook/buttons/subscribe.png" /></a></td>
				
			</tr>
			<tr>
				<td colspan='5'>
				<div id='bar_completeness' class='bar_1' steps='1' />
				</td>
			</tr>
		</table>
		</td>
		<td>
		<div class="bar_end"></div>
		</td>
	</tr>
</table>
</div>
</div>



<div id="appFrame" class="flashVisible">
<div id="flashFrame"
	style="background: black; margin-top: 0px; padding: 0px">



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


<div class="clearfix"
	style="margin-top: 8px; margin-bottom: 8px; border: 1px solid #ccc; background: #f7f7f7; height: 58px; width: 760px; vertical-align: middle;">
<div
	style="border: 2px solid white; height: 54px; vertical-align: middle;"
	id="likeFrame_C"><iframe id="likeFrame"
	src="pop/like.php?fbid=loggedinuser&name=hjdshj" width="750"
	height="36px" style="float:left;border:none;overflow:hidden;"></iframe>
</div>
</div>

<div id="htmlFrame" class="offscreen"></div>
<div id="loadingFrame" class="offscreen" style="display: none"><img
	src="static/images/loading.gif" /></div>









<div style="text-align: center; margin-bottom: 10px"><?php echo get_lang('Having trouble to load game');?>? <?php echo get_lang('Please');?> <a
	href='http://get.adobe.com/flashplayer/' target="_blank"><?php echo get_lang('update your flash player'); ?></a> <?php echo('by clicking');?> <a
	href='http://get.adobe.com/flashplayer/' target="_blank"><?php echo get_lang('Here');?></a>.</div>



<table id='fan_dialog' cellspacing='0' cellpadding='0' border='0'
	class='boxy-wrapper fixed'
	style='z-index: 1339; left: 205px; top: 110.5px; display: none'>
	<tbody>
		<tr>
			<td class='top-left'></td>
			<td class='top'></td>
			<td class='top-right'></td>
		</tr>
		<tr>
			<td class='left'></td>
			<td class='boxy-inner'>
			<div class='title-bar' style='-moz-user-select: none;'>
			<h2><?php echo get_lang('Become a Fan')?></h2>
			<a id='fan_close_link' class='close' href='#'>[<?php echo get_lang('Close');?>]</a></div>
			<div style='display: block;' id='fan_dialog' class='boxy-content'><fb:fan
				profile_id='<?php echo FB::$app_id;?>'></fb:fan></div>
			</td>
			<td class='right'></td>
		</tr>
		<tr>
			<td class='bottom-left'></td>
			<td class='bottom'></td>
			<td class='bottom-right'></td>
		</tr>
	</tbody>
</table>
<div style="display: none; position: absolute; left: 16px; top: 30.5px;"
	id="inviteDiv">
<table cellspacing="0" cellpadding="0" border="0"
	class="boxy-wrapper fixed">
	<tbody>
		<tr>
			<td class="top-left"></td>
			<td class="top"></td>
			<td class="top-right"></td>
		</tr>
		<tr>
			<td class="left"></td>
			<td class="boxy-inner">
			<div class="title-bar boxy-content">
			<h2>&nbsp;</h2>
			<a onclick="closeInvite();return false" class="close" href="#">[close]</a></div>
			<div class="loading boxy-content" id="invite_loading"
				style="display: none;"><img
				src="<?php echo FB::$resource_urlp;?>images/spinner.gif" /></div>
			<div class="boxy-content"><iframe id="inviteFrame" width="728"
				height="500" frameborder="0" style="overflow: auto;"
				src="pop/blank.php"></iframe></div>
			</td>
			<td class="right"></td>
		</tr>
		<tr>
			<td class="bottom-left"></td>
			<td class="bottom"></td>
			<td class="bottom-right"></td>
		</tr>
	</tbody>
</table>
</div>
<script type="text/javascript">
		install_swf();
		</script>

<script>
PL.js(['static/js/jquery-1.4.2.min.js','static/js/install_bar.js' ],function(){
	 IBar.bind();
	 PL.js(['static/js/jquery.boxy.js'])
	
});

query_json = PL.query_json();
query_str  = PL.query_str();
console.log('aa',query_json);
PL.js(['static/js/pageUtil.js', 'static/js/log.js']); 
var config = {
		log:1,//init log? server can force debug, just for develop
		fb:1,//init fb?

		fbd:1,//init fb debug? 
		logcb:function(r){//log init callback
			console.log('log callback in ' +  window.location.href);
		},
		after_fbinit:function(){//before FB.init callback
					   IBar.show(1);
			console.log('in index after_fbinit');
			IBar.show(1);
			FB.getLoginStatus(function(r){
				
					var cb = function(response) {
						console.log('FB.login callback', response);
						if (response.session) {
							console.log('User logged in');
						  if (response.perms){
							Log.info('User granted permissions');
						  }
						 } else {
				  	         Log.info('User is logged out');
					     }
					     FB.Canvas.setAutoResize(false); 
					};
				if(r.status !='connected'){
					console.log('not connected');
					FB.login(cb, { perms: 'email,read_stream' });// publish_stream,
				}
				else if(r.session){
					
					if(r.session.uid){
					   query_json.fb_sig_user = r.session.uid||query_json.fb_sig_user;
					   PL.conf('uid',query_json.fb_sig_user);
					   query_json.fb_session = r.session;
					   console.log(query_json);
					   IBar.show();
					   playcrab_fb.set_page_ok();
					   init_popFrame(); 
					}else{
						console.log('not connected');
						FB.login(cb, { perms: 'email,read_stream' });// publish_stream,
						console.log('no userid get');
					}
				}else{
					console.log('no session');
					FB.login(cb, { perms: 'email,read_stream' });// publish_stream,
				}
			})
			
		},
		before_fbinit:function(){//after FB.init callback
			 FB.Canvas.setAutoResize(); 
		},
		cb:function(){//after config callback
			
		}
}


PL.init('static/js/fb_config.js',config);

</script>
<div><input type="button" value="feed me" onclick="popUpFeed();" /></div>
</body>
</html>
<script type="text/javascript">
<!--
var appFrame, flashFrame, loadingFrame, htmlFrame, iframe, oldIframe, tabs, flashTab, preloadedUrls = {}, needToRestore;
var cleanup = function() {
	oldIframe.remove();
	if (needToRestore) {
		preloadUrl(needToRestore);
		needToRestore = null;
	}
};

var hideFlash = function() {
	window.location.hash = '#notOnFlash';
	flashFrame.addClass('offscreen');
	
};

var hideHtml = function() { htmlFrame.addClass('offscreen'); };
var hideLoading = function() {loadingFrame.addClass('offscreen'); };

var showHtml = function() {
	hideLoading();
	hideFlash();
	htmlFrame.removeClass('offscreen');
	appFrame.removeClass('flashVisible');
};

var showLoading = function() {
	hideHtml();
	hideFlash();
	loadingFrame.show();
	loadingFrame.removeClass('offscreen');
	appFrame.removeClass('flashVisible');
};

var showFlash = function() {
	hideHtml();
	hideLoading();
	flashFrame.removeClass('offscreen');
	flashFrame.show();
	appFrame.addClass('flashVisible');
	oldIframe = iframe;
	iframe = false;
	tabs.children('a').parent().removeClass('selected');
	flashTab.addClass('selected');
	setTimeout(cleanup, 1000);
};

var interval, innerDoc, lastHeight;

var innerIFramePoller = function() {

	try {
		if (iframe == null || (iframe[0].contentWindow && iframe[0].contentWindow.location.hash == "#switchToFlash")) {
		  iframe && iframe.unbind('load', iframeLoaded);
			showFlash();
			clearInterval(interval);
			interval = null;
			if (iframe)
			iframe[0].contentWindow.location.hash = '#switched';
		} else {
			if (!innerDoc || lastHeight == null || lastHeight == 0) {
				innerDoc = $(iframe[0].contentWindow.document.body);
			
			}
			
			var height = innerDoc.outerHeight();
			if (height != lastHeight) {
				iframe.height(height*4);
				lastHeight = height;
			}
		}
	} catch(e) {
	}

};

var iframeLoaded = function() {
	iframe && iframe.unbind('load', iframeLoaded);
	showHtml();
};

var navigateTo = function(url) {
	//return;
	if (url.match(/^#switchToFlash/)) {
		switchToFlash();
		return;
	}
	setupElements();
	showLoading();
	if (!iframe) {
		iframe = createIframe().appendTo(htmlFrame);
	}
	iframe.load(iframeLoaded);
	iframe.attr('src', url);
	innerDoc = null; lastHeight = 0;
	interval = setInterval(innerIFramePoller, 500);
};

var switchToFlash = function() {
	interval && clearInterval(interval);
	interval = null;
	showFlash();
};

var preloadFuncs = [];
var preloadUrl = function(url) {
	var preloader = function(){
		var preloadedFrame = createIframe();
		preloadedFrame.addClass('preloaded').attr('src', prepUrl(url)).appendTo(htmlFrame).load(function() {
			preloadedUrls[url] = preloadedFrame;
		});
	};

	if(preloadFuncs.push){
		preloadFuncs.push(preloader);
	}	
};

var readyToPreload = function() {
	setupElements();
	var funcs = preloadFuncs;
	preloadFuncs = [];
	setTimeout(function() {
		for (var i = 0; i < funcs.length; ++i) {
			funcs[i]();
		}
	}, 5000);
};

var createIframe = function() {
	return $("<iframe/>", {scrolling: 'no', border: '0', frameborder: '0', height: '0'});
};

var setupElements = function() {
	appFrame = $('#appFrame');
	flashFrame = $('#flashFrame');
	loadingFrame = $('#loadingFrame');
	htmlFrame = $('#htmlFrame');
	flashTab = $('#flashTab');
};

$(document).ready(
		function(){
	/** Opens an overlaying iframe */
	(function() {
		
		
		var tabClick = function(e) {
			if (e && e.target) {
				var el = $(e.target);
				if (!el.is('a')) {
					el = el.parents('a');
				}
				navigateTo(el.attr('href'));
				tabs.children('a').parent().removeClass('selected');
				el.parent().addClass('selected');
				return false;
			}
		};

		
		$(function() {
			tabs = $('#tabs li');
			console.log(tabs.children('a'));
			tabs.children('a').not('.fullpage').click(tabClick);
			setupElements();
		});

	})();

});
//-->
</script>

<?php include FB_CURR.'/cs/gajs_init.php';?> 

