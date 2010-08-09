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
	 <img src="<?php echo RenrenConfig::$resource_url; ?>img/poker_jinbicz.jpg" width="134" height="32" />
     <div class="back_game"><a href="<?php echo RenrenConfig::$canvas_host.RenrenConfig::$canvas_name;?>/index.php"><img src="<?php echo RenrenConfig::$resource_url; ?>img/poker_backgame.jpg" width="109" height="28" /></a></div>
     </div>
	<div class="poker_pay"> 
      	<div class="poker_pay_top"><a <?php if($myself=='pay.php') echo "class='select'";?> href="<?php echo RenrenConfig::$canvas_host.RenrenConfig::$canvas_name;?>/pay.php">游戏充值</a> <a <?php if($myself=='pay_history.php') echo "class='select'";?> href="<?php echo RenrenConfig::$canvas_host.RenrenConfig::$canvas_name;?>/pay_history.php">充值记录</a></div>
		
		
		
<div  style="display:block;">
<div class="pay-history used">
  <span class="table-title">充值记录</span>
  <span>共<?php echo count($orders);?>条</span>
  <table>
   <tbody>
   <tr class="head"><th>日期</th><th>充值宝石数量</th><th>人人豆数量</th></tr>
   <?php foreach($orders as $key=>$o){ ?>
 
     <tr><th><?php echo $o->getAttr("paid_at");?></th><th><?php echo $o->getAttr("gem");?></th><th><?php echo $o->getAttr("amount");?></th><th></th></tr>
	 
	 <?php }?>
   </tbody>
  </table>
  <div class="pager">
    <div class="pager-bottom clearfix">
       <ol class="pagerpro"/>
    </div>
  </div>
</div>
<?php if(false){ ?>
<div class="pay-history">
  <span class="table-title">消费记录</span>
  <span>共0条</span>
  <table>
   <tbody>
     <tr class="head"><th>日期</th><th>充值宝石数量</th><th>人人豆数量</th><th>充值方式</th></tr>
   </tbody>
  </table>
  <div class="pager">
    <div class="pager-bottom clearfix">
       <ol class="pagerpro"/>
    </div>
  </div>
</div>
<?php  }  ?>

</div>		
		
		
		
	</div>
      <div class="poker_main_b">  </div>
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