<?php

class DataS{
    
	/**
	 * @param $params
	 *  require u  -- user id
	 *  require d  -- record  
	 *             id    --  'uid:g1:g2:xxx'
	 *             others      -- amount 
	 * @return 
	 *  s   -- OK ,or other fail
	 *  id  -- the  id
	 */
	public function put($params)
	{
		$uid = $params['u'];
		$tm = new TTDS($uid);
		$ret['id'] = $tm->puto($params['d'],$uid);
		$ret['s'] = 'OK';
		return $ret;
	}


	/**
	 * 移动
	 * @param $params
	 *  require u        -- user id
	 *  require ids        -- ids 数组
	 *                   tag  -- type id(类型id)
	 *                   pos  -- position information of items
	 * @return 
	 *  s   -- OK ,or other fail
	 *  d   --数组
	 */
	public function get($params)
	{
		$uid = $params['u'];
		$tm = new TTDS($uid);
                $ret['d'] = $tm->getbyids($params['ids'],$uid);
		$ret['s'] = 'OK';
		return $ret;
	}
	/**
        *获取所有
	*如果有pre,
	*
        */
	public function getAll($params)
	{
		$uid = $params['u'];
		$tm = new TTDS($uid);
                $ret['d'] = $tm->getAll();
		$ret['s'] = 'OK';
		return $ret;
	}
}
