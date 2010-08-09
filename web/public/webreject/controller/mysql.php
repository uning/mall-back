<?php
require_once dirname(__FILE__)."/../../base.php";
require_once MODEL_ROOT."ModelFactory.php";


function test_invitation( &$db )
{
    $invit = ModelFactory::Invitation( $db );
    $data = array('user_id'=>1
                  ,'to_id'=>2
                  ,'gift_id'=>3
                  ,'is_accepted'=>1
                  ,'updated_at'=>date( TM_FORMAT,time() )
                  );
    $invit->insert( $data );
}

function test_order( &$db )
{
    $order = ModelFactory::Order( $db );
    $now = time();
    $data = array('order_id'=>"order.$now"
                  ,'user_id'=>1
                  ,'pid'=>"pid.$now"
                  ,'amount'=>10
                  ,'gem'=>20
                  ,'is_paid'=>1
                  ,'order_type'=>"facebook"
                  ,'paid_at'=>date( TM_FORMAT,$now )
                  ,'created_at'=>date( TM_FORMAT,$now )
                  );
    $order->insert( $data );
}

function test_present( &$db )
{
    $now = time();
    $present = ModelFactory::Present( $db );
    $now = time();
    $data = array('user_id'=>1
                  ,'donor_id'=>2
                  ,'message'=>'wahaha'
                  ,'item_id'=>3
                  ,'done'=>0
                  ,'created_at'=>date( TM_FORMAT,$now )
    );
    $present->insert( $data );
}

//$db = ServerConfig::getdb_by_userid(0);
$db = ServerConfig::connect_main_mysql(0);
//test_invitation( $db );
test_order( $db );
//test_present($db);