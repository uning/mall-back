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
width:740px;
border:1px solid #BEB9B9;
margin:5px;
display:none;
padding:5px;
}
.xnid{
text-align: right; font-size: 12px;
width:100%;
margin-bottom:3px;
margin-top:4px;
}
.bottom {
text-align:center;
width:800px;
margin-top:5px;
}
.bottom a img{
border: 0;
}
.share-wrapper {
position:absolute;
right:0;
top:0;
}
.help {
color:#444444;
height:18px;
line-height:18px;
margin-left:3px;
margin-top:4px;
width:100%;
}
</style>
<div id='is_install'></div>
<div id='content' style="background-color: #ffffff;">
    <div class='container'>
        <div class='canvas'>
			<div class='share-wrapper'>
				<xn:share-app-button/> 
			</div>
	        <xn:iframe scrolling="no" src="<?php echo RenrenConfig::$callback_url?>if/index.php?a=<?php echo $_REQUEST['a']; ?>" height="770" width="800" frameborder="0"/>
		</div> 
		
		<div class="help">
			    <span style="width: 625px; float: left;">
					[<a href='http://group.renren.com/GetThread.do?id=331584998&parentpage=&curpage=0&label=&tribeId=336701942' target='_blank'>如何清除浏览器缓存</a>]&nbsp;
				  
				</span>
				<span style="width: 175px; float: right; text-align: right;">
					[<a target="_blank" href="http://msg.renren.com/SendMessage.do?id=253382225">联系客服</a>]&nbsp;
				</span>
	  </div>
		

		<div class='bottom'>
			<a target='_blank' href='http://page.renren.com/livemall'>
				<img src="<?php echo RenrenConfig::$resource_urlp ?>images/bottom.png"></img>
			</a>
		</div>
		
		<div class='xnid'>商场门牌号: <?php echo $_REQUEST['xn_sig_user'] ?></div>
		
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
