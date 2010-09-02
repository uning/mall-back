<?php
require_once('../config.php');
?>

<link rel="stylesheet" href="<?php echo RenrenConfig::$resource_urlp;?>css/crab2.css" type="text/css"/>
<script src="<?php echo RenrenConfig::$resource_urlp;?>js/jquery-1.4.2.min.js"></script>
<script src="<?php echo RenrenConfig::$resource_urlp;?>js/jquery.boxy.js"></script>
<!--link rel="stylesheet"href="<?php echo RenrenConfig::$resource_urlp;?>css/main.css?2" /-->
<script src="<?php echo RenrenConfig::$resource_urlp;?>js/pageUtil.js"></script>

<div id="header">
    <div id="main-nav">
    <div class="logo"><a href="http://apps.renren.com/happyfarmtwo/" target="_top" title="开始游戏!">logo</a></div>
    <ul class="clearfix tcenter">       
        <li class="game"><a class="active" href="http://apps.renren.com/happyfarmtwo/"  target="_top">开始游戏</a></li>
        <li class="freegift"><a  href="http://apps.renren.com/happyfarmtwo/?mod=freegift&from=nav"  target="_top">免费礼物</a></li>
        <li class="invite"><a href="http://apps.renren.com/happyfarmtwo/?mod=invite&from=nav"  target="_top">邀请好友</a></li>

        <!--<li class="support"><a  href="http://apps.renren.com/happyfarmtwo/?mod=support" target="_top">帮助中心</a></li>-->
        <li class="faq"><a  href="http://apps.renren.com/happyfarmtwo/?mod=faq" target="_top">常见问题</a></li>
        <li class="forum"><a  href="http://group.renren.com/GetTribe.do?id=313639287" target="_blank">讨论群</a></li>
        <li class="payment"><a  href="http://apps.renren.com/happyfarmtwo/?mod=payment"  target="_top">F币兑换</a></li>
			</ul>
    </div>
</div>




<div id="htmlFrame"  style="width: 760px;">
<input type="button" onclick="popUpFeed();" value="clicak"></input>
</div>
<div id="loadingFrame" class="offscreen" style="display: none"><img
	src="../static/images/loading.gif"/></div>
