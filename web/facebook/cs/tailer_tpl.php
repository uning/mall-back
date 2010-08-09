

<fb:google-analytics uacct="UA-5208795-40" ulink="1" page="/<?php echo '/cb/'.$lang.'/'.$myself;?>" />
<div align='center'><a target=_blank href='<?php echo FB::$iframe_urlp;?>privacy.php'>Privacy Policy</a>&nbsp;|&nbsp;<a target=_blank href='<?php echo FB::$iframe_urlp;?>tos.php'>Terms of Service</a>&nbsp;|&nbsp;<a target=_blank href= " http://cs.6waves.com/contact.php?appid=<?php echo FB::$app_id;?>&uid=<?php echo $platform_id;?>" >Contact Support</a></div>
<fb:else>
<fb:redirect url="http://www.facebook.com/tos.php?api_key=<?php echo FB::$api_key;?>&v=1.0&next=<?php  echo FB::$canvas_url; ?>"/>
</fb:if-is-app-user>
<pre>
<?php
print_r($_POST);
print_r($_GET);
?>
</pre>
