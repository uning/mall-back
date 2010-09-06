<?php
require_once (dirname(__FILE__).'/base.php');
require_once (LIB_ROOT.'/JsonServer.php');

function record_time(&$start,$usage="",$unit=0)
{
	$end  = microtime(true);
	$cost=$end-$start;
	$cost=ceil(1000000*$cost);
	if($unit>0){
		$cost = ceil($cost/$unit);
	}
	if($usage)
		echo "$usage use time $cost us\n";
	$start = $end;
}


function dotest($m,$p=null)
{
	$server = new JsonServer;
	if(!$p)
		$p = JsonServer::getMethodLastCallParam($m);
	echo "method $m\n";
	echo "The params are these as follow:\n";
	print_r( $p );
	echo "The response are these as follow:\n";
	record_time($st);
	print_r($server->doRequest($m,$p));      
	record_time($st," $m ");
	echo "===============================================\n\n";
}

dotest('UserController.update_popu',array('u'=>1));
return;
$now = 1282208631;
$now = time();
dotest('GoodsController.dcheckout',array('u'=>26,'now'=>$now));
return;
dotest('Man.update',array('u'=>8127,'step'=>'1'));
exit;
dotest('Friend.debug_get',array('u'=>4));
return;
dotest('UserController.update_profile');

dotest('Friend.help_car');
return;
$tu = new TTUser(2);
$tu->update_help('60102',3);
$tu->update_help('60102',4);
$tu->update_help('60102',5);
dotest('Friend.get',array('u'=>2,'f'=>24));
dotest('HelpGet.get',array('u'=>2));
dotest('HelpGet.open',array('u'=>2,'tag'=>'60102'));
dotest('HelpGet.award',array('u'=>2,'tag'=>'60102'));
return;
$now = 1282208631;
$now = time();
//dotest('GoodsController.checkout',array('u'=>61,'now'=>$now));
//return;
dotest('GoodsController.dcheckout',array('u'=>138,'now'=>$now));
return;

dotest('Gift.accept');
return;
dotest('ItemController.buy');
dotest('ItemController.move');
return;
dotest('Man.satisfy',array('u'=>2,'type'=>1));
return;
dotest('Friend.visit',array('u'=>1,'f'=>24));
return ;
dotest('GoodsController.checkout');
//return ;
$now = time();
dotest('GoodsController.checkout',array('u'=>61,'now'=>$now));
$now += 60;
dotest('GoodsController.checkout',array('u'=>61,'now'=>$now));
return ;
/*
dotest('ItemController.buy');
return;
dotest('Advert.get');
//dotest('Advert.set');
dotest('Advert.buy');
return;
dotest('GoodsController.checkout',array('u'=>58));
return;
print_r($data=TTGenid::genid(array('pid'=>'dfs1','gender'=>1,'name'=>"wahaha"),$new));
echo "new = $new\n";
print_r($data=TTGenid::genid(array('pid'=>'dfs1','gender'=>1,'name'=>"wahaha"),$new));
echo "new = $new\n";
return;
 */
dotest('UserController.get_achieves');
otest('UserController.showids');
dotest('UserController.genusers');
dotest('UserController.login');
dotest('UserController.get_items');
dotest('Man.get');
dotest('Man.update');
dotest('DataS.put');
dotest('DataS.get');
/*
print_r($data=TTGenid::genid(array('pid'=>'dfs','gender'=>1,'name'=>"wahaha"),$new));
echo "new =$new\n";
$data['pid'] = '1erer2';
$u =20;
dotest('UserController.login',$data);
dotest('UserController.get_items',$p=array('u'=>$u));
dotest('UserController.get_all',$p=array('u'=>$u));
dotest('TaskController.accept');
dotest('UserController.update_friends',$p=array('u'=>'1','fids'=>'quest01,quest03'));
dotest('UserController.update_friends',$p=array('u'=>'1'));

dotest('UserController.get_tasks',$p = array('u'=>'27') );
dotest('TaskController.get_award',$p = array('u'=>'27','tid'=>'27:t:4c4a968aeb924:o') );

dotest('UserController.showids',$p=array('id'=>'1'));
dotest('UserController.get_tasks',$p=array('u'=>'26'));
dotest('UserController.set_advert',$p=array( 'u'=>'1','tag'=>'1') );
dotest('UserController.get_advert',$p=array( 'u'=>'1' ) ); 
dotest('UserController.get_others',$p=array('id'=>'1:#:4c46650777','u'=>1,'gender'=>1,'name'=>"wahaha"));
dotest('UserController.enlarge_mall',$p=array('u'=>'1','cap'=>"8,8"));

dotest('UserController.login',$p=array('pid'=>'001','gender'=>1,'name'=>"wahaha"));
dotest('UserController.update_info',$p=array('id'=>8,'pid'=>'001','gender'=>1,'name'=>"wahaha",'icon'=>'nonono','favor'=>'Mariah Carey','o'=>'oii'));
dotest('UserController.update_friends',$p=array('u'=>1));
dotest('UserController.update_friends',$p=array('u'=>1,'fids'=>"quest07,wely0211"));
dotest('UserController.get_achieves',$p=array('u'=>39));

dotest('UserController.set_advert',$p=array('u'=>1,'tag'=>3) ); 
//dotest('UserController.delete',$p=array('u'=>38));    
dotest( 'CarController.buy',$p = array( 'u'=>1,'c'=>array( array( 'tag'=>5004,'pos'=>array( 'x'=>'1','y'=>'1' ) ) ) ) );

dotest('UserController.get_achieves',$p=array('u'=>38));
dotest('UserController.get_tasks',$p=array('u'=>5));
dotest('UserController.get_items',$p=array('u'=>5));
//dotest('UserController.deleteall',$p=array('u'=>5));
dotest('ItemController.buy',$p=array('u'=>5,'d'=>array(array('tag'=>5,'pos'=>array('x'=>3,'y'=>49),'n'=>2))));
dotest('UserController.get_items',$p=array('u'=>5));
dotest('UserController.login',$p=array('pid'=>'JimmyChou'));
dotest('UserController.get_tasks',$p=array('u'=>5));
dotest('UserController.get_goods',$p=array('u'=>5));
dotest('UserController.get_cars',$p=array('u'=>5));
dotest('UserController.get_all',$p=array('u'=>5));

$p = array('u'=>'5','d'=>array('tag'=>5,'pos'=>array('x'=>0,'y'=>49) ),'n'=>2 );
dotest('ItemController.buy',$p);

$p = array('u'=>'5','d'=>array(array('tag'=>900,'num'=>6,'stime'=>time(),'pos'=>array('x'=>0,'y'=>49) ) ));
dotest('GoodsController.buy',$p);

dotest('GoodsController.exhibit_goods');
dotest('ItemController.buy');
//*/
dotest('Gift.get',$p=array('u'=>1,'fname'=>'xxx','fid'=>2,'gtag'=>'12','gid'=>'test','desc'=>'test' ));
dotest('Gift.view',$p=array('u'=>1));
dotest('Gift.accept',$p=array('u'=>1,'d'=>array(array('id'=>'1:gi:4c4e5bbf2c579','pos'=>'s')) ));
