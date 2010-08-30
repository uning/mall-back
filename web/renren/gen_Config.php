<?php
$rdir='../../../Venus/to-company/';
$confile = $rdir.'0_mall_config.xml';
$xml =  simplexml_load_file($confile);


?>
<?xml version="1.0" encoding="utf-8"?>
<config>
	<version tag="beta1.0"/>
	
	<resources parallel="false" use_cache="false">
<?php

foreach($xml->resources->resource as $r){
	$src=$r['url'];
	$src = explode('?',$src);
	$src = $src[0];
	$file = $rdir.$src;
	if(!file_exists($file)){
		die("CONF resource not exists $file");
	}
	$src.='?v='.md5_file($file);
	$r['url']=$src;
	echo "   ".$r->asXML()."\n";
	//print_r($v);

}

?>

	</resources>
<?php	
	//<main name="main" label="加载主程式" url="MallGame.swf?v=tc1.1" use_cache="false"/>
	//<progress_aspect url="resource/common/swf/loadinglogo.swf" />
    $r = $xml->main;

	$src=$r['url'];
	$src = explode('?',$src);
	$src = $src[0];
	$file = $rdir.$src;
	if(!file_exists($file)){
		die("CONF resource not exists $file");
	}
	$src.='?v='.md5_file($file);
	$r['url']=$src;
	echo "   ".$r->asXML()."\n";
    $r = $xml->progress_aspect;

	$src=$r['url'];
	$src = explode('?',$src);
	$src = $src[0];
	$file = $rdir.$src;
	if(!file_exists($file)){
		die("CONF resource not exists $file");
	}
	$src.='?v='.md5_file($file);
	$r['url']=$src;
	echo "   ".$r->asXML()."\n";
?>
	
	<resource_servers>
	    <server name="test" purl="http://rrmall.playcrab.com/work/mall/Venus/to-company/" />
	</resource_servers>	
	<app_servers>
	    <server name="test" purl="http://rrmall.playcrab.com/work/mall/backend/web/public/" />
	</app_servers>	
</config>
