<?php 
//用户授权应用后的回调
//使用user_profiles 中的intparam2来记录用户的状态， -1来表示用户删除应用，1表示用户授权应用
    include "./header.php";
    $platform_id = "renren".$renren->user;
    $session_key = $renren->session_key;
 
    $user_id     = AutoIncIdGenerator::genid($platform_id);
    $db          = ServerConfig::getdb_by_userid($user_id);
    $up = ModelFactory::UserProfile($db);
    $up->find($user_id);

    $up->setAttr("intparam2", 1);
	$up->setAttr("intparam1", time() );
    $up->save();