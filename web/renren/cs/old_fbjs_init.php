
<!-- end fbjs init -->
<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>
<script type="text/javascript">
var api_key = "<?php echo FB::$api_key;?>";
var channel_path = "<?php echo FB::$reciever_url;?>";
var canvas_url = "<?php echo FB::$canvas_url;?>";
FB.init(api_key, channel_path);
</script>			
<!-- end fbjs init -->
