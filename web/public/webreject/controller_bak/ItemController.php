<?php

class ItemController extends BaseController {

    
	/**
	 * 所有东西的购买入口
	 * todo:购买多个，钱币操作，购买限制检查
	 *      设定配置文件
	 * @param $params
	 *  require u  -- user id
	 *  require d  -- item data map （多个时为数组）
	 *                tid  -- type id(类型id)
	 *  optional num  -- 同一种东西购买数量              
	 * @return 
	 *  s   -- OK ,or other fail
	 *  id  -- the new generate id
	 *  money
	 *  gem
	 */
	public function buy($params)
	{
		//todo:添加购买验证逻辑
		//购买多个支持
		$uid = $params['u'];
		$item = ModelFactory::UserItem();
		$id = $item->put_super($params['d'],$uid);
		$ret['s'] = 'OK';
		$ret['id'] = $id;
		return $ret;		
	}
	
 
	/**
	 * 移动
	 * @param $params
	 *  require u  -- user id
	 *  require d  -- item数组
	 *                tid  -- type id(类型id)
	 *     
	 * @return 
	 *  s   -- OK ,or other fail
	 * 
	 */
	public function move($params)
	{
		//todo:
		$uid = $params['u'];
		$item = ModelFactory::UserItem();
		$item->putmulti_super($params['d'],$uid);
		$ret['s'] = 'ok';
		return $ret;
	}
	
	/**
	 * 卖出
	 * @param $params
	 *  require u  -- user id
	 *  require d  -- item ids
	 *     
	 * @return 
	 *  s   -- OK ,or other fail
	 */
    public function sale($params)
	{
		//todo:添加购买验证逻辑
		$uid = $params['u'];
		$item = ModelFactory::UserItem();
		$item->put_super($params['d'],$uid);
		$ret['s'] = 'ok';
		return $ret;
	}
}

