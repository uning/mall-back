<?php
    $myself=basename(__FILE__);
    include "./header.php";
    $platform_id = "renren".$renren->user;
    $session_key = $renren->session_key;
?>
<div id='is_install'></div>
<div id='content'>
    <div class='container'>
        <div class='canvas'>
	        <xn:iframe scrolling="no" src="<?php echo RenrenConfig::$resource_url?>index_bar.php" height="855" width="760" frameborder="0"/>
        </div> 
    </div>
</div>

<xn:else>
<img src="<?php echo RenrenConfig::$resource_url ?>/img/install_background.jpg"/>
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
