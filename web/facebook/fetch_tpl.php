<?php
//download files
$content = file_get_contents('tpl_index.html');
$static_pre='http://content00.nightclub-city.com/nlc/static/club/www/static/';
$static_len = strlen($static_pre);

$s = preg_quote ($static_pre);
if(preg_match_all("|(${s}[^'\"\)]+)['\"]|",$content,$res)){
	//print_r($res);

	$dup=array();
	echo "rm -rf static/*";
	foreach($res[1] as $u){
		$utt=explode('?',$u);
		$url = $utt[0];
		$filename = substr($url,$static_len);
		$filename = 'static/'.$filename;
		$mkdir = dirname($filename);
		++$dup[$filename];
		if($dup[$filename]<2&&!file_exists($filename)){
			$cmd = "wget $url -O $filename";
			echo "mkdir -p $mkdir\n ";
			echo $cmd."\n";
		}
	}

}


