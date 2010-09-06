<?php
require_once 'base.php';
require_once  LIB_ROOT.'JsonServer.php';


echo '
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script>
</script>
</head>
<body>
<pre>
';
$m = $_GET['m'];
$c = $_GET['c'];
if($m){
		$ca=explode('.',$m);
		$c=$ca[0];
}

		$show_all = true;
if($c==null){
		$show_all = false;
		JsonServer::registerController('Achieve');
		JsonServer::registerController('Advert');
		JsonServer::registerController('Cinema');
		JsonServer::registerController('Gift');
		JsonServer::registerController('Man');
		JsonServer::registerController('UserController');
		JsonServer::registerController('ItemController');
		JsonServer::registerController('CarController');
		JsonServer::registerController('GoodsController');
		JsonServer::registerController('Task');
		JsonServer::registerController('Friend');
		JsonServer::registerController('TaskOnce');
		JsonServer::registerController('HelpGet');
		JsonServer::registerController('Tool');
		JsonServer::registerController('DataS');
         
//	     JsonServer::registerController('Test');
}else{
	 JsonServer::registerController($c);
}
if($m==null ){
	   $allms = JsonServer::getAllMethod();
	   foreach($allms as $m){
	   	$ca=explode('.',$m);
		 //echo "'$m'=>1,\n";
		 //continue;
	   	$c = $ca[0];
	   	echo "<a href='?&c=$c'>$c</a>  <a href='?m=$m'>$m</a>\n";
	   	//echo "<h2> <a href='?&c=$c'>$c</a>  <a href='?m=$m'>$m</a></h2>\n";
		if($show_all)
			echo  JsonServer::getMethodHelp($m);
	   	//echo "<hr/>";
	   }
	   exit;
}
echo "<h2> <a href='?'>All</a>  <a href='?c=$c'>$c</a>  <a href='?&m=$m'>$m</a></h2>\n";
echo JsonServer::getMethodHelp($m);

?>

<div id="gen_help">
输入参数约定:
   u        --  用户内部id
   pid      --  用户平台id
返回值：
   s        --  OK,正常，其余为失败原因
   r        --  返回结果
</div>  
	
