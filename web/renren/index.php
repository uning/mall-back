<?php
require_once('config.php');
    //$myself=basename(__FILE__);
    include "./header.php";
  // $platform_id = "renren".$renren->user;
    //$session_key = $renren->session_key;
?>
<style>
.footnotice {
background:none repeat scroll 0 0 #FCFAD4;
border:1px solid #BEB9B9;
margin:5px;
padding:5px;
}
</style>
<div id='is_install'></div>
<div id='content'>
    <div class='container'>
        <div class='canvas'>
	        <xn:iframe scrolling="no" src="<?php echo RenrenConfig::$callback_url?>if/index.php?a=<?php echo $_REQUEST['a']; ?>" height="855" width="800" frameborder="0"/>
		</div> 
		<div class='footnotice'>
			健康忠告：抵制不良游戏，拒绝盗版游戏。注意自我保护，预防受骗上当。适度游戏益脑，沉迷游戏伤身。合理安排时间，享受健康生活。				    				
		</div>
    </div>
</div>

<xn:else>
<img src="<?php echo RenrenConfig::$resource_urlp ?>images/genericbg.jpg"/>
<script>
var auth = false;
function authOK()
{
	auth = true;
	document.setLocation("<?php echo RenrenConfig::$canvas_url;?>?"+Math.random() ) ;
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
