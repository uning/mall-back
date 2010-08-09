<?php
$loc = dirname(__FILE__);
echo "The current directory is $loc  \n";
$outloc = $loc."/../web/public/";
echo "the output directory is $outloc  \n";

$Upgrade = array();
$f=fopen("upgrade.csv",'r');
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
	$Upgrade[$d['id']] = $d;
}
fclose( $f );

ob_start();
echo "<?php\n";
echo "class UpgradeConfig\n";
echo "{\n";
echo "    static ".'$_upgrade'."=array(";
foreach($Upgrade as $k =>$v){
    echo "$k=>array(";
    foreach($v as $kk=>$vv){
	    echo "'$kk'=>'$vv',";
    }
    echo ")\n,";
}
echo ");\n";
echo '    static function getLevel ( $exp )'."\n";
echo "    {\n";
echo '        if( $exp == 0 )'."\n";
echo "            return 1;\n";
echo "        foreach( ".'self::$_upgrade as $row'." ) {\n ";
echo '           if( $row["needexp"] <= $exp ) {'." \n";
echo '                if( $row["level"] < 130 ) '."\n";
echo "                    continue;\n";
echo "                return 130;\n";
echo "            } \n";
echo '            return $row["level"]-1;';
echo " \n        } \n";
echo "    }\n";

echo '    static function getUpgradeNeed( $exp )'."\n    { \n";
echo '        if( $exp == 0 )'."\n";
echo '            return self::$_upgrade[1];'."\n";
echo "        foreach( ".'self::$_upgrade as $row'." ) {\n ";
echo '           if( $row["needexp"] <= $exp ) {'." \n";
echo '                if( $row["level"] < 130 ) '."\n";
echo "                    continue;\n";
echo '                return self::$_upgrade[130] ;'."\n";
echo "            } \n";
echo '            return self::$_upgrade[$row["id"]-1];';
echo " \n        } \n";
echo "    }\n";

echo "}";
   
   $content=ob_get_contents();
   ob_end_clean();
   file_put_contents( $outloc."UpgradeConfig.php",$content,NULL,NULL);