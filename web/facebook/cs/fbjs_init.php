
<script>
var api_key = "<?php echo FB::$api_key;?>";
var app_id = "<?php echo FB::$app_id;?>";
var channel_path = "<?php echo FB::$reciever_url;?>";
var canvas_url = "<?php echo FB::$canvas_url;?>";
var before_fbinit = before_fbinit || function(){};
var after_fbinit  = after_fbinit  || function(){};
window.fbAsyncInit = function(){
        before_fbinit();
        FB.Canvas.setAutoResize(); 
	FB.init({
		appId  : app_id,
  	        apiKey : api_key,
                status : true, // check login status
                cookie : true, // enable cookies to allow the server to access the session
                xfbml  : true  // parse XFBML
       });
       after_fbinit();
};


(function() {
 console.log("fbjs_init");
 var e = document.createElement('script');
 e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
 e.async = true;
 //document.getElementsByTagName("head")[0].appendChild(e);

 var fbr = document.createElement('div');
 fbr.id = 'fb-root';
 fbr.appendChild(e);
 document.getElementsByTagName('body')[0].appendChild(fbr);
 console.log("fb init ok "+document.getElementById("fb-root"));
}());
</script>


