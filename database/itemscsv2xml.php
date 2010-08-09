<?php
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
	$ItemConfig[$d['id']] = $d;
}
fclose( $f );

ob_start();
$item_str.= "<config>
                 <items>
              ";
foreach( $ItemConfig as $item ){
    $item_str.="<item";
    foreach( $item as $k=>$v ){
        if( $v ){
            $item_str.=" $k='".$v."'";
        }
    }
    $item_str.=">  </item>\n";
//    $item_str.="<item id='".$item['id']."' name='".$item['name']."' level='".$item['level']."' price_gold='".$item['price_gold']."'>  </item>\n";
}
$item_str .="\n</items>\n  </config>";
print_r( $item_str );
$content=ob_get_contents();
ob_end_clean();
file_put_contents( "items.xml",$content,NULL,NULL);