<?php
require_once 'Zend/Amf/Server.php';
require_once LIB_ROOT.'AutoIncIdGenerator.php';
require_once MODEL_ROOT.'ModelFactory.php';
require_once LIB_ROOT.'renren/renren.php';


class Renren_Amf_Server extends Zend_Amf_Server
{

	public function authRenren($platform_id, $session_key)
	{
		$cache=ServerConfig::connect_memcached('cache_server');
		if(!$cache){
			return true;
		}
		$cid = 'session-key-'.$platform_id;
		$ret = $cache->get($cid);
		if(!$ret || $ret!= $session_key  ){
			$renren = new Renren($session_key, $platform_id);
			$result = $renren->api_client->users_getLoggedInUser();
			if(is_array($result) && is_array($result[0]) && $result[0][0] == $platform_id){
				$cache->set($cid, $session_key);
				return true;
			}
			return false;
		}
		return true;

	}

	protected function _dispatch($method, $params = null, $source = null)
	{
		$content = $params[0];
		ServerConfig::setLang($content->lang);
		return parent::_dispatch($method, $params, $source );
			
		/*

		$session_key = $content->auth;
		if($content->platform_id !=null)
		{
		$platform_id = $content->platform_id;
		}
		else if($content->user_id !=null)
		{
		$user_id = $content->user_id;
		$platform_id = AutoIncIdGenerator::getPlatformId($user_id);
		}
		if($platform_id != null && $this->authRenren($platform_id,$session_key) ){
		return parent::_dispatch($method, $params, $source );
		}
		Logger::error('Method "' . $method . '" auth failed'." sk=$session_key platform_id=$platform_id ");
		return array('status'=>'error','msg'=>'auth failed');
		*/
		 
	}

}


