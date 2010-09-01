<?php
$mypos=dirname(__FILE__).'/';
$rdir=$mypos.'/../../../Venus/to-company/';
$confile = $rdir.'0_mall_config.xml';
$xml =  simplexml_load_file($confile);
$outf = $mypos.'/static/flash/o_0_mall_config.xml'; 

$out .= <<<EOT
<?xml version="1.0" encoding="utf-8"?>
<config>
	<version tag="beta1.0"/>
	
	<resources parallel="false" use_cache="false">
EOT;


foreach($xml->resources->resource as $r){
	$src=$r['url'];
	$src = explode('?',$src);
	$src = $src[0];
	$file = $rdir.$src;
	if(!file_exists($file)){
		die("CONF resource not exists $file");
	}
	$size = filesize ($file);
	$total_size +=$size;
	if($size>100000)
		echo " $size bytes $src \n ";
	$r['url']=$src;
	$src.='?v='.md5_file($file);
	$out .= "   ".$r->asXML()."\n";
	//print_r($v);

}

$out .= <<<EOT

	</resources>

EOT;
//<main name="main" label="加载主程式" url="MallGame.swf?v=tc1.1" use_cache="false"/>
//<progress_aspect url="resource/common/swf/loadinglogo.swf" />
$fs = array('main','progress_aspect');
foreach($fs as $v){
	$r = $xml->$v;

	$src=$r['url'];
	$srcs = explode('?',$src);
	$src = $srcs[0];
	$file = $rdir.$src;
	if(!file_exists($file)){
		die("CONF resource not exists $file");
	}
	$size = filesize ($file);
	$total_size +=$size;
	if($size>100000)
		echo " $size bytes $src \n ";
	$src.='?v='.md5_file($file);
	$r['url']=$src;
	$out .= "   ".$r->asXML()."\n";
}
$out .= <<<EOT
	
	<resource_servers>
	    <server name="test" purl="http://files5.qq494.cn/pig/hotel/flash/" />
	</resource_servers>	
	<app_servers>
	    <server name="test" purl="http://rrmall.playcrab.com/bg/" />
	</app_servers>	
</config>
EOT;
echo "total_size: $total_size\n";
//echo $out;
//file_put_contents($outf,$out);
