<?php
 	require_once('config.php');
	
	$pid =   $_POST['xn_sig_user']; 

	file_put_contents("/home/hotel/pay.log",$_POST);
	
	$secret  = Renrenconfig::$pay_secure;//
	if($_POST['xn_sig_skey'] != md5($secret.$pid) ){
		$ret['app_res_code']= "error";
		echo json_encode($ret);
		exit();
	} 

	
	$payment = json_decode($_POST['xn_sig_payment']);
	//{"amount":"1","message":"orderingsomelowers.",
	//"parameters":"{type:'Tulip',quantity:5}",
	//"paymentType":"payment",
	//"sandbox":true,
	// "items": [{"skuId":"test_sku1","price":20,"count":2,"description":"demo descriptionred ower"},],
	//"orderedTime":1261633596528}
	if($_POST['xn_sig_sandbox'] == true){
	    //fake payment
		$payment['sandbox']  = true;
	}
	
	$ot = TT::get_tt('order');  
	$sess=TTGenid::getbypid($pid);
	$user = new TTUser($sess['id']);
	
	$payment['pid'] = $pid; 
	$payment['uid'] = $sess['id'];
	$payment['status'] = 0;
	$payment['gem']= $payment['amount'] * 10;
	 
	//$gem = $user->chGem(0);
	
	//chGem(232);
 	$orderid = $ot->put(null,$payment);
	
	//��������
	//{"app_res_order_id":20091223061349,"app_res_code":"OK","app_res_message":"10���˶��һ�100Q��","app_res_user":230121017}
	$ret['app_res_order_id']= $orderid;
	$ret['app_res_code']= "OK";
	$ret['app_res_message']= $payment.message;
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
