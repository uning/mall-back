<?php
require_once('config.php');
require_once('pop/freeGift.php');

//include "./header.php";
$linkid = $_REQUEST['fid'];
$irec = false;
$pid = false;
$user =  null;
if($linkid){
	$tw = TT::LinkTT();
	$irec = $tw->getbyuidx('lid',$linkid);;
    $pid = $irec['uid'];
    $oid = $irec['oid'];
    $sess=TTGenid::getbypid($pid);	
	$user = new TTUser($sess['id']);
}
$new = 0;
if($_REQUEST['new']){
	$new = 1;
}
$mypid =   $_REQUEST['xn_sig_user'];  
TTLog::record(array('m'=>'help_open_shop','tm'=> $_SERVER['REQUEST_TIME'],'u'=>$mypid,'sp2'=>$new,'sp1'=>$oid));
$sess=TTGenid::getbypid($pid);
$myuser = new TTUser($sess['id']);
 	
 if(!$oid||!$help[$oid]){
?> 
<xn:redirect url="<?php echo RenrenConfig::$canvas_url;?>"/>
<?php exit;}
	$obj = $user->get_help($oid);
	
?>
 <style>  
 
#content {
height: 700px;
font:12px/1.5 tahoma,arial,微软雅黑,宋体,sans-serif;
}
#header .logo {
    width: 195px;
    height: 46px;
    background: url("<?php echo RenrenConfig::$resource_urlp; ?>/images/logo.png?v=1") no-repeat 10px center transparent;
    text-indent: -9999px;
    float: left;
}

#header .logo  a {
    display: block;
    height: 36px;
} 
 
#navga ul { 
    margin: 0 0 5px 0px;
    padding-top: 14px;
}

#navga ul li {
    float: left;
    cursor: pointer;
    padding: 0 2px;
}

#navga ul li a {
    display: block;
    text-indent: -9999px;
    background: url("<?php echo RenrenConfig::$resource_urlp; ?>/images/nav.png") no-repeat left top;
    width: 95px;
    height: 32px; 
} 
#navga ul li.game a {
    background-position: 0 -1px;
}


#navga ul li.game a.active, #navga ul li.game a:hover {
    background-position: 0 -45px;
    outline:none;
	blr:expression(this.onFocus=this.blur());
	
}

#navga ul li.freegift a {
    background-position: 0 -88px;
}

#navga ul li.freegift a.active, #navga ul li.freegift a:hover {
    background-position: 0 -133px;
	outline:none;
	blr:expression(this.onFocus=this.blur());
}

#navga ul li.invite a {
    background-position: 0 -176px;
}

#navga ul li.invite a.active, #navga ul li.invite a:hover {
    background-position: 0 -221px;
}

/*payment*/
#navga ul li.faq a {
    background-position: 0 -263px;
}

#navga ul li.faq a.active, #navga ul li.faq a:hover {
    background-position: 0 -308px;
    outline:none;
	blr:expression(this.onFocus=this.blur());
}

#navga ul li.forum a {
    background-position: 0 -351px;
}

#navga ul li.forum a.active, #navga ul li.forum a:hover {
    background-position: 0 -396px;
}
#navga ul li.payment a {
    background-position: 0 -440px;
}

#navga ul li.payment a.active, #navga ul li.payment a:hover {
    background-position: 0 -487px;
    outline:none;
	blr:expression(this.onFocus=this.blur());
}
#navga ul li.problem a {
    background-position: 0 -532px;
}

#navga ul li.problem a.active, #navga ul li.problem a:hover {
    background-position: 0 -579px;
    outline:none;
	blr:expression(this.onFocus=this.blur());
}

#cinema-main{
 height: 600px; 
}
.user-info {
border-bottom:1px dotted #CCCCCC;
height:60px;
margin:0 20px;
padding:10px 10px 10px 90px;
}
.user-info h2{
text-align:left;
}
.user-info p{
padding:10px 0 0;
text-align:left;
}
.user-info p label {
border:1px solid #E2C925;
margin-right:10px;
padding:5px 20px 6px 25px;
}
.user-info p label span {
color:#336699;
padding-left:5px;
}
.user-info .avatar{
-moz-border-radius:3px 3px 3px 3px;
-moz-box-shadow:1px 1px 2px #CCCCCC;
border:1px solid #B2B2B2;
display:block;
float:left;
height:50px;
margin-left:-70px;
padding:2px;
}
.user-info .avatar img{
width: 50px;
height: 50px;
}
.cinema-body {
padding:10px 30px;
border-top:medium none;
}
.cinema-body h2 {
font-size:14px;
font-weight:normal;
padding:10px 30px;
}  

.formsubmit {
	border-style: solid;
	border-color: #d9dfea #0e1f5b #0e1f5b #d9dfea;
	border-width: 1px;
	margin: 1px 5px;
	padding: 3px 10px;
	background-color: #3b5998;
	color: white;
	font-size: 12px;
	font-weight: bold;
	text-decoration: none;
	height: 25px;
}
 </style>

<xn:if-is-app-user>
<form action="cinema_help_open.php" method="post">
<div id='is_install'></div>	

<div id='content'>
    <div class='container'>
        <div class='canvas'>
			<div id="header">
				<div id="navga">
				<div class="logo"><a href="<?php echo RenrenConfig::$canvas_url;?>" target="_top" title="开始游戏!">logo</a></div>
			   <div id="tabs">
				<ul class="clearfix tcenter">       
					<li class="game" id="flashTab" ><a  href="<?php echo RenrenConfig::$canvas_url;?>" >游戏</a></li>
					<li class="freegift"><a href="<?php echo RenrenConfig::$canvas_url;?>?a=freeGift" id="freeGift" >免费礼物</a></li>
					<li class="invite" ><a href="<?php echo RenrenConfig::$canvas_url;?>?a=invite" >邀请好友</a></li>
					<li class="faq"><a id='faq'  href="<?php echo RenrenConfig::$canvas_url;?>?a=faq" >常见问题</a></li>
					<li class="forum"><a href="<?php echo RenrenConfig::$group_url; ?>" class="fullpage" target='_blank' id="forum">论坛</a></li>
					<li class="payment" ><a  href="<?php echo RenrenConfig::$canvas_url;?>pay.php"   id ="pay">充值</a></li>
				</ul>
				</div>
				</div>
			</div>
		
		    <div id='cinema-main'>
				<div class='user-info'>
					<span class='avatar'>
						<xn:profile-pic uid="<?php echo $pid;?>" linked="false" size="tiny" />
					</span>
					<h2><xn:name uid="<?php echo $pid;?>" linked="false" shownetwork="false" /></h2>
					<p>
						<label>
							声望等级: <span class='gem' id='gemValue'><?php echo $user->getLevel(); ?></span>
						</label>
					</p>
				</div>
				<input type="hidden" name="linkid" value="<?php echo $linkid;?>"/>
				<input type="hidden" name="fid" value="<?php echo $mypid;?>"/>
				<div class='cinema-body'> 
					<?php 
						if($obj['help']&&$obj['help']!='null')
						$count = count($obj['help']);
						else $count = 0;
					?>
					<h2><xn:name uid="<?php echo $pid;?>" linked="false" shownetwork="false" />
					<?php if($obj['help'][$mypid])
							echo '已经获得了你的帮助';
						else if($count<$help[$oid]['need_num']){ 
								echo '需要你的帮助才能开启'.$help[$oid]['name'];
							}
						if($count>=$help[$oid]['need_num']){
							echo ',ta的'.$help[$oid]['name'].'已经开启';
						}
						if(!$obj['help'][$mypid]&&$count<$help[$oid]['need_num']){?>
						<input type="submit"  value="帮助ta" style="cursor: pointer;padding-left: 30px;" class="formsubmit"/>
						<?php }?>
					</h2>
					<div class="pictue">
						<img src="<?php echo RenrenConfig::$resource_urlp; ?>/images/help/<?php echo $help[$oid]['bp'];?>"/>
					</div>
					
					<h2>已经有<?php echo $count;?>位董事长帮助过<xn:name uid="<?php echo $pid;?>" linked="false" shownetwork="false" />了</h2>
					<table cellpadding="0" cellspacing="0">
					<?php 
					if($obj['help']&&$obj['help']!='null'){
						echo '<tr>';
					foreach ($obj['help'] as $k=>$v){?>
					<td align="center" valign="bottom">
					<span class='avatar'>
						<xn:profile-pic uid="<?php echo $k;?>" linked="true" size="tiny" />
					</span>
					</td>
					<?php }
					echo '</tr><tr>';
					foreach ($obj['help'] as $k=>$v){?>
					<td align="center" valign="top" style="font-weight: lighter;">
					<xn:name uid="<?php echo $k;?>" linked="true" shownetwork="false" />
					</td>
					<?php }
					echo '</tr>';
					}
					?>
					</table>
				</div> 
			</div>			 
		
		</div>
	</div>
</div>
 
</form>

<xn:else>
<img src="<?php echo RenrenConfig::$resource_urlp ?>images/genericbg.jpg"/>
<script>
var auth = false;
function authOK()
{
	auth = true;
	document.setLocation("<?php echo RenrenConfig::$canvas_url;?>cinema.php?linkid=<?php echo $linkid; ?>?&new="+Math.random() ) ;
}
function authKO()
{
	auth = false;
	document.setLocation("<?php echo RenrenConfig::$canvas_url;?>") ;
}
var is_install=document.getElementById('is_install');
if(!Session.isApplicationAdded() || is_install == null ){
	Session.requireLogin(authOK,authKO);
}
</script>
</xn:else>
</xn:if-is-app-user>

