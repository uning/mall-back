<?php
 	require_once('config.php');
	$ot = TT::get_tt('order'); 
	
	$pid =   $_REQUEST['xn_sig_user']; 
	$sess=TTGenid::getbypid($pid);
	$user = new TTUser($sess['id']);
	
	$gem = $user->chGem(0);
	
	chGem(232);
	
	$r['u']=$sess['id'];
	$r['pid'] = 
	$r['gem']=2;
	$orderid = $ot->put(null,$r);
	$oq=$ot->getQuery();
	
	$oq->setLimit(10,0);
	$oq->addCond("pid",TokyoTyrant::RDBQC_STREQ , $pid);
	$orders =- $oq->search();
	
	
	
//	$id = $sess['id'];
//	$sess=TTGenid::getbypid($id);
	
//	$rid=$ot->put(null,$record);
    
	$record=$ot->get($rid)
?>
