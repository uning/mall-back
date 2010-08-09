<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<title>Invite Friends</title>

<meta name="layout" content="facebook_xfbml"></meta>

<script type="text/javascript">
function facebook_loaded()
{
	parent.invite_loaded();
}
</script>

</head>
<body>
<script
	src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php"
	type="text/javascript"></script>

<script type="text/javascript">
var api_key = "<?php
echo FB::$api_key;
?>";
var channel_path = "<?php
echo FB::$reciver_url;
?>";
FB.init(api_key, channel_path);
</script>
<?php
include FB_CURR . '/cs/fbjs_init.php';
?>

<fb:serverFbml>

	<fb:fbml>
		<fb:request-form
			action="http://dynamic00.nightclub-city.com/nlc/invite/dialog?post=skip&inviteType=neighbor&fb_sig_user=657589045&fb_sig_session_key=2.9cpk6TjxMxvVgOiFe3oppQ__.3600.1279292400-657589045&fb_sig_ss=qqcr7uOVak51_ImiBxdHlA__"
			content="Come be my neighbor in &lt;fb:application-name/&gt; - where you can create your own club, DJ cool music and more!&lt;fb:req-choice url='http://apps.facebook.com/nightclubcity/?refer=inviteNPop&channel=friendInviteLink&action=accept&type=pop&inviteType=neighbor&t=657589045' label='Play Now!'/&gt;"
			invite="true" method="post" type="Nightclub City"
			url="http://apps.facebook.com/nightclubcity/?refer=inviteNPop&channel=friendInviteLink&action=accept&type=pop&inviteType=neighbor&t=657589045">

			<fb:multi-friend-selector condensed="true" exclude_ids="" max="35"
				selected_rows="4" style="width:620px" unselected_rows="7"></fb:multi-friend-selector>
			<div style="margin-top: 10px"><fb:request-form-submit></fb:request-form-submit>
			<span class="inputbutton inputsubmit"
				style="padding: 4px 8px; margin-left: 3px"><a
				href="http://dynamic00.nightclub-city.com/nlc/invite/dialog?post=skip&inviteType=neighbor&fb_sig_user=657589045&fb_sig_session_key=2.9cpk6TjxMxvVgOiFe3oppQ__.3600.1279292400-657589045&fb_sig_ss=qqcr7uOVak51_ImiBxdHlA__"
				style="color: white">Skip</a></span></div>
		</fb:request-form>
	</fb:fbml>
</fb:serverFbml>

</body>
</html>
