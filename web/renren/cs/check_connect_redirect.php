<script>
	var query_str =  window.location.href.slice(window.location.href.indexOf('?') + 1);
	if(query_str.indexOf('fb_sig_user=')<0){
		top.window.location = 'http://www.facebook.com/login.php?api_key=<?php echo FB::$api_key;?>&req_perms=email%2Cpublish_stream&return_session=1&sdk=joey&session_version=3&v=1.0';	
	}
</script>
