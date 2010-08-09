<?php
class UserController extends BaseController
{

	/**
	 * 登录游戏
	 *   
	 * @param $params
	 *    require   pid      -- platformid
	 *    optional  anyfield  --头像等保存到 infos
	 * @return 
	 *    u      userid
	 * 	  infos  map 玩家基本信息,头
	 *    accs   map 玩家相关数值信息
	 */
	public function login($params)
	{
		$pid = $params['pid'];
		$uid = AutoIncIdGenerator::genid( $pid );
		$db = ServerConfig::getdb_by_userid( $uid );
		$ua = ModelFactory::UserAccount( $db );
		$uin = ModelFactory::UserInfo();
        $uin->put( $params ,$uid );
        $ret['s'] = 'ok';
        $ret['infos'] = $uin->get( $uid );
        $ret['u'] = $uid;
		return $ret;
	}
	
	
	/**
	 * 
	 * @param $params
	 *   require  u      -- 玩家id
	 *   optional infos  --其他数据
	 * @return 
	 *   s         OK
	 */
    public function update_info($params)
	{
		$uid = $params['u'];
		$uin = ModelFactory::UserInfo();
		$uin->put( $params,$uid );
		$ret['s'] = 'ok';
	}
	
	/**
	 * 获取所有或部分item
	 * @param $params
	 *  require    u      -- userid
	 *  optional   ids    -- id列表
	 *  
	 *  optional   fromid -- 起始物品id 
	 *  optional   num    -- 返回物品数目
	 *  
	 *  optional   rev -- 是否反序 
	 * @return 物品列表，id 为下标
	 *   s   OK,others fail
	 *   r   id为下标的数组
	 * 
	 */
    public function get_items( &$params )
    {
        $uid = $params['u'];
        $item = ModelFactory::UserItem();
        $ret['s'] = 'ok';
        $ret['r'] = $item->get( $uid );
		return $ret;
    }
    
    /**
	 * 获取所有或部分car
	 * @param $params
	 *  require    u      -- userid
	 *   s   OK,others fail
	 *   c   cars
	 * 
	 */
    public function get_cars( &$params )
    {
        $uid = $params['u'];
        $car = ModelFactory::UserCar();
		$ret['s'] = 'ok';
		$ret['c'] = $car->get($uid);
		return $ret;
    }
    
    
     /**
	 * 获取所有或部分goods
	 * @param $params
	 *  require    u      -- userid
	 * @return 物品列表，id 为下标
	 *   s   OK,others fail
	 *   g   goods
	 */
    public function get_goods ( $params )
    {
        $uid = $params['u'];
    	$goods = ModelFactory::UserGood();
		$ret['s'] = 'ok';
		$ret['g'] = $goods->get($uid);
		return $ret;
    }
    
    
    /**
	 * 获取所有或部分好友
	 * @param $params
	 *  require    u      -- userid
	 *   s   OK,others fail
	 *   f   friends
	 */
    public function get_friends ( $params )
    {
        $uid = $params['u'];
    	$fl = ModelFactory::UserFriend();
    	$ret['s'] = 'ok';
		$ret['f'] = $fl->get($uid);
		return $ret;
    }
    
     /**
	 * 获取所有或部分消息记录(消息默认按时间排序)
	 * @param $params
	 *  require    u      -- userid
	 * @return 物品列表，id 为下标
	 *   s   OK,others fail
	 *   m   message
	 */
    public function get_msgs ( $params )
    {
        $uid = $params['u'];
    	$mg = ModelFactory::UserMessage();
    	$ret['s'] = 'ok';
		$ret['m'] = $mg ->get( $uid );
		return $ret;
    }    
}