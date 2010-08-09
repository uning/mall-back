<?php include 'config.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>

<script src="<?php echo FB::$resource_urlp;?>js/loader.js"></script>
<?php include FB_CURR.'/cs/debug_fbjs_init.php';?>
<script type="">
PL.init('<?php echo FB::$resource_urlp;?>js/fb_config.js.php',
		function(r){
	  
        init_log();
       
   Log.info("dfd");
	
});



</script>

</body>
</html>
