<?php
require_once '../config.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<title>Club</title>

<meta name="layout" content="facebook_xfbml"></meta>
<style type="text/css">
body {
	margin: 0;
	padding: 0;
	font-family: Lucida Grande, Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold
}
</style>

</head>
<body >

		<div style="vertical-align: middle;">
<table cellspacing="0" cellpadding="0" width="720" border="0">
	<tr valign="middle">
		<td width="150">
		<fb:profile-pic linked="true" size="square" uid="<?php echo $_REQUEST["fbid"];?>" /></td>
		<td><?php echo $_REQUEST ["name"]?></td>
		<td align="center" ><!--fb:like
			href="http://supermall.playcrab.com/inif/pop/liked.php?subj=<?php
			echo $_REQUEST ["subj"];
			?>"
			show_faces="false"></fb:like--> 
			<br>
			<iframe
			id='likeFriend'
			src="http://www.facebook.com/plugins/like.php?href=http://127.0.0.1/work/mall/backend/web/facebook/pop/liked.php?fbid=&amp;show_faces=false&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=35"
			scrolling="no" frameborder="0"
			style="border:none; overflow:hidden; width:450px; height:35px;"
			allowTransparency="true"></iframe></td>
	</tr>
</table>
</div>
</body>
</html>
<script type="text/javascript" src="../static/js/loader.js"></script>
<script type="text/javascript">
var fbid=PL.getUrlParam('fbid','');
document.getElementById('likeFriend').src="http://www.facebook.com/plugins/like.php?href=http://supermall.playcrab.com/pop/liked.php?fbid="+fbid+"&amp;show_faces=false&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=35"
var config = {
		log:1,//init log? server can force debug, just for develop
		//fb:1,//init fb?
		fbd:1,//init fb debug? 
		logcb:function(r){//log init callback
			console.log('log callback' +  window.location.href);
		},
		after_fbinit:function(){//before FB.init callback
			
			
		},
		before_fbinit:function(){//after FB.init callback
			 FB.Canvas.setAutoResize(); 
		},
		cb:function(){//after config callback
			PL.js(['fb_jsflash.js','pageUtil.js','loadSwf.js'])
		}
}

//PL.init('../static/js/fb_config.js',config);
