<?php 
    include "./header.php";
    $platform_id = "renren".$renren->user;
    $session_key = $renren->session_key;

    $user_id     = AutoIncIdGenerator::genid($platform_id);
    $db          = ServerConfig::getdb_by_userid($user_id);
    $up = ModelFactory::UserProfile($db);
    $up->find($user_id);

    $up->setAttr("intparam2", -1);
    $up->setAttr("intparam1", time() );
    $up->save();
