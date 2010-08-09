<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<link rel="stylesheet" type="text/css" href="http://fb-client-0.frontier.zynga.com/css/reset.css" />
		<link rel="stylesheet" type="text/css" href="http://fb-client-0.frontier.zynga.com/css/main.css" />
		<link rel="stylesheet" type="text/css" href="http://fb-client-0.frontier.zynga.com/css/app.css" />
		<link rel="stylesheet" type="text/css" href="giftIcon/gift.css" />
		<script src="http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>
		<script type="text/javascript" src="http://fb-client-0.frontier.zynga.com/js/jquery.min.js"></script>
		<script type="text/javascript" src="http://fb-client-0.frontier.zynga.com/js/global.js"></script>
		<title>Super Mall</title>
	</head>
	<body>
	<br/>
	<center><h1 style="font-size:22px; font-family: tahoma; color: #4880d7;">Select a FREE gift to send your friends!</h1></center></br>
	<form action="invite/invite.php?" method="post">
	<div style="width: 700px; text-align: right;">
			<a href="http://fb-0.frontier.zynga.com/flash.php?zySnid=1&zySnuid=100000891768718&zy_user=100000891768718&zy_ts=&zy_session=&zySig=0acbae4878af3b2d7258848916baf438" class="inputbutton inputaux giftButtonFloat" >Skip</a>
			<input type="submit" name="send_gift" value="Ready to Pick Friends >>>" class="giftformsubmit giftButtonFloat" />
	</div>
	<br style="clear:both" />
	<div class="main_gift_cont">
	<ul class="items">
<?php
	require_once 'freeGift.php';
	function getAttr($name,$xml)
	{
		return $xml[$name];
	}
	
	function levelEnoughShow($xml)
	{
		echo '<li class="giftLi" style="padding-bottom: 0px; margin-bottom: 0px;">';
		echo '<div class="gift_img">';
		echo '<label for="radiobucket">';
		echo '<img src="giftIcon/'.getAttr('icon',$xml).'" class="giftIconImg" /></label></div>';		
		echo '<div class="gift_name"><strong><span>'.getAttr('name',$xml).'</span></strong></div>';
		echo '<div class="gift_action"><input type="radio" name="gift" value="'.getAttr('icon',$xml).'" id="radio'.getAttr('name',$xml).'"/></div></li>';
	}
	
	function levelLessShow($xml)
	{
		echo '<li class="giftLocked"><div class="gift_img">';
		echo '<img src="giftIcon/'.getAttr('icon',$xml).'" class="giftIconImg" />';
		echo '<div class="gift_name"><strong><span>'.getAttr('name',$xml).'</span></strong></div>';
		echo '<div class="gift_action">Level: '.getAttr('level',$xml).'</div></li>';
	}
	
	function getUserLevel()
	{
		return 0;
	}
	
	$level = getUserLevel();
	foreach ($gift as $child){
		if($level >= getAttr('level',$child)){
			levelEnoughShow($child);
		}
		else{
			levelLessShow($child);
		}
	}
?>
</ul>
</div>

<br></br>
		<br style="clear:both" />
		<div style="width: 700px; text-align: right;" >
		<br />
			<a href="http://fb-0.frontier.zynga.com/flash.php?zySnid=1&zySnuid=100000891768718&zy_user=100000891768718&zy_ts=&zy_session=&zySig=0acbae4878af3b2d7258848916baf438" class="inputbutton inputaux giftButtonFloat" >Skip</a>
			
			<input type="submit" name="send_gift" value="Ready to Pick Friends >>>" class="giftformsubmit giftButtonFloat" />
		</div>
		<br style="clear:both" />
</form>
</body>
</html>