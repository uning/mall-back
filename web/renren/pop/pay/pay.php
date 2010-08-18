<?php 
	require_once('../../config.php');
?>
<html
xmlns="http://www.w3.org/1999/xhtml"
	xmlns:xn="http://www.renren.com/2009/xnml"
>
<head>
<script src="<?php echo RenrenConfig::$resource_urlp;?>js/loader.js"></script>
<script type="text/javascript"  src="http://static.connect.renren.com/js/v1.0/FeatureLoader.jsp"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">
		function toFlash()
		{
			window.parent.switchToFlash(); 
		}
		</script>
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


</head>
<body>

<style type="text/css">
h2 {margin-left:3px;margin-top:20px;font-size:24px}
a {padding: 10px;font-size:18px}
</style>
    <h2>人人弹框支付页面</h2>
    <br/>
    <a target="_top" href="${requestScope.appHome}">回到首页</a>
    <br/>
    <br/>

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


</body>
</html>