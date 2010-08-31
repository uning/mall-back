<?php
require_once('config.php');
include "./header.php";

$pid =   $_REQUEST['xn_sig_user'];  
$sess=TTGenid::getbypid($pid);
$user = new TTUser($sess['id']);
$gem = $user->chGem(0);
	
 
?> 

 <style>  
 
#content {
height: 700px;
font:12px/1.5 tahoma,arial,微软雅黑,宋体,sans-serif;
}
#header .logo {
    width:195px;
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

#pay-body{
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
background:url("<?php echo RenrenConfig::$resource_urlp ?>/images/gem.png") no-repeat scroll 5px center #FFFAEF;
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
.pay-form {
padding:10px 30px;
border-top:medium none;
}
.pay-form h2 {
font-size:14px;
font-weight:normal;
padding:10px 30px;
}
.pay-type{
padding-left: 30px;
}
.pay-type li div {
text-indent:-9999px;
}
.pay-type li {
	background:url("<?php echo RenrenConfig::$resource_urlp ?>/images/payment.png") no-repeat scroll center top transparent;
	float:left;
	height:210px;
	padding:10px 40px;
	position:relative;
	text-align:center;
	width:86px;
}
.pay-type li input {
background:url("<?php echo RenrenConfig::$resource_urlp ?>/images/payment.png") no-repeat scroll center top transparent;
 background-position: -19px -205px;
border:0 none;
bottom:24px;
cursor:pointer; 
height:50px;
position:absolute;
right:26px;
width:120px;
}
.pay-type li.gem-100 {
background-position: 0;
}
.pay-type li.gem-200 {
background-position:-166px 50%;
}
.pay-type li.gem-500 {
background-position:-332px 50%;
}
.pay-type li.gem-1000 {
background-position:-498px 50%
}
.payment-type label{
padding-right:20px;
}
 </style>

<script type="text/javascript">
 
function callback(responseItem){
	var errCode = responseItem.getErrorCode();
	var errMsg = responseItem.getErrorMessage();
	var params = responseItem.getData();
	console.debug("params",params);
	var msg;
	if (errCode == Payment.ResponseCode.OK) {
 		msg = "平台充值结果为：成功充值" +params.message  +"。如果显示结果不对，尝试刷新页面。";
		
	 
		var alert_dialog = new Dialog(
				Dialog.DIALOG_ALERT, 
				{message: msg,title: '充值结果',callBack:function(){ 
					var addGem = parseInt(params.amount) * 10; 
					var gemNode = document.getElementById('gemValue'); 
					console.debug("gemNode",gemNode.getInnerHTML(),addGem);
					gemNode.setTextValue(parseInt(gemNode.getInnerHTML() ) + parseInt(addGem) );
				} }
		);
	}
	else if (errCode == Payment.ResponseCode.USER_CANCELLED) {
		msg = "用户取消了消费。";
	}
	else {
		msg = "由于某种原因没支付成功。";
	}
	
}

function requestPayment(amount,gem,message) {
	var payType = Payment.PaymentType.PAYMENT;
 

	var params = {}; 
	params[Payment.Field.AMOUNT] = amount; 
	params[Payment.Field.MESSAGE] = message;
	params[Payment.Field.PARAMETERS] = "{name:'gem',amount:"+amount+",gem:"+gem+",message:"+message+",pid:<?php echo $pid;?>}"; 
	params[Payment.Field.PAYMENT_TYPE] = payType; 
	params[Payment.Field.SANDBOX] = false;
	var itemParams1 = {}; 
	itemParams1[Payment.BillingItem.SKU_ID] = 'gem'; 
	itemParams1[Payment.BillingItem.PRICE] = amount; 
	itemParams1[Payment.BillingItem.COUNT] = gem; 
	itemParams1[Payment.BillingItem.DESCRIPTION] = message; 
	params[Payment.Field.ITEMS] = [itemParams1];
	//可以有多个item构成一个购物车
	Payment.requestPayment(callback,params);
}

</script>

 
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
					<li class="payment" ><a  class="active" href="<?php echo RenrenConfig::$canvas_url;?>pay.php"   id ="pay">充值</a></li>
				</ul>
				</div>
				</div>
			</div>
		
		    <div id='pay-body'>
				<div class='user-info'>
					<span class='avatar'>
						<xn:profile-pic uid="<?php echo $pid;?>" linked="false" size="tiny" />
					</span>
					<h2><xn:name uid="<?php echo $pid;?>" linked="false" shownetwork="false" /></h2>
					<p>
						<label>
							宝石余额: <span class='gem' id='gemValue'><?php echo $gem; ?></span>
						</label>
					</p>
				</div>
				
				<div class='pay-form'>
				     
					<h2>选择你要充值的面值</h2>
					
					<ul class="pay-type clearfix">
						<li class="gem-100">
							<div title="充值100个宝石">充值100个宝石</div>
							<p><input type="button" onclick="requestPayment(10,100,'100个宝石');return false;" value="   " class="btn-red"></p>
							<div>价格：<span style="font-weight: bold; color: #009900;">10</span>个人人豆</div>
						</li>
						<li class="gem-200">
							<div title="充值200个宝石">充值200个宝石</div>
							<p><input type="button" onclick="requestPayment(20,200,'200个宝石');return false;" value="   " class="btn-red"></p>
							<div>价格：<span style="font-weight: bold; color: #009900;">20</span>个人人豆</div>
						</li>
						<li class="gem-500">
							<div title="充值500个宝石">充值500个宝石</div>
							<p><input type="button" onclick="requestPayment(50,500,'500个宝石');return false;" value="   " class="btn-red"></p>
							<div>价格：<span style="font-weight: bold; color: #009900;">50</span>个人人豆</div>
						</li>
						<li class="gem-1000">
							<div title="充值1000个宝石">充值1000个宝石</div>
							<p><input type="button" onclick="requestPayment(100,1000,'1000个宝石');return false;" value="   " class="btn-red"></p>
							<div>价格：<span style="font-weight: bold; color: #009900;">100</span>个人人豆</div>
						</li>
					</ul>
					
					<h2>宝石可以用来使货车进货加速或者购买漂亮的店面装饰来增加人气</h2>
				</div> 
			</div>			 
		
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
	document.setLocation("<?php echo RenrenConfig::$canvas_url;?>pay.php?"+Math.random() ) ;
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

