<?php
require_once('config.php');
$linkid = $_REQUEST['linkid'];
$fid = $_REQUEST['fid'];
if(!$linkid||!$fid){
	header('Location: '.RenrenConfig::$canvas_url.'?help=invite');
}
else {
	$tw = TT::LinkTT();
	$irec = $tw->getbyuidx('lid',$linkid);
}

if($irec){
 $pid = $irec['uid'];
   $oid = $irec['oid'];
   $sess=TTGenid::getbypid($pid);	
	$user = new TTUser($sess['id']);	
}


$user->update_help($oid,$fid);
print_r($user->get_help($oid));
header('Location: cinema.php?fid='.$linkid.'&xn_sig_user='.$fid);