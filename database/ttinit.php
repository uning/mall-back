<?php

$host = '127.0.0.1';
$port = 15000;


	$cmd = 'init';
if($argv[1]=='start'){
	$cmd = 'start';
}

if($argv[1]=='stop'){
	$cmd = 'stop';
}

$myloc=dirname(__FILE__);
//include '../web/public/ttserver/TT.php';
$models=array(
'o'=>array('main'=>15000,'data'=>15002,'other'=>'15006','web'=>'15004'),
't'=>array('stat'=>16000,'log'=>16002,'genid'=>16004,'link'=>'16006','order'=>'16008'),
);

$init_dir='/home/hotel/ttserver_deploy';
foreach($models as $k=>$v){
	foreach($v as $m=>$p){
		$name = $m;
		if($cmd == 'init'){
			system("$myloc/init_ttserver_runenv.sh $name $p $k $init_dir");
			sleep(1);
			system("$init_dir/$name.$p$k/ctrl stop");
			system("rm -f $init_dir/$name.$p$k/pid");
			system("$init_dir/$name.$p$k/ctrl start");
		}else{
			system("$init_dir/$name.$p$k/ctrl stop");
			system("rm -f $init_dir/$name.$p$k/pid");
			system("$init_dir/$name.$p$k/ctrl $cmd");
		}
		echo "$init_dir/$name.$p$k/ctrl $cmd\n";
	} 
}

