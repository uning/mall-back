<?php
    $myloc=dirname(__FILE__);
    require_once  $myloc.'/bg/base.php';
    require_once LIB_ROOT.'ModelFactory.php';
    require_once LIB_ROOT.'AutoIncIdGenerator.php';
    require_once LIB_ROOT.'renren/renren.php';
    require_once $myloc.'/config.php';
 
    $renren = new Renren();
    $renren->api_key = RenrenConfig::$api_key;
    $renren->secret = RenrenConfig::$secret;
    $renren->init();
    
	CrabTools::myprint($renren,RES_DATA_ROOT."/renren"); 
    
	$platform_id = "renren".$renren->user; 
    $user_id     = AutoIncIdGenerator::genid($platform_id);
    $order_db=ServerConfig::getdb_by_userid(0);
    $db          = ServerConfig::getdb_by_userid($user_id);
    $ua = ModelFactory::UserAccount($db); 
    $order = ModelFactory::Order($order_db);
    if(!$ua->find($user_id) ){
//        Logger::error("not existed   user: ".$user_id);
		print(json_encode(array("message"=>'not existed   user' ) ) );
        exit();
    }
    $order2type = array(1=>array("gem"=>0,"money"=>100000)
                       ,2=>array("gem"=>0,"money"=>205000)
					   ,3=>array("gem"=>0,"money"=>515000)
					   ,4=>array("gem"=>0,"money"=>1035000)
					   ,5=>array("gem"=>0,"money"=>2080000)
					   ,6=>array("gem"=>0,"money"=>5150000)
					   ,7=>array("gem"=>1000,"money"=>0)
					   ,8=>array("gem"=>2050,"money"=>0)
					   ,9=>array("gem"=>5150,"money"=>0)
					   ,10=>array("gem"=>10350,"money"=>0)
					   ,11=>array("gem"=>20800,"money"=>0)
					   ,12=>array("gem"=>51500,"money"=>0)
                       );
	$amount = $_POST['amount'];
	$order_data = $order2type[$_POST['order_type']];
	
    if($amount< 10 || $amount > 500 ){
        print(json_encode(array("message"=>'error amount' ) ) );
        exit();
    } 
    $gem = 0;
    $money = 0;
    if( $order_data['money'] == 0){
	    $gem = $order_data['gem']; 
	}
	else if( $order_data['gem'] == 0 ){
	    $money = $order_data['money'];
	} 
    $data = array('user_id'=> $user_id,'pid'=> $platform_id , 'amount'=>$amount, 'gem'=>$gem ,'money'=>$money,'is_paid'=>0,'created_at'=>date( TM_FORMAT,time() ));
    CrabTools::myprint($data,RES_DATA_ROOT."/data");
    if( $order->insert($data  ) ){
//		$o = $renren->api_client->pay_regOrder($order->getAttr("id"),$order->getAttr("amount"),"扑克".$order->getAttr("gem")."游戏币". $order->getAttr("money")."金币");
        //$id = select id from orders where	
/*		
		$id=$order->lastInsertId();	
        $o = $renren->api_client->pay_regOrder($id,$amount,"扑克".$gem."游戏币".$money."金币");
		CrabTools::myprint("id = $id and amout = $amount",RES_DATA_ROOT."/submit");
*/
        $o = $renren->api_client->pay4Test.regOrder($order->getAttr("id"),$order->getAttr("amount"),"扑克".$order->getAttr("gem")."游戏币". $order->getAttr("money")."金币");
		CrabTools::myprint($o,RES_DATA_ROOT."/o");
	    if($o['token'] ){
	        print(json_encode(array('amount'=>$amount,'token'=>$o['token'],'order_number'=>$order->getAttr("id")) ) );
        }
		else{
            print( json_encode( array('amount'=>$amount,'message'=>'call api error','order_number'=>$order->getAttr("id") ) ) );
        }

	}
	else{
	    print(json_encode(array("message"=>'error insert' ) ) );
	}
?>
