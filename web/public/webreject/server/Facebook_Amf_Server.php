<?php
require_once 'Zend/Amf/Server.php';
require_once LIB_ROOT.'AutoIncIdGenerator.php';
require_once MODEL_ROOT.'ModelFactory.php';

class Facebook_Amf_Server extends Zend_Amf_Server
{
	public function auth($platform_id, $auth)
	{
		return true;
	}

	protected function _dispatch($method, $params = null, $source = null)
	{
		$content = $params[0];
		//0 ru
		if($content->lang == 4)
		ServerConfig::setLang('en');
			

		$auth = $content->auth;

		if($platform_id == null || $this->auth($platform_id,$auth) ){
			return parent::_dispatch($method, $params, $source );
		}
		Logger::error('Method "' . $method . '" auth failed'." auth=$auth platform_id=$platform_id ");
		return array('status'=>'error','msg'=>'auth failed');
	}
}


