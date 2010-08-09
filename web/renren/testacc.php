<html>
<head>
<link rel="stylesheet" type="text/css" href="static/css/gift.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
//require_once('config.php');
require_once('pop/freeGift.php');
$gid = 10103;
?>

			<div class="padding_content center">
			<div class="main_giftConfirm_cont">
			<h3>您接受了<?php echo $gift[$gid]['name'];?></h3>
			<div class="gift_box_holder">
				<div class="gift_box_cont">
					<div class="giftConfirm_img"><img src="<?php echo $gift[$gid]['icon'];?>"></div>
					<div class="giftConfirm_name"><span><?php echo $gift[$gid]['name'];?></span></div>
				</div>
				<div class="gift_from"><h3>From</h3></div>
				<div class="from_box_cont">
					<div class="giftFrom_img"><img src="<?php echo "jj";?>"></div>
					<div class="giftFrom_name"><span><?php echo "jirji";?></span></div>
				</div>
				<input type="button" name="acc" value="进入游戏"></input>
			</div>
		</div>
		
	</div>
</body>
</html>