<?php

class CarController extends BaseController{    
	/**
	 * 购买卡车
	 * @param $params
	 *  require u  -- user id
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
		$car = ModelFactory::UserCar();
		$id = $car->put_super( $params,$uid );
		$ret['s'] = 'ok';
		$ret['id'] = $id;
		return $ret;
	}

	/**
	 * 卖出卡车
	 * @param $params
	 * require  u  -- user_id
	 * require  c  -- car be sold
	 * @return unknown_type
	 * s   -- ok
	 * c   car status
	 */
    public function sale($params)
	{
		//todo:添加购买验证逻辑
		//购买多个支持
		$uid = $params['u'];
		$car = ModelFactory::UserCar();
		$car->put_super($params['c'],$uid);
		$ret['s'] = 'ok';
		$ret['c'] = $car->get( $uid );
		return $ret;
	}

	
	/**
	 * 移动货车
	 * @param $params
	 * require  u  -- user_id
	 * require  c  -- car be moved
	 * @return unknown_type
	 * s   -- ok
	 * c   -- car status
	 */
    public function move($params)
    {
        $uid = $params['u'];
        $car = ModelFactory::UserCar();
        $car->put_super($params['c'],$uid);
        $ret['s'] = 'ok';
        return $ret;
    }	

	/**
	 * 进货
	 * @param $params
	 * require  u  -- user_id
	 * require  c  -- car be lold
	 * require  g  -- the goods to be load
	 * @return unknown_type
	 * s   -- ok
	 * c   -- car status
	 * t   -- current time
	 */
    public function go_goods($params)
	{
		//todo:添加购买验证逻辑
		//购买多个支持
		$uid = $params['u'];
		$car = ModelFactory::UserCar();
		$goods = ModelFactory::UserGood();
		$db = ServerConfig::getdb_by_userid($uid);
		$ua = ModelFactory::UserAccount($db);
		$car->put_super($params['c'],$uid);
		$item_count = 10;//货车容量从配置文件读取
		for($i=0;$i<$item_count;$i++){
		    $goods->put_super($params['c'],$uid);
		}
		$ret['s'] = 'ok';
		$ret['t'] = time();
		return $ret;
	}
	
    /**
	 *  收货物
	 * @param $params
	 * require  u  -- user_id
	 * require  c  -- car be unlold
	 * require  g  -- the goods to be unload
	 * @return unknown_type
	 * s   -- ok
	 * c   -- car status
	 * t   -- current time
	 */
    public function  get_goods($params)
	{
		//todo:添加购买验证逻辑
		//购买多个支持
		$uid = $params['u'];
		$car = ModelFactory::UserCar();
		$goods = ModelFactory::UserGood();
		$car->put_super($params['c'],$uid);
		$goods->put_super($params['g'],$uid);
		$ret['s'] = 'ok';
		$ret['t'] = time();
		return $ret;
	}
}