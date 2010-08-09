<?php
require_once('config.php');


$data = array('uid'=>100,'fid'=>12,'gift_id'=>3);

$pid = $_GET['fbid'];
$session = TTGenid::getbypid($pid);

$linkid = uniqid();
$tts = TT::TTweb();
$data['id']=$linkid;
$data['tm']=time();
$tts->puto($data);




print_r($tts->getbyid($linkid));





$giftid=$_GET['gid'];
$fids = $_GET['fids'];
$linkid= $_GET['linkid'];

if($fids){
	$tts = TT::TTweb();
	$data['id']=$linkid;
	$data['tm']=time();
	$tts->puto($data);
	return;
 
}

if($linkid){
		
}





?>
<form >

<input name='linkid' value='<?php echo uniqid();?>'/>
<>
