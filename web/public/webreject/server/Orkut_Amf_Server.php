<?php
require_once 'Zend/Amf/Server.php';
require_once LIB_ROOT.'AutoIncIdGenerator.php';
require_once MODEL_ROOT.'ModelFactory.php';

class Orkut_Amf_Server extends Zend_Amf_Server
{
	public function authOrkut($platform_id, $auth)
	{
		$md5_value = md5($platform_id . '_HH@orkut');
		if($md5_value != $auth)
		{
			return false;
		}
		return true;
	}

	protected function _dispatch($method, $params = null, $source = null)
	{
		$content = $params[0];
		ServerConfig::setLang($content->lang);
		return parent::_dispatch($method, $params, $source );

		$auth = $content->auth;

		if($platform_id != null || $this->authOrkut($platform_id,$auth) ){
			return parent::_dispatch($method, $params, $source );
		}
		Logger::error('Method "' . $method . '" auth failed'." auth=$auth platform_id=$platform_id ");
		return array('status'=>'error','msg'=>'auth failed');
	}
}


