<?php
$loc = dirname(__FILE__);
echo "The current directory is $loc  \n";
$outloc = $loc."/../web/public/";
echo "the output directory is $outloc  \n";

$ItemConfig = array();
$f=fopen("items.csv",'r');
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
	unset( $d['allowShopWidth'] );
	unset( $d['buy_goods_star'] );
	unset( $d['can_sit'] );
	unset( $d['canBuyGoodsId'] );
	unset( $d['functiontype'] );
	unset( $d['iconMc'] );
	unset( $d['instructions'] );
	unset( $d['location'] );
	unset( $d['mc'] );
//	unset( $d['name'] );
	unset( $d['showMcs'] );
	unset( $d['star'] );
	unset( $d['truckBg'] );
	unset( $d['hideWhenServe'] );
	unset( $d['servePos'] );
	unset( $d['continueType']);
	unset( $d['servenum']);

	$d['tag'] = $d['id'];
	unset( $d['id'] );
	$ItemConfig[$d['tag']] = $d;
}
fclose( $f );

ob_start();
echo "<?php\n";
echo "class ItemConfig\n";
echo "{\n";
echo "    static ".'$_config'."=array(";
foreach($ItemConfig as $k =>$v){
    echo "$k=>array(";
    foreach($v as $kk=>$vv){
        if( $vv ){
	        echo "'$kk'=>'$vv',";
        }
    }
    echo ")\n,";
}
echo ");\n";
echo '    static function getItem ( $tag )'."\n";
echo "    {\n";
echo '        return self::$_config[$tag];'."\n";
echo "    }\n";
echo "}";
   
   $content=ob_get_contents();
   ob_end_clean();
   file_put_contents( $outloc."ItemConfig.php",$content,NULL,NULL);