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
foreach($allms as $m){
	$ca=explode('.',$m);
	$c = $ca[0];
	echo "<a href='?&c=$c'>$c</a>  <a href='?m=$m'>$m</a>\n";
	//echo "<h2> <a href='?&c=$c'>$c</a>  <a href='?m=$m'>$m</a></h2>\n";
	//echo  JsonServer::getMethodHelp($m);
	//echo "<hr/>";
}


	
