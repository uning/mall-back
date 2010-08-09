<?php
$myloc=dirname(__FILE__);
require_once  $myloc.'/bg/base.php';
require_once LIB_ROOT.'ModelFactory.php';
require_once LIB_ROOT.'renren/renren.php';
require_once $myloc.'/config.php';
Logger::info("pay_callback:".serialize($_POST) );
$renren = new Renren();
$renren->api_key = RenrenConfig::$api_key;
$renren->secret = RenrenConfig::$secret;
$renren->init();
$platform_id = "renren".$renren->user;
$session_key = $renren->session_key;
 
$user_id     = AutoIncIdGenerator::genid($platform_id);
$db          = ServerConfig::getdb_by_userid($user_id);

$secret  = Renrenconfig::$pay_secure;//
if($_POST['xn_sig_password'] != $secret ){
echo "!!";
exit();
} 
$ua = ModelFactory::UserAccount($db);
$ua->find($user_id);
$order_db=ServerConfig::getdb_by_userid(0);
$order = ModelFactory::Order($order_db);
$order->find($_POST['xn_sig_order_number'] );
  
  if($order->getAttr("is_paid") ){
   print(json_encode(array('user_id'=>$order->getAttr("platform_id"), 'amount'=>$order->getAttr("amount"), 
  'order_number'=>$order->getAttr("id") ) ));
	exit();
  }
   
    $gem = $ua->getAttr("gem");
    $gem += $order->getAttr("gem");
    $ua->setAttr("gem",$gem);
    $order->setAttr("is_paid",true);
    $order->setAttr("paid_at",Date('Y-m-d H:i:s', time() ) );
      
    if(!$order->save() ){
        Logger::error("order save error: ".$_POST['xn_sig_order_number']); 
  print(json_encode(array('user_id'=>$order->getAttr("platform_id"), 'amount'=>$order->getAttr("amount"), 
  'order_number'=>$order->getAttr("id") ) ));

        exit();
    }else{
            if(!$ua->save() ){
                Logger::error("useraccount save error: order_id:".$idpay);
                 print(json_encode(array('user_id'=>$order->getAttr("platform_id"), 'amount'=>$order->getAttr("amount"), 
  'order_number'=>$order->getAttr("id") ) ));
                exit();
            }  
 //render(:text=>{:user_id=>order.xn_id, :amount=>order.amount, :order_number=>order.id}.to_json)    

    }
  

?>
