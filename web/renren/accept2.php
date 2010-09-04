<?php 
require_once('config.php');
require_once('renren.php');
require_once('pop/freeGift.php');
$linkid = $_REQUEST['lid'];	
$linkid ='4c8205480c843';
$tw = TT::LinkTT();
$link = $tw->getbyuidx('lid',$linkid);
$touser = $_REQUEST['xn_sig_user'];
$touser = 45182749;
?>
<xn:if-is-app-user>
<div id='is_install'></div>
<?php
	$fromuser = $link['pid'];
	$fsess = TTGenid::getbypid($fromuser);	
	$tsess = TTGenid::getbypid($touser);	
	$ftu = new TTUser($fsess['id']);
	$ttu = new TTUser($tsess['id']);
	$att = $tsess['authat'];
	$ut = $tsess['ut'];
	$gemd = $tsess['gemd'];
	print_r($att);
	print_r($ut);
	print_r($gemd);
	if($link['gift']){
		$lg = $link['gift'];
	}
	else
	   $lg = 0;
	$new  = 1;
	if($_REQUEST['new']){
	 	$new = 1;
	 }

	TTLog::record(array('m'=>'accept_invite','tm'=> $_SERVER['REQUEST_TIME'],'u'=>$touser,'sp1'=>$lg,'sp2'=>$new));
	//$tudata=$ftu->getf(array('name','icon'));
	$getted = $link['geted'];
	$ids = $link['ids'];
	$invite = false;
	foreach($ids as $id){
		if($id==$touser){
				$invite = true;
				break;
				}
		}  
	$got = false;
	foreach ($getted as $u){
			if($u==$touser){
				$got = true;
					break;
					}
		}
	$diff = time() - $att;
	print_r($invite.'\n');
	print_r($gemd.'\n');
	print_r($ut.'\n');

	if($invite&&$new ==1&&!$gemd){
		$ftu->numch('invite_num',1);
		$cid = $ftu->getoid('copilot',TT::OTHER_GROUP );	    
		$copilot = $ftu->getbyid( $cid );
		print_r($copilot);
		$copilot['id'] = $cid;
		$copilot['bag'][2004] += 1;
		$ftu->puto($copilot);
		$tsess['gemd']=1;
		TTGenid::update($tsess,$tsess['id']);
		print_r( $ftu->getbyid( $cid ));
	}				
	if($invite){
	$gid = $link['gift'];
	if($gid){?>
	<?php 
		if($got) {?>
			
				<h3>您的礼物已经领取，请在仓库中查收。</h3>
			
		<?php }?>
	         <a href='<?php echo RenrenConfig::$canvas_url;?>' class='giftformsubmit' >返回游戏</a>	
	</div>
	</div>
		<?php 
		if(!$got){
			$id = $ttu->getdid( '',$gift[$gid]['group'] );
			$data['tag'] = $gid;
			$data['id'] = $id;
			$data['pos']='s';
			$ttu->puto( $data ); 
			}
		}
		if(!$got){
		$link['geted'][] = $touser;
		$tw->put($link);
		}
		if(!$gid){?>
		<xn:redirect url="<?php echo RenrenConfig::$canvas_url;?>" />
		<?php }
		}
		?>
	<xn:else>
<img src="<?php echo RenrenConfig::$resource_urlp ?>images/genericbg.jpg"/>

</xn:else>
</xn:if-is-app-user>