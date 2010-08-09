<?php
    require_once dirname(__FILE__)."/base.php";
    require_once dirname(__FILE__)."/mysqlconfig.php";
    $uid = 1;
    if( isset( $_POST['ids'] ) ){	
	    $ids = $_POST['ids'];
		$db =ServerConfig::connect_main_mysql( 0 );
		$invite = ModelFactory::Invitation( $db );
//	    if($uid && count($ids)>0 ){
        if( $uid && count($ids)>0 ){
		    $invite->invite($uid,$ids);
	    }	
	}

/*		
   $user_id     = AutoIncIdGenerator::getid($platform_id);
  if(isset($_POST["ids"]) ){
	$ids =  $_POST['ids'];
	$db  = ServerConfig::getdb_by_userid(0);
	$invitation = ModelFactory::Invitation($db);
	
	if($user_id && count($ids)>0 ){ 
		$row_count = $invitation->invite($user_id,$ids);
	}		
  }
  
  // Retrieve array of friends who've already authorized the app. 
  $fql = 'SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1='.$platform_id.') AND is_app_user = 1'; 
try{  $_friends = $facebook->api_client->fql_query($fql); 
  }catch(Exception $e){;}
  // Extract the user ID's returned in the FQL request into a new array. 
  $friends = array(); 
  if (is_array($_friends) && count($_friends)) {foreach ($_friends as $friend) { $friends[] = $friend['uid']; } } 
   // Convert the array of friends into a comma-delimeted string.
   $friends = implode(',', $friends); // Prepare the invitation text that all invited users will receive.
  if($friends){
	$udb  = ServerConfig::getdb_by_userid($user_id);
	$ua=ModelFactory::UserAccount($udb);
	$ua->setFriendList($user_id);
   }
*/
