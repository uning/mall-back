<?php 
//�û���ȨӦ�ú�Ļص�
//ʹ��user_profiles �е�intparam2����¼�û���״̬�� -1����ʾ�û�ɾ��Ӧ�ã�1��ʾ�û���ȨӦ��
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