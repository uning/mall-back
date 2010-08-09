<?php

require_once 'Zend/Amf/Server.php';



class Test_Amf_Server extends Zend_Amf_Server
{
	protected function _dispatch($method, $params = null, $source = null)
	{
		$st=microtime(true);
		$content = $params[0];
		$mypre="$source-$method";
		if($content->lang == 4)
		ServerConfig::setLang('en');
		if($content->lang == 3)
		ServerConfig::setLang('zh');
		CrabTools::mydump($params[0],REQ_DATA_ROOT.$mypre.'.param');
		Logger::info("$source::$method");
		try{
			$ret= parent::_dispatch($method, $params, $source );

			$parastr=print_r($params,true);
			$et=microtime(true);
			$cost=$et-$st;
			$cost=ceil(1000000*$cost);
			Logger::info("$source::$method  $cost ,  [{$ret['status']}]  [{$ret['msg']}] $parastr");
			CrabTools::myprint($ret,REQ_DATA_ROOT.$mypre.'.resp');
			return $ret;
		}catch(Exception $e){
			CrabTools::myprint($e,REQ_DATA_ROOT.$mypre.'.exp');
			return array('status'=>'error','msg'=>'exe exception');
		}

		 
		Logger::error('Method "' . $method . '" auth failed'." sk=$session_key platform_id=$platform_id ");
		return array('status'=>'error','msg'=>'auth failed');
	}
}


