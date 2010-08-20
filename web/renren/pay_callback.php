<?php
 	require_once('config.php'); 
 	$pid =   $_POST['xn_sig_user'];  
	$secret  = Renrenconfig::$pay_secure;//
	if($_POST['xn_sig_skey'] != md5($secret.$pid) ){
		$ret['app_res_code']= "error invalid";
		echo json_encode($ret);
		exit();
	} 
	
	$ot = TT::get_tt('order');  
	$sess=TTGenid::getbypid($pid);
	$user = new TTUser($sess['id']);
	
	
	$oid = $_POST['xn_sig_order_id'];  
	$payment = $ot->get($oid);
	
	if($payment == null){
        $ret['app_res_code']= "error no order";
		echo json_encode($ret);
		exit(); 
	}
	if($payment['status'] == 0){	
		if($payment['sandbox'] == 'true'){ 	
			$ret['app_res_user']= $pid;
			$ret['app_res_amount']= $payment['amount'];
			$ret['app_res_order_id']= $oid;
			echo json_encode($ret);
		}
		else{
			if($user->chGem($payment['gem'])){
				$payment['status'] = 1;
				$payment['handledTime'] =  time();
				$ot->put($oid,$payment);   
				
				//成功后返回
				//{"app_res_user":12345,"app_res_order_id":1000001,"app_res_amount":100}
				$ret['app_res_user']= $pid;
				$ret['app_res_amount']= $payment['amount'];
				$ret['app_res_order_id']= $oid;
				echo json_encode($ret);
		
			} else{
				$ret['app_res_code']= "error gem";
				echo json_encode($ret);
				exit(); 
			}
		}
	} 
	
	 
	 
	
	

	
	