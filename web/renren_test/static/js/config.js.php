//load all js,every time downloaded
<?php require_once '../../config.php';?>
<script type="text/javascript">


var $config = {
    debug:1,
	jspre : [ '',
			'http://127.0.0.1/work/mall/backend/web/facebook/static/js/' ],
	 api_key : "<?php echo FB::$api_key;?>",
     app_id : "<?php echo FB::$app_id;?>",
     channel_path: "<?php echo FB::$reciever_url;?>",
     canvas_url: "<?php echo FB::$canvas_url;?>",
	 jsfiles : [ [ 'fb_jsflash.js', 1 ], [ 'delegator.js', 1 ],
			[ 'jquery.boxy.js', 1 ], [ 'jsDump-1.0.0.js', 1 ], [ 'log.js', 1 ],
			[ 'jquery-1.4.2.min.js', 1 ] ]
}

</script>