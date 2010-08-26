<?php
require_once('../config.php');
$linkid = $_REQUEST['linkid'];
$fid = $_REQUEST['fid'];
if(!$linkid||!$fid){
	header('Location: '.RenrenConfig::$canvas_url.'?help=invite');
}
else {
	$tw = TT::LinkTT();
	$irec = $tw->getbyuidx('linkid',$linkid);
}

if($irec){
 $pid = $irec['pid'];
   $oid = $irec['oid'];
   $sess=TTGenid::getbypid($pid);	
	$user = new TTUser($sess['id']);	
}

if($user){
foreach ($irec['ids'] as $v){
	if($v==$fid)
	{
		$inv = true;
		break;
	}
}
if($inv)
$user->update_help($oid,$fid);
}?>
<xn:redirect url="<?php echo RenrenConfig::$canvas_url;?>" />