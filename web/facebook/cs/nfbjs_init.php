
<div id="fb-root"></div>
<script>
var api_key = "<?php echo FB::$api_key;?>";
var app_id = "<?php echo FB::$api_id;?>";
var channel_path = "<?php echo FB::$reciever_url;?>";
var canvas_url = "<?php echo FB::$canvas_url;?>";
  window.fbAsyncInit = function() {
    FB.init({
      appId  : '<?php echo FB::$api_id;?>',
      status : true, // check login status
      cookie : true, // enable cookies to allow the server to access the session
      xfbml  : true  // parse XFBML
    });
  };

  (function() {
    var e = document.createElement('script');
    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
    e.async = true;
    document.getElementsByTagName("head")[0].appendChild(e);
  }());
</script>

