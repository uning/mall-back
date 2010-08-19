<?php
require_once('config.php');
include "./header.php";

$pid =   $_REQUEST['xn_sig_user'];  
$sess=TTGenid::getbypid($pid);
$user = new TTUser($sess['id']);

 
?> 

 <style>  
#content {
height: 700px;
}
#header .logo {
    width: 165px;
    height: 46px;
    background: url("<?php echo RenrenConfig::$resource_urlp; ?>/images/logo.png?v=1") no-repeat center;
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
    background-position: 0 -352px;
}

#navga ul li.forum a.active, #navga ul li.forum a:hover {
    background-position: 0 -397px;
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


.user-info {
border-bottom:1px dotted #CCCCCC;
height:60px;
margin:0 20px;
padding:0 10px 10px 90px;
}
.user-info .avatar a{
-moz-border-radius:3px 3px 3px 3px;
-moz-box-shadow:1px 1px 2px #CCCCCC;
border:1px solid #B2B2B2;
display:block;
float:left;
height:50px;
margin-left:-70px;
padding:2px;
}

 </style>

<script type="text/javascript">

function callback(responseItem){
	var errCode = responseItem.getErrorCode();
	var errMsg = responseItem.getErrorMessage();
	var params = responseItem.getData();
	var msg;
	if (errCode == Payment.ResponseCode.OK) {
		msg = "成功了。";
	}
	else if (errCode == Payment.ResponseCode.USER_CANCELLED) {
		msg = "用户取消了消费。";
	}
	else {
		msg = "由于某种原因没支付成功。";
	}
	msg += "平台返回 错误消息为：" + errMsg;
	var alert_dialog = new Dialog(
			Dialog.DIALOG_ALERT, 
			{message: msg,title: '提示框标题' }
	);
}

function requestPayment() {
	var payType = Payment.PaymentType.PRESENT;
	if (document.getElementById('paymentType').getChecked()) {
		payType = Payment.PaymentType.PAYMENT;
	}
	else if (document.getElementById('creditType').getChecked()) {
		payType = Payment.PaymentType.CREDIT;
	}
	else if (document.getElementById('peerType').getChecked()) {
		payType = Payment.PaymentType.PEER;
	}

	var params = {}; 
	params[Payment.Field.AMOUNT] = parseInt(document.getElementById('amount').getValue()); 
	params[Payment.Field.MESSAGE] = document.getElementById('message').getValue(); 
	params[Payment.Field.PARAMETERS] = '{name:"tulip"}'; 
	params[Payment.Field.PAYMENT_TYPE] = payType; 
	params[Payment.Field.SANDBOX] = document.getElementById('sandbox').getChecked(); 
	var itemParams1 = {}; 
	itemParams1[Payment.BillingItem.SKU_ID] = 'test_sku1'; 
	itemParams1[Payment.BillingItem.PRICE] = 15; 
	itemParams1[Payment.BillingItem.COUNT] = 2; 
	itemParams1[Payment.BillingItem.DESCRIPTION] = 'demo description red flower'; 
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
				<div class="logo"><a href="http://apps.renren.com/livemall/" target="_top" title="开始游戏!">logo</a></div>
			   <div id="tabs">
				<ul class="clearfix tcenter">       
					<li class="game" id="flashTab" ><a  href="http://apps.renren.com/livemall" >游戏</a></li>
					<li class="freegift"><a href="http://apps.renren.com/livemall?a=freeGift" id="freeGift" >免费礼物</a></li>
					<li class="invite" ><a href="http://apps.renren.com/livemall?a=invite" >邀请好友</a></li>
					<li class="faq"><a  href="../static/help/teach.html" class="fullpage" target="_blank">常见问题</a></li>
					<li class="problem"><a  href="javascript:alert('暂未开放');"  id="problem">问题反馈</a></li>
					<li class="payment" ><a  class="active" href="http://apps.renren.com/livemall/pay.php"   id ="pay">充值</a></li>
				</ul>
				</div>
				</div>
			</div>
		
		    <div id='pay-body' style='height: 600px; overflow-y: scroll; border: 1px solid rgb(51, 153, 187); text-align: center;'>
				<div class='user-info'>
					<span class='avatar'>
						<xn:profile-pic uid="<?php echo $pid;?>" linked="false" size="tiny" />
					</span>
					<h2><xn:name uid="<?php echo $pid;?>" linked="false" shownetwork="false" /></h2>
				</div>
				
				<h2>人人弹框支付页面</h2>
			 

				<label for="amount">支付人人豆数额</label>
				<input id="amount" type="text" value="1" />
				<br/>
				<label for="message">购买商品名称</label>
				<input id="message" type="text" value="15游戏币" />
				<br/>
				<p>选择支付类型</p>
				<input id="paymentType" name="pt" type="radio" />
				<label for="paymentType">普通支付</label>
				<input id="presentType" name="pt" type="radio" checked="checked" />
				<label for="presentType">赠送好友</label>
				<input id="creditType" name="pt" type="radio" />
				<label for="creditType">直充</label>
				<input id="peerType" name="pt" type="radio" />
				<label for="peerType">索要支付</label>
				<br/>
				<p>是否沙箱环境？</p>
				<input id="sandbox" name="case" type="radio" checked="checked" />
				<label for="sandbox">沙箱环境</label>
				<input id="real" name="case" type="radio" />
				<label for="real">真实环境</label>
				<br/>
				<br/>
				<br/>
				<br/>
				<a href="#" onclick="requestPayment();return false">点击调出弹层赠送支付</a> 
			</div>			 
		
		</div>
	</div>
</div>
 


<xn:else>
<img src="<?php echo RenrenConfig::$resource_urlp ?>images/genricbg.jpg"/>
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

