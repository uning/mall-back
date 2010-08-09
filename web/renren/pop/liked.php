<?php
require_once '../../config.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
		<title>Supper Mall</title>
        
		<meta name="layout" content="facebook_xfbml"></meta>
		<meta property="og:title" content="Kun's Mall"/>
		<meta property="og:site_name" content="supermall.playcrab.com"/>
		<meta property="og:image" content="<?php echo FB::$resource_urlp;?>images/default.png"/>
		<meta property="og:type" content="game"/>

		<meta property="fb:app_id" content="<?php echo FB::$app_id; ?>"/>


		
		<script type="text/javascript">
			top.location.href = "http://apps.facebook.com/nightclubcity/?refer=like";
		</script>

		<style type="text/css">
			body {
				margin:0;
				padding:0;
				font-family: Lucida Grande, Verdana, Arial, Helvetica, sans-serif;
				font-size:12px;
				font-weight:bold
			}
		</style>

	
    </head>
    <body>
<?php includeFB_CURR.'/cs/fbjs_init.php';?>
        
		<div>
			<a href="<?php echo FB::$canvas_url;?>/?refer=like" target="_top">Redirecting!</a>
		</div>
		<script type="text/javascript">
			FB_RequireFeatures(["XFBML"], function() {
				FB.Facebook.init(api_key, channel_path);
				facebook_loaded();
			});
		</script>
    </body>
</html>
