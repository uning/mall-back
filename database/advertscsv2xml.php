<?php
$ItemConfig = array();

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
	$advertConfig[$d['id']] = $d;
}
fclose( $f );

ob_start();
$advert_str.= "<config>
                 <adverts>
              ";
foreach( $advertConfig as $advert ){
    $advert_str.="<advert";
    foreach( $advert as $k=>$v ){
        if( $v ){
            $advert_str.=" $k='".$v."'";
        }
    }
    $advert_str.=">  </advert>\n";
//    $advert_str.="<advert id='".$advert['id']."' name='".$advert['name']."' level='".$advert['level']."' price_gold='".$advert['price_gold']."'>  </advert>\n";
}
$advert_str .="\n</adverts>\n  </config>";
print_r( $advert_str );
$content=ob_get_contents();
ob_end_clean();
file_put_contents( "adverts.xml",$content,NULL,NULL);