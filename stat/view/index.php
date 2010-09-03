<?php
require_once dirname(__FILE__).'/../collect/config.php';

/*
$cmd = "mysql -u{$dbconfig['username']} -P{$dbconfig['port']}  -h{$dbconfig['host']} ";
if($dbconfig['password']){
  $cmd.=" -p'{$dbconfig['password']}' ";
}
$cmd .= $dbconfig['dbname'];
system($cmd);
*/

/*
$dbconfig=array(
		//'host' => '122.11.61.28',
		'host' => '127.0.0.1',
		'username' => 'admin',
		'password' => '123456',
		'port'     =>'3307',
		'charset'     =>'utf8',
		'dbname'   => 'mall_stat');
*/

$lastday = date("Y-m-d",time()-86400);
$db = ServerConfig::connect_mysql($dbconfig);
$sql = "select * from mall_stat where name='login_num' and date='$lastday'";
try {
	$data = $db->fetchAll( $sql );
}
catch (Exception $e){
	echo "select count(id) from userops expection occured";
}
print_r( $data );