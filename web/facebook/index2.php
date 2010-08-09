<?php require_once('config.php');?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"

   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<link rel="stylesheet" type="text/css" href="http://fb-client-0.frontier.zynga.com/css/reset.css" />
		<link rel="stylesheet" type="text/css" href="http://fb-client-0.frontier.zynga.com/css/main.css" />
		<link rel="stylesheet" type="text/css" href="http://fb-client-0.frontier.zynga.com/css/app.css" />
		<link rel="stylesheet" href="<?php echo FB::$resource_urlp;?>css/main.css?2" />
<link rel="shortcut icon" href="<?php echo FB::$resource_urlp;?>images/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="<?php echo FB::$resource_urlp;?>css/boxy.css" type="text/css" />
<link rel='stylesheet' href='<?php echo FB::$resource_urlp;?>css/installbar.css' type='text/css' />
		<script src="http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>
<script type="text/javascript" src="http://fb-client-0.frontier.zynga.com/js/jquery.min.js"></script>
<script type="text/javascript" src="http://fb-client-0.frontier.zynga.com/js/global.js"></script>
		<script type="text/javascript" src="static/js/pageUtil.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"> </script>

<script  type="text/javascript">

	var params = { allowScriptAccess: "always", wmode: "opaque", allowFullScreen: "true" };
	var attrs = { id: "flashapp",name: "flashapp" };
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
		flashvars.pconf = 'mall_config.xml';
		flashvars.languagetype = "0";
		flashvars.fb_sig_app_secret = "60d180ac578ce34093b3ce2d1d450f84";
		flashvars.platform = "facebook";
		var flash_width = 760+200;
		var flash_height = 900;
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
		params.base = "http://192.168.1.50/work/mall/Venus/to-company/";
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
	<title>FrontierVille</title>
	<body style="overflow: auto">
				
			<div class="noticebox" style="text-align: center; margin: 0 0 10px 0;">
				this is mall's test text
				<input type="button" onclick="inviteFriends()" value="kkkkk"/>
			</div>
			<div class="left" style="padding: 0 0 0 0;">
					游戏推广广告
					<iframe height="80" width="760" frameborder="0" scrolling="no" src="http://zbar.zynga.com/zbar-new/banner.php?uid=100000891768718&tid=04c56d42d3&fgid=67&snid=1"></iframe>
			</div>
							


						
			<iframe id="probar_iframe" src="http://fb-0.frontier.zynga.com/probar.php?zySnid=1&zySnuid=100000891768718&zy_user=100000891768718&zy_ts=&zy_session=&zySig=3909d25ecf676e614b0e93a193221da8" frameborder="0" height="0"></iframe>
						
			<style type="text/css">
			
			</style>
			<div style="display: none;" id="fanBox">

				<div class="bubble_titlebar">
					<div class="bubble_top">
						Like Frontierville
					</div>
					<span id="close_display">
						<a href="#" class="viral_dialog_button_close" onclick="document.getElementById('fanBox').style.display = 'none'; $('#probar_iframe')[0].contentWindow.refreshProgress()"></a>
					</span>
				</div> 
			
				<div class="bubble_middle">
					<br /><div class="fanBoxPopupText">Click here to "like" Frontierville</div><br /><br />

					<fb:fan profile_id="201278444497" name=""></fb:fan>
					<br /><br />
				</div>
				</div>
			<br/>
	
<div class="">
<div id="tabs">
	<ul>
		<li class="first" id="li1">

			<a href="pop/gift.php?ref=tab&zySnid=1&zySnuid=100000891768718&zy_user=100000891768718&zy_ts=&zy_session=&zySig=3909d25ecf676e614b0e93a193221da8" title="Free Gifts">Free Gifts</a>
		</li>
		<li class="selected" id="flashTab" id="li2">
				
				<a href="#switchToFlash" title="Play">Play</a>
				
		</li>
		<li id="li3">
			<a href="#" title="My Neighbors">My Neighbors</a>

		</li>
		<li id="li4">
			<a href="pop/invite/invite.php?giftId=42442" title="Invite Friends" id="invite">Invite Friends</a>
		</li>
		<li id="li5">
			<a href="#" title="Help">Help</a>
		</li>
		<li id="li6">

			<a href="http://fb-0.frontier.zynga.com/money.php?ref=tab&zySnid=1&zySnuid=100000891768718&zy_user=100000891768718&zy_ts=&zy_session=&zySig=3909d25ecf676e614b0e93a193221da8" title="Get Horseshoes">Get Horseshoes</a>
		</li>
			</ul>
</div>
</div>		
<div id="appFrame"  style="display:block;"><!--  class="flashVisible"-->
	<div id="flashFrame">
		
		<div style="margin-top: 5px;">
			<div id="message_center_div"></div>
		</div>

		<script type="text/javascript">
		install_swf();
		</script>
		<div id="flashDIV" style="width:760px;height:620px;">
 
			<div id="flashOuterContainer">
				<div id="flashapp">
					<span style="font-size: 19px; font-family: tahoma; color: #4880d7;padding-bottom: 10px;">Loading Game...</span><br/>
					<span style="font-size: 16px; font-family: tahoma;">If your game does not load within 10 seconds, you may need to upgrade your version of Flash.  Please do so by clicking <a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash&promoid=BUIGP">here</a></span>
				</div>
			</div>
		</div>

		<div id="rypixel"></div>

	</div>
	<div class="clearfix"
	style="margin-top: 8px; margin-bottom: 8px; border: 1px solid #ccc; background: #f7f7f7; height: 58px ;width:760px;vertical-align: middle;">
	<div style="border: 2px solid white; height: 54px;vertical-align: middle;" id="likeFrame_C" >
	<iframe id="likeFrame" src="pop/like.php?fbid=loggedinuser&name=hjdshj" width="750" height="36px" style="float:left;border:none;overflow:hidden;"></iframe>
	</div>
</div>
	<div id="htmlFrame" class="offscreen">

	</div>
	<div id="loadingFrame" class="offscreen"><img src="http://assets.frontierville.zynga.com/production/R.0.7.004.18097/assets/images/loading.gif"></div><!--  -->
	</div>
<br></br>
<!--div class="padding_content center" style="font-size: 10pt; font-weight: bold;">
		

	</div-->
	<div class="center">
		Having trouble seeing FrontierVille?<br /> Please <a href="http://get.adobe.com/flashplayer/" target="_blank">update your flash player</a> by clicking <a href="http://get.adobe.com/flashplayer/" target="_blank">HERE</a>. | <a href="http://zynga.custhelp.com/">Report User Content</a>
	</div>

	<div class="center" style="margin:15px 0px 0px 0px;">
		All items including but not limited to user interface.
	</div>
<div>

</div>
</body>
</html>
