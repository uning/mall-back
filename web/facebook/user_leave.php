<?php
define('FB_CURR',$myloc);
require_once  FB_CURR.'/bg/base.php'; 

function arr2jsonstr(&$a,&$a1=null)
{

	if($a1){
		foreach($a1 as $k=>$v){
			$a[$k]=$v;
		}
	}
    return json_encode($a);

}

$writer = new  Zend_Log_Writer_Stream(LOG_ROOT.'user_pop.log');
Logger::registerLogger('user_pop',$writer,null,true);
Logger::debug(arr2jsonstr($_POST,$_GET));

