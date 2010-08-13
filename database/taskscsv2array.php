<?php
$loc = dirname(__FILE__);
echo "The current directory is $loc  \n";
$outloc = $loc."/../web/public/";
echo "the output directory is $outloc  \n";

$TaskConfig = array();
$f=fopen("tasks.csv",'r');
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
	unset( $d['guestname'] );
	unset( $d['star'] );

	$d['tag'] = $d['id'];
	unset( $d['id'] );	
	$TaskConfig[$d['tag']] = $d;
}
fclose( $f );

ob_start();
echo "<?php\n";
echo "class TaskConfig\n";
echo "{\n";
echo "    static ".'$_config'."=array(";
foreach($TaskConfig as $k =>$v){
    echo "$k=>array(";
    foreach($v as $kk=>$vv){
	    echo "'$kk'=>'$vv',";
    }
    echo ")\n,";
}
echo ");\n";
echo '    static function getTask ( $tag )'."\n";
echo "    {\n";
echo '        return self::$_config[$tag];'."\n";
echo "    }\n";
echo "}";
   
   $content=ob_get_contents();
   ob_end_clean();
   file_put_contents( $outloc."TaskConfig.php",$content,NULL,NULL);