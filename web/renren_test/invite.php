<?php
    $myself=basename(__FILE__);
    include "./header.php";
    $platform_id = "renren".$renren->user;
    $session_key = $renren->session_key;
    $user_id     = AutoIncIdGenerator::genid($platform_id);
    $db          = ServerConfig::getdb_by_userid($user_id);
	$sql_up = "select * from user_profiles where id=$user_id";
	$up = $db->fetchRow($sql_up);
	$sql_ua = "select * from user_accounts where id=$user_id";
	$ua = $db->fetchRow($sql_ua);
    if($_POST && $_POST['ids'] ){
	    $ids =  $_POST['ids'];
	    $db  = ServerConfig::getdb_by_userid(0);
	    $invitation = ModelFactory::Invitation($db);	
	if($user_id && count($ids)>0 ){ 
		$row_count = $invitation->invite($user_id,$ids);
	}
  }
?>
<div id='is_install'></div>
<div class="poker_game">
	<div class="poker_cz_banner">
    	<div class="renren_user_photo"><img src="<?php echo $up['hIcon']; ?>" alt="<?php echo $up['name']; ?>" class="boxes" ></div>
    	<p class="poker_ad_text">邀请一位朋友赠送1000游戏币啦，赶快加入吧！</p>
        <p class="poker_game_gold"><?php echo $ua['money']; ?></p>
        <p class="poker_gold"><?php echo $ua['gem']; ?></p>
   	</div>  
    
<div class="poker_main">
       <div class="poker_main_t">
	 <img src="<?php echo RenrenConfig::$resource_url; ?>img/poker_yaoqing.jpg" width="134" height="32" />
     <div class="back_game"><a href="<?php echo RenrenConfig::$canvas_host.RenrenConfig::$canvas_name; ?>"><img src="<?php echo RenrenConfig::$resource_url; ?>img/poker_backgame.jpg" width="109" height="28" /></a></div>
     </div>
      <div class="poker_invite">  
	  <xn:request-form content="邀请一位朋友赠送1000游戏币啦，赶快加入吧！&lt;xn:req-choice url=&quot;<?php  echo RenrenConfig::$group_url; ?> &quot; label=&quot;逛逛论坛&quot;&gt;&lt;xn:req-choice url=&quot;<?php  echo RenrenConfig::$canvas_host.RenrenConfig::$canvas_name; ?> &quot; label=&quot;接受邀请&quot;&gt;" action="">
	     <xn:multi-friend-selector-x actiontext="邀请一位朋友赠送1000游戏币啦，赶快加入吧！" max="500" mode="naf"/> </xn:request-form>
		 
</div>
      <div class="poker_main_b"> </div>
    </div>
</div>
  <xn:else>
<script>
var auth = false;
function authOK()
{
	auth = true;
	document.setLocation("<?php echo RenrenConfig::$canvas_host.RenrenConfig::$canvas_name;?>?"+Math.random() ) ;
}
function authKO()
{
	auth = false;
	document.setLocation("http://app.renren.com/app/apps/list?origin=119") ;
}
var is_install=document.getElementById('is_install');
if(!Session.isApplicationAdded() || is_install == null ){
	Session.requireLogin(authOK,authKO);
}
</script>
  </xn:else>
</xn:if-is-app-user>