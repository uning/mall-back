<?php
require_once('../config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php //include FB_CURR.'/cs/check_connect_redirect.php';?>
<link rel="stylesheet" href="<?php echo FB::$resource_urlp;?>css/main.css?2" />
<link rel="stylesheet" href="<?php echo FB::$resource_urlp;?>css/main2.css" />
<link rel="stylesheet" href="<?php echo FB::$resource_urlp;?>css/reset.css" />
<link rel="stylesheet" href="<?php echo FB::$resource_urlp;?>css/app.css" />
<link rel="shortcut icon" href="<?php echo FB::$resource_urlp;?>images/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="<?php echo FB::$resource_urlp;?>css/boxy.css" type="text/css" />
<link rel='stylesheet' href='<?php echo FB::$resource_urlp;?>css/installbar.css' type='text/css' />
<script src="../static/js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script src="../static/js/pageUtil.js" type="text/javascript"></script>
</head>
<body style="width:100%;height:100%">
<div id="FB_HiddenIFrameContainer"
	style="display: none; position: absolute; left: -100px; top: -100px; width: 0px; height: 0px;"></div>

<div>
<input type="button" value="click me" onclick="inviteFriends(1,2)"/>
</div>
<div id='tabs' class='clearfix'>
<ul class='tabs'>
	<li class='first selected'><a title='<?php echo get_lang('Play'); ?>'
		href='<?php echo FB::$canvas_url;?>?f=self' target='_top'><?php echo get_lang('Play'); ?></a></li>
	<li class=''><a title='<?php echo get_lang('Neighbors'); ?>'
		href='<?php echo FB::$canvas_url;?>neighbors.php'
		target='_blank'><?php echo  get_lang('Neighbors'); ?></a></li>
	<li class=''><a title='<?php echo get_lang('Love').' '.get_lang('Super Mall');?>?'
		href='http://www.facebook.com/apps/application.php?id=<?php echo FB::$app_id;?>'
		target='_blank'><?php echo get_lang('Love').' '.get_lang('Super Mall');?>?</a></li>
</ul>
</div>

<div style='margin-top: 10px'>
<div id='installBarContainer' style='display: none'>
<div class='top_bar'>
<table width="760" cellpadding="0" cellspacing="0"
	style="background: white">
	<tr valign="top">
		<td width='92'><img
			src='<?php echo FB::$resource_urlp;?>images/bar/bar_logo.jpg' /></td>
		<td>
		<table cellpadding='0' cellspacing='0' width='652'>
			<tr>
				<td class="progress1_done"></td>
				<td id='step_button_2' class="progress2 button"><a href="#"
					onclick="get_publish_permission(2);return false"><img
					src="<?php echo FB::$resource_urlp;?>images/facebook/buttons/permissions.png" /></a></td>
				<td id='step_button_3' class="progress3 button"><a href="#"
					onclick="bookmark(3);return false"><img
					src="<?php echo FB::$resource_urlp;?>images/facebook/buttons/bookmark.png" /></a></td>
				<td id='step_button_4' class="progress4 button"><a href="#"
					onclick="fan(4);return false"><img
					src="<?php echo FB::$resource_urlp;?>images/facebook/buttons/like.png" /></a></td>
				<td id='step_button_5' class="progress5 button"><a href="#"
					onclick="get_email_permission(5);return false"><img
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
</div>


<div class="clearfix">
<div id="gameFrameContainer" style="width: 1520px;height:90%">

  <iframe
	src="<?php echo FB::$resource_urlp.'flash/loader.php';?>"
	frameborder="0" border="0" width="760" height="600" id="gameFrame"
	style="float:left;border:none;overflow:hidden;">
    <p>Your browser does not support iframes.</p>
 </iframe>

<div id="gameFrameBackground"
	style="z-index: 1; background: black url(<?php echo FB::$resource_urlp;?>images/genericbg.jpg); float: right; width: 760px; height: 600px;"></div>
</div>
</div>


<div class="clearfix"
	style="margin-top: 8px; margin-bottom: 8px; border: 1px solid #ccc; background: #f7f7f7; height: 58px">
<div style="border: 2px solid white; height: 54px" id='likeFrame_C'>
<iframe id="likeFrame" src="pop/like.php" width="750" height="54px" style="float:left;border:none;overflow:hidden;"></iframe>
</div>
</div>




<div style="text-align: center; margin-bottom: 10px"><?php echo get_lang('Having trouble to load game');?>? <?php echo get_lang('Please');?> <a
	href='http://get.adobe.com/flashplayer/' target="_blank"><?php echo get_lang('update your flash player'); ?></a> <?php echo('by clicking');?> <a href='http://get.adobe.com/flashplayer/'target="_blank"><?php echo get_lang('Here');?></a>.</div>

<?php include FB_CURR.'/cs/if_infb_tail.php';?>


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
			<h2>Become a Fan</h2>
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
<div style="display:none;position: absolute;left: 32px;top:60.5px;" id="inviteDiv">
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
			<div class="title-bar boxy-content" >
			<h2>&nbsp;</h2>
			<a onclick="closeInvite();return false" class="close" href="#">[close]</a></div>
			<div class="loading boxy-content" id="invite_loading"
				style="display: none;"><img
				src="<?php echo FB::$resource_urlp;?>images/spinner.gif"></div>
			<div class="boxy-content"><iframe
				id="inviteFrame" width="646" height="500" frameborder="0"
				style="overflow: auto;"
				src="pop/invite/blank.php"></iframe></div>
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

<script src="<?php echo FB::$resource_urlp;?>js/loader.js?v=1"></script>
<script>
PL.init('<?php echo FB::$resource_urlp;?>js/fb_config.js',
		function(r){
	    console.log(r);
		PL.js(['jquery-1.4.2.min.js','jquery.boxy.js'],function(i){
			 console.log(i);
		     init_popFrame(); 
	});
});
</script>
<?php include FB_CURR.'/cs/fbjs_init.php';?>
<button onclick="invite_open()"> invite</button>
<script type="text/javascript">
var cached_publish_stream = false;

function init_popFrame()
{
	$('#likeFrameC').html('<iframe id="likeFrame" src="pop/like.php?fbid='+ 
           query_json.fb_sig_user+'" frameborder="0" border="0" width="754" height="54"'+
	' style="overflow:hidden;margin:0;padding:0"></iframe>');
        $('#innerInviteFrameC').html();
         
}


function increase_completion(step)
{
	var button = $("#step_button_"+step);
	var img  = button.find('a');
	console.log(img.html());
	if(img != null){
		button.empty();
		button.append(img.html());
	}
	if (button.hasClass("button"))
	{
		var bar = $("#bar_completeness");
		var steps = bar.attr("steps");
		if (steps < 5)
		{
			steps++;
			bar.attr("steps", steps);
			bar.attr("className", "bar_"+steps);
		}
	}
	button.attr("className", "progress"+step+"_done");
}


function get_publish_permission(step)
{
	var cb = function(response) {
		if (response.session) {
			if (response.perms.indexOf('publish_stream') > -1){
				increase_completion(step);
			}
		} else {

		}
	};
	FB.login(cb,  {perms: 'publish_stream'});
}

function get_email_permission(step)
{
	var cb = function(response) {
		if (response.session) {
			if (response.perms.indexOf('email') > -1){
				increase_completion(step);
				save_email();
			}
		} else {

		}
	};
	FB.login(cb,  {perms: 'email'});
}

function save_email()
{
	$.post("ajax/save_email.php", query_json);
}

function bookmark(step)
{
	FB.ui({ method: 'bookmark.add' },function (res) {
                console.dir(res);
		if (res.bookmarked == 1)
		{
			increase_completion(step);
			save_bookmarked();
		}
	});
}

function save_bookmarked()
{
	$.post("ajax/bookmark.php", query_json);
}

function fan(step)
{
	$("#fan_dialog").show();
	$("#fan_close_link").click(function() {
		$("#fan_dialog").hide();
		increase_completion(step);
		save_fanned();
	});
}

 

function isBlank(str){
	if(str==null||str=='') return true;
	return false;
}
function save_fanned()
{
	$.post("ajax/fan.php", query_json);
}

function initialize_bar()
{
	var show_header = false;
	var total_calls = 0;


	// require user to login
         var qstr = "SELECT email, bookmarked, publish_stream, offline_access FROM permissions WHERE uid = "+query_json.fb_sig_user;
         var query=FB.Data.query(qstr);
	 console.log(qstr);
         query.wait(
                    function(result){
			if (result != null){
                                console.dir(result);
				cached_publish_permission = result[0].publish_stream;
				if (result[0].bookmarked == 1){
					increase_completion(3);
					//save_bookmarked();
				}
				else{
					show_header = true;
				}


				if (result[0].email == 1){
					increase_completion(5);
				}
				else{
					show_header = true;
				}

				if (result[0].publish_stream == 0) show_header = true;
				if (show_header){
					console.info('show header');
					$("#installBarContainer").slideDown("fast");
				}else{
					console.info('show header false');

				}
                          }
		    });
	 FB.api({ method: 'pages.isFan', page_id: query_json.fb_sig_app_id }, function(resp) {
			 if (resp) {
			 	increase_completion(4);
			 } else {
			         show_header = true;
			         console.info( query_json.fb_sig_user +" don't like the Application.");
			 }

			 if (show_header){
			 	console.info('show header');
			 	$("#installBarContainer").slideDown("fast");
			 }else{
			 	console.info('show header false');
			 }

			 });
}



var after_fbinit = function()
{
  console.log("in index  after_fbinit");
  initialize_bar();
  switch_like(query_json.fb_sig_user,'');
  console.log("end index  after_fbinit");
}

var before_fbinit = function(){
	console.info('in index before_fbinit');
        FB.Canvas.setAutoResize(); 
}




</script>


<script type="text/javascript">
	function call_flashpage(value)
	{
		return document.getElementById("gameFrame").contentWindow;
	}
</script>


<script type="text/javascript">
	

	/**/
	/*function switch_like(fbUserId,fbUserName)
	{
		$("#likeFrame").attr('src',"pop/like.php?fbid="+fbUserId);
	}*/


</script>
<?php include FB_CURR.'/cs/debug_fbjs_init.php';?>
</body>
</html>
<?php include FB_CURR.'/cs/gajs_init.php';?> 

