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
		msg = "�ɹ��ˡ�";
	}
	else if (errCode == Payment.ResponseCode.USER_CANCELLED) {
		msg = "�û�ȡ�������ѡ�";
	}
	else {
		msg = "����ĳ��ԭ��û֧���ɹ���";
	}
	msg += "ƽ̨���� ������ϢΪ��" + errMsg;
	var alert_dialog = new Dialog(
			Dialog.DIALOG_ALERT, 
			{message: msg,title: '��ʾ�����' }
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
	//�����ж��item����һ�����ﳵ
	Payment.requestPayment(callback,params);
}

</script>


</head>
<body>

<style type="text/css">
h2 {margin-left:3px;margin-top:20px;font-size:24px}
a {padding: 10px;font-size:18px}
</style>
    <h2>���˵���֧��ҳ��</h2>
    <br/>
    <a target="_top" href="${requestScope.appHome}">�ص���ҳ</a>
    <br/>
    <br/>

	<label for="amount">֧�����˶�����</label>
	<input id="amount" type="text" value="1" />
    <br/>
	<label for="message">������Ʒ����</label>
	<input id="message" type="text" value="15��Ϸ��" />
    <br/>
    <p>ѡ��֧������</p>
	<input id="paymentType" name="pt" type="radio" />
	<label for="paymentType">��֧ͨ��</label>
	<input id="presentType" name="pt" type="radio" checked="checked" />
	<label for="presentType">���ͺ���</label>
	<input id="creditType" name="pt" type="radio" />
	<label for="creditType">ֱ��</label>
	<input id="peerType" name="pt" type="radio" />
	<label for="peerType">��Ҫ֧��</label>
    <br/>
    <p>�Ƿ�ɳ�价����</p>
	<input id="sandbox" name="case" type="radio" checked="checked" />
	<label for="sandbox">ɳ�价��</label>
	<input id="real" name="case" type="radio" />
	<label for="real">��ʵ����</label>
    <br/>
    <br/>
    <br/>
    <br/>
    <a href="#" onclick="requestPayment();return false">���������������֧��</a>


</body>
</html>