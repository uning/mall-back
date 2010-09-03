<?php
require_once dirname(__FILE__).'/../collect/config.php';
print_r( $dbconfig );
$lastday = date( "Y-m-d",time()-86400 );
$db = ServerConfig::connect_mysql( $dbconfig );
if( !$db ){
    echo "db fetch failed!";
}
$sql = "select * from mall_stat where name='login_num' and date='$lastday'";
try {
	$data = $db->fetchAll( $sql );
}
catch (Exception $e){
	echo "Expection Occured!";
}
print_r( $data );