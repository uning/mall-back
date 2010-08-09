<?php
require_once 'Zend/Amf/Server.php';
require_once LIB_ROOT.'AutoIncIdGenerator.php';
require_once MODEL_ROOT.'ModelFactory.php';
require_once LIB_ROOT.'vk/vk.php';

class Vk_Amf_Server extends Zend_Amf_Server
{
	protected function _dispatch($method, $params = null, $source = null)
	{
		$content = $params[0];
		return parent::_dispatch($method, $params, $source );
		$auth = $content->auth;
		ServerConfig::setLang($content->lang);
		if($content->platform_id !=null)
		{
			$platform_id = $content->platform_id;
		}
		else if($content->user_id !=null)
		{
			$user_id = $content->user_id;
			$platform_id = AutoIncIdGenerator::getPlatformId($user_id);
		}
		$vk = new Vk($platform_id);
		if($vk->auth($auth) ){
			return parent::_dispatch($method, $params, $source );
		}
		Logger::error('Method "' . $method . '" auth failed'." sk=$session_key platform_id=$platform_id ");
		return array('status'=>'error','msg'=>'auth failed');
		 
	}
}


