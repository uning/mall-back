<?php
 	require_once('config.php');
	
	$pid =   $_POST['xn_sig_user']; 

 	
	$secret  = Renrenconfig::$pay_secure;//
	if($_POST['xn_sig_skey'] != md5($secret.$pid) ){
		$ret['app_res_code']= "error";
		echo json_encode($ret);
		exit();
	} 

	
	$pp = json_decode($_POST['xn_sig_payment'],true);
	//{"amount":"1","message":"orderingsomelowers.",
	//"parameters":"{type:'Tulip',quantity:5}",
	//"paymentType":"payment",
	//"sandbox":true,
	// "items": [{"skuId":"test_sku1","price":20,"count":2,"description":"demo descriptionred ower"},],
	//"orderedTime":1261633596528}
	if($_POST['xn_sig_sandbox'] == 'true'){
	    //fake payment
		$pp['sandbox']  = 'true';
	}
	if($pp== null || $pp['amount'] == null){
		$ret['app_res_code']= "error payment";
		echo json_encode($ret);
		exit();
	}
	
	$ot = TT::get_tt('order');  
	$sess=TTGenid::getbypid($pid);
	$user = new TTUser($sess['id']);
	$payment = array('parameters'=>$pp['parameters'],'items'=>$pp['items'],'amount'=>$pp['amount'],'message'=>$pp['message'], 'sandbox'=>$pp['sandbox'],'paymentType'=>$pp['paymentType'] ,'orderedTime'=>$pp['orderedTime']);
	$payment['pid'] = $pid; 
	$payment['uid'] = $sess['id'];
	$payment['status'] = 0;
	$payment['gem']= $pp['amount'] * 10;
	 
	//$gem = $user->chGem(0);
	
	//chGem(232);
 	$orderid = $ot->put($payment);
	
	//返回数据
	//{"app_res_order_id":20091223061349,"app_res_code":"OK","app_res_message":"10人人豆兑换100Q币","app_res_user":230121017}
	$ret['app_res_order_id']= $orderid;
	$ret['app_res_code']= "OK";
	$ret['app_res_message']= $payment['message'];
	$ret['app_res_user']= $pid; 
	
	echo json_encode($ret);
	
	//$oq=$ot->getQuery();	
	//$oq->setLimit(10,0);
	//$oq->addCond("pid",TokyoTyrant::RDBQC_STREQ , $pid);
	//$orders =- $oq->search();
 	
//	$id = $sess['id'];
//	$sess=TTGenid::getbypid($id);
//	$rid=$ot->put(null,$record);    
//	$record=$ot->get($rid)


?>
