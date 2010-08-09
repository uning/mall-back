<?php
 require_once '../../config.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<table>
<tr>
<?php 
	$id = '4';
	$user = new TTUser($id);
	$frd = $user->getf(TT::FRIEND_STAT);
	print_r($frd);
	$content = '<fb:name uid="loggedinuser"" firstnameonly="true" shownetwork="false"/>正在玩<a href="$facebook->canvas_url">開心酒店</a>，推薦你也來玩一把!<br><fb:req-choice url="kokopio" label="開始玩開心酒店"/>';
	$gift = $_REQUEST["gift"];

	if($gift!=NULL&&$gift!=''){
		$content = '<fb:name uid="loggedinuser"" firstnameonly="true" shownetwork="false"/>用Mall送给你<a href="$facebook->canvas_url"><img src="../giftIcon/'.$gift.'"/></a>,快来领悟吧!<br/><fb:req-choice url="kokopio" label="開始玩mall"/>';
		echo '<td>';
		echo '<img src="../giftIcon/'.$gift.'"/>';
		echo '</td>';
	}
?>

<td>
<fb:serverfbml>
  <script type="text/fbml">
    <fb:request-form action="inviteCallBack.php?<?php echo 'gift='.$gift ?>"
                     method="POST"
                     invite="true"
                     type="Mall"
                     content="<?php echo htmlentities($content,ENT_COMPAT,'UTF-8'); ?>">
      <fb:multi-friend-selector showborder="false" condensed="true" 
                                bypass="cancel"
                                cols=4
								max="35" selected_rows="4" style="width:550px" unselected_rows="7"
                                actiontext=""/>
<fb:request-form-submit /> 
</fb:request-form>

  </script>
</fb:serverfbml>
</td>
</tr>
</table>
</body>
</html>
<script src="<?php echo FB::$resource_urlp;?>js/loader.js"></script>
<script type="text/javascript">
var config = {
		//log:1,//init log? server can force debug, just for develop
		fb:1,//init fb?
		//fbd:1,//init fb debug? 
		logcb:function(r){//log init callback
			console.log('log callback' +  window.location.href);
		},
		after_fbinit:function(){//before FB.init callback
			FB.getLoginStatus(function(r){
				if(r.status !='connected'){
					console.log('not connected');
				}
				if(r.session){
					if(r.session.uid){
					   query_json.fb_sig_user = r.session.uid||query_json.fb_sig_user;
					   PL.conf('uid',query_json.fb_sig_user);
					   IBar.show();
					   init_popFrame(); 
					}else{
						console.log('no userid get');
					}
				}
			})
			
		},
		before_fbinit:function(){//after FB.init callback
			 FB.Canvas.setAutoResize(); 
		},
		cb:function(){//after config callback
			
		}
};

PL.init('../../static/js/fb_config.js',config);
</script>