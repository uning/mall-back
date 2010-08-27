<?php
require_once 'base.php';
require_once  LIB_ROOT.'JsonServer.php';

		$show_all = false;
		JsonServer::registerController('Achieve');
		JsonServer::registerController('Advert');
		JsonServer::registerController('Cinema');
		JsonServer::registerController('Gift');
		JsonServer::registerController('Man');
		JsonServer::registerController('UserController');
		JsonServer::registerController('ItemController');
		JsonServer::registerController('CarController');
		JsonServer::registerController('GoodsController');
		JsonServer::registerController('Task');
		JsonServer::registerController('Friend');
		JsonServer::registerController('DataS');
         
		$allms = JsonServer::getAllMethod();
		foreach($allms as $m){
			$ca=explode('.',$m);
			echo "'$m'=>1,\n";
		}
