<?php
 ob_start();
// $mypos=dirname(__FILE__).'/../../frontflash/to-renren/';
 $mypos=dirname(__FILE__).'/';
?>
<urls>
<url name="serverlistUrl">./resource/common/jx/serverlist?v=<?php echo md5_file($mypos.'./resource/common/jx/serverlist');?></url>
<url name="hallUrl">./resource/common/swf/Hall/release/Hall.swf?v=<?php echo md5_file($mypos.'./resource/common/swf/Hall/release/Hall.swf');?></url>
<url name="adSwfUrl">./resource/common/swf/advert.swf?v=<?php echo md5_file($mypos.'./resource/common/swf/advert.swf');?></url>
<url name="adUrl">invite.php?f=admain</url>

</urls>
<?php
  $r=ob_get_contents();
  file_put_contents($mypos.'./resource/common/jx/urls.xml',$r);
  ob_end_clean();
  ob_start();
?>
<config>
	<version tag="beta1.0"/>
	<resources parallel="false" use_cache="false">
		<resource name="ui" label="资源1/13" url="./resource/common/swf/pokerPopup.swf?v=<?php echo md5_file($mypos.'resource/common/swf/pokerPopup.swf');?>" type="swf" />
		<resource name="ui" label="资源2/13" url="./resource/common/swf/TableUI.swf?v=<?php echo md5_file($mypos.'resource/common/swf/TableUI.swf');?>" type="swf" />
		<resource name="ui" label="资源3/13" url="./resource/common/swf/porkerMain.swf?v=<?php echo md5_file($mypos.'resource/common/swf/porkerMain.swf');?>" type="swf" />
		<resource name="ui" label="资源4/13" url="./resource/common/swf/achieves.swf?v=<?php echo md5_file($mypos.'resource/common/swf/achieves.swf');?>" type="swf" />
		<resource name="gifts" label="资源5/13" url="./resource/common/swf/gift.swf?v=<?php echo md5_file($mypos.'resource/common/swf/gift.swf');?>" type="swf" />
		<resource name="chips" label="资源6/13" url="./resource/common/swf/Chips.swf?v=<?php echo md5_file($mypos.'resource/common/swf/Chips.swf');?>" type="swf" />
		<resource name="cards" label="资源7/13" url="./resource/common/swf/Cards.swf?v=<?php echo md5_file($mypos.'resource/common/swf/Cards.swf');?>" type="swf" />
		<resource name="sound" label="资源8/13" url="./resource/common/swf/sound.swf?v=<?php echo md5_file($mypos.'resource/common/swf/sound.swf');?>" type="swf" />
		<resource name="roomtype" label="资源9/13" url="./resource/common/jx/roomtype.xml?v=<?php echo md5_file($mypos.'');?>" type="xml" />
		<resource name="gifts" label="资源10/13" url="./resource/common/jx/presents.xml?v=<?php echo md5_file($mypos.'resource/common/jx/presents.xml');?>" type="xml" />
		<resource name="achieves" label="资源11/13" url="./resource/common/jx/achieves.xml?v=<?php echo md5_file($mypos.'resource/common/jx/achieves.xml');?>" type="xml" />
		<resource name="urls" label="资源12/13" url="./resource/common/jx/urls.xml?v=<?php echo md5_file($mypos.'resource/common/jx/urls.xml');?>" type="xml" />
		<resource name="stopwords" label="资源13/13" url="./resource/common/jx/stopwords.xml?v=<?php echo md5_file($mypos.'resource/common/jx/stopwords.xml');?>" type="xml" />
	</resources>
	<main name="main" label="加载主程式" url="./PokerGame.swf?v=<?php echo md5_file($mypos.'PokerGame.swf');?>" use_cache="false"/>
	<progress_aspect url="./resource/common/swf/loadinglogo.swf?v=<?php echo md5_file($mypos.'resource/common/swf/loadinglogo.swf');?>" />	
	<resource_servers>
	    <server name="test" purl="" />
	</resource_servers>
	<app_servers>
	    <server name="test" purl="http://127.0.0.1/pokerbg/" />
	</app_servers>
</config>
<?php
  $r=ob_get_contents();
  file_put_contents($mypos.'0_pokerloader_config.xml',$r);
  ob_end_clean();
  ob_start();
?>
