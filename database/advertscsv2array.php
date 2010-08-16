<?php
$loc = dirname(__FILE__);
echo "The current directory is $loc  \n";
$outloc = $loc."/../web/public/";
echo "the output directory is $outloc  \n";

$AdvertConfig = array();
$f=fopen("adverts.csv",'r');
while(!feof($f)){
	$d=fgetcsv($f);

	if($d[0]=='id'){
		$keys=$d;
//		print_r($keys);
		continue; 
	}
	if(!$d||!$keys)
		continue;

	$d=array_combine($keys,$d);
	if(!$d){
		continue;
	}
//	unset( $d['name'] );
	unset( $d['description'] );
	unset( $d['showMc'] );

	$d['tag'] = $d['id'];
	unset( $d['id'] );
	$AdvertConfig[$d['tag']] = $d;
}
fclose( $f );

ob_start();
echo "<?php\n";
echo "class AdvertConfig\n";
echo "{\n";
echo "    static ".'$_config'."=array(";
foreach($AdvertConfig as $k =>$v){
    echo "$k=>array(";
    foreach($v as $kk=>$vv){
	    echo "'$kk'=>'$vv',";
    }
    echo ")\n,";
}
echo ");\n";
echo '    static function getAdvert ( $tag )'."\n";
echo "    {\n";
echo '        return self::$_config[$tag];'."\n";
echo "    }\n";
echo "}";
   
   $content=ob_get_contents();
   ob_end_clean();
   file_put_contents( $outloc."AdvertConfig.php",$content,NULL,NULL);