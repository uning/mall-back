<?php
    $myself=basename(__FILE__);
    include "./header.php";
?>
<div id='is_install'></div>
<div>
    <xn:iframe scrolling="no" src="<?php echo RenrenConfig::$resource_url?>faq.html" height="600" width="760" frameborder="0"/>
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
