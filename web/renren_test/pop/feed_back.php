<html>
<head>
</head>
<body>
<?php
//require_once '../config.php';
require_once 'freeGift.php';
print_r($_REQUEST);
$key = $_GET['key'];
$tt = TT::TTWeb();
$value = $tt->get($key);
$type = $value['type'];
$uid = $_POST['uid'];
$user = new TTUser($uid);
if($type==2&&$value['count']>0):
		{
			echo '<script type="text/javacsript">';
			echo 'wodows.location = "http://apps.renren.com/livemall/";';
			echo '</script>';
		}
 	  elseif($type==1&&$value['count']>0):
 	   {
 	  		$recv = $value['rcv'];
 	  		if(!array_key_exists($uid,$recv)){
 	  			$user->chMoney(1000);
 	  			$value['count'] = $value['count']-1;
 	  			$value['clickTime'] +=1;
 	  			$value['rcv'][]=$uid;
 	  			$tt->puto($value);
 	  			echo '<script type="text/javacsript">';
				echo 'wodows.location = "http://apps.renren.com/livemall/";';
				echo '</script>';
 	  		}
 	   }
?>
<?php elseif($type==3&&$value['count']>0):?>
<div class="padding_content center">
			<div class="main_giftConfirm_cont">
									<h3>您接受了<?php echo $gift[$value['gift']]['name'];?></h3>
			<div class="gift_box_holder">
				<div class="gift_box_cont">
					<div class="giftConfirm_img"><img src="<?php echo $gift[$value['gift']]['icon'];?>"></div>
					<div class="giftConfirm_name"><span>Pine Tree</span></div>
				</div>
				<div class="gift_from"><h3>From</h3></div>
				<div class="from_box_cont">
					<div class="giftFrom_img"><img src="<?php echo $user->name;?>"></div>
					<div class="giftFrom_name"><span><?php echo $user->name;?></span></div>
				</div>
			</div>
		</div>
		
	</div>
<?php endif;?>
</body>
</html>