<?php
require_once('ttserver/test_config.php');
//璁板綍鏃ュ織
$m=$_GET['m'];
//if($m){
	$_GET['tm']=$_SERVER['REQUEST_TIME'];
	TTLog::record($_GET);
//}
