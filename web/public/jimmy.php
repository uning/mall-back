<?php
require_once (dirname(__FILE__).'/base.php');
require_once (LIB_ROOT.'/JsonServer.php');
//ini_set("memory_limit","20M");

function dotest($m,$p=null)
{
	$server = new JsonServer;
	if(!$p)
		$p = JsonServer::getMethodLastCallParam($m);
	echo "method $m\n";
        echo "The params are these as follow:\n";
        print_r( $p );
        echo "The response are these as follow:\n";
        print_r($server->doRequest($m,$p));      
        echo "===============================================\n\n";
}
//dotest('Achieve.get',$p= array('u'=>192));
//dotest('Achieve.finish',$p= array('u'=>1,'tag'=>1001));
//dotest('Achieve.get',$p= array('u'=>1));
//dotest('GoodsController.checkout',$p=array('u'=>1));
//dotest('UserController.enlarge_mall',$p = array('u'=>47));
//dotest('UserController.login',$p = array('pid'=>'45597666'));
//dotest('UserController.login',$p = array('pid'=>'JimmyChou840720'));
//dotest('Advert.get',$p= array('u'=>1));
//dotest( 'UserController.precheckout',$p = array('u'=>1));
//dotest('Advert.get',$p= array('u'=>189));
//dotest('Advert.buy',$p= array('u'=>1,'tag'=>1,'num'=>100));
//dotest('Advert.set',$p= array('u'=>1,'tag'=>1));
//dotest('Friend.test',$p=array('u'=>5));
//dotest('Friend.get',$p=array('u'=>1));
//dotest('UserController.get_achieves',$p=array('u'=>1));
/*
for($i=1;$i<100;$i++){
dotest( 'Tool.clean',$p = array('u'=>$i));
}
*/
//dotest( 'UserController.get_items',$p = array('u'=>21));
//dotest( 'Tool.clean',$p = array('u'=>48));
//dotest( 'UserController.get_items',$p = array('u'=>1));
//dotest( 'GoodsController.before_checkout',$p = array('u'=>1,'goodstag'=>10101,'shoptag'=>60001,'adverttag'=>1));
//dotest('GoodsController.checkout',$p=array('u'=>48));
//dotest( 'Tool.showids',$p = array('u'=>1));
//dotest( 'Tool.genusers',$p = array('u'=>1));
//dotest('CarController.go_goods',$p = Array('c'=>Array('id'=>"23:c:4c4fe86bb4aaa",'goodsTag'=>10101),'u'=>1) );
//dotest('UserController.set_advert',$p=array('u'=>1,'tag'=>1));
//dotest('UserController.cheat',$p=array('u'=>21,'money'=>100000,'gem'=>100,'exp'=>10000));
//dotest('ItemController.buy',$p = array('d' => Array('0' => Array('pos' => Array('y' => 48,'x' => 0),'havePop' => 1,'tag' => 60003,'isShop' => 1),'1' => Array('pos' => Array('y' => 50,'x' => 0),'havePop' => 1,'tag' =>60003,'isShop' => 1)),'u' => 20));
//dotest('CarController.buy',$p=array('u'=>2,'c'=>array( array('tag'=>92702,'pos'=>array('x'=>0,'y'=>0),'t'=>0) ) ) );
//dotest('TaskController.get_award',$p = array('u'=>'27','tid'=>'27:t:4c4a968aeb924:o') );
//dotest('UserController.get_tasks',$p=array('u'=>'26'));
//dotest('UserController.get_advert',$p=array( 'u'=>'1' ) ); 
//dotest('UserController.get_others',$p=array('id'=>'1:#:4c46650777','u'=>1,'gender'=>1,'name'=>"wahaha"));
//dotest('UserController.enlarge_mall',$p=array('u'=>'1','cap'=>"8,8"));
//dotest('UserController.update_info',$p=array('id'=>8,'pid'=>'001','gender'=>1,'name'=>"wahaha",'icon'=>'nonono','favor'=>'Mariah Carey','o'=>'oii'));
//dotest('UserController.get_goods',$p=array('u'=>5));
//dotest('UserController.get_cars',$p=array('u'=>5));
//dotest('UserController.get_all',$p=array('u'=>5));
