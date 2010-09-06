<?php
require_once('config.php');
    //$myself=basename(__FILE__);
    include "./header.php";
  // $platform_id = "renren".$renren->user;
    //$session_key = $renren->session_key;
?>
<style>
.fan-wrapper{
 position:absolute;
right:60px;
top:0;
}

.share-wrapper {
position:absolute;
right:0;
top:0;
}
</style>

<div id='is_install'></div>
<div id='content' style="background-color: #ffffff;">
    <div class='container'>
		<div class='canvas'>
            <div class='fan-wrapper'>
              <xn:app-fan/>
            </div>
			<div class='share-wrapper'>
				<xn:share-app-button/> 
			</div>
	        <xn:iframe scrolling="no" src="<?php echo RenrenConfig::$callback_url?>if/index.php?a=<?php echo $_REQUEST['a']; ?>" height="1100" width="800" frameborder="0"/>
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
	document.setLocation("<?php echo RenrenConfig::$canvas_url;?>?f=<?php echo $_REQUEST['f'];?>&from=<?php echo $_REQUEST['from'];?>" ) ;
}

function authKO()
{
	auth = false;
	document.setLocation("<?php echo RenrenConfig::$canvas_url;?>?"+Math.random() ) ;

}
var is_install=document.getElementById('is_install');
if(!Session.isApplicationAdded() || is_install == null ){
	Session.requireLogin(authOK,authKO);
}
</script>

</xn:else>

</xn:if-is-app-user>
<img style='width:0;height:0;' src='http://img.tongji.linezing.com/2051209/tongji.gif' />
