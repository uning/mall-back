<?php

/**
 * 
 * @author uning
 *
 */
class FriendController extends BaseController{ 
	/**
	 * 添加好友
	 * @param $params
	 * @return unknown_type
	 */
	public function add($params)
	{
		//todo:
		$uid = $params['u'];
		$uf = ModelFactory::UserFriend();
		$uf->put_sper($params['d'],$uid);		
	}
	
	/**
	 * 获取
	 * @param $params
	 * @return unknown_type
	 */
	public function get($params)
	{
		//todo:
		$uid = $params['u'];
		$uf = ModelFactory::UserFriend();
		$ret['s'] = 'ok';
		$ret['r'] = $uf->get($uid);
		return $ret;
	}
	
	
     /**
	 * 拜访
	 * @param $params
	 * @return unknown_type
	 */
	public function visit($params)
	{
		//todo:
		$uid = $params['u'];
		$fid = $params['f'];
		$item = ModelFactory::UserFriend();
		$item->put_super($params['item'],$uid);	
	}	
	//other op
}

