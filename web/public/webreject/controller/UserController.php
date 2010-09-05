<?php
class UserController 
{
	/**
	 * 登录游戏
	 *   
	 * @param $params
	 *    require   pid      -- platformid
	 *    optional  anyfield  --头像等保存到 infos
	 * @return 
	 *    u      userid
	 * 	  infos  map 玩家基本信息,头像
	 *    a          玩家帐户信息，金币，宝石，经验
	 *    accs   map 玩家相关数值信息
	 *    t      server time
	 */
	public function login($params)
	{
		$now = time();
		$params['at'] = $now;	
		$data = TTGenid::genid($params,$new);
		$uid = $data['id'];
		$tu = new TTUser( $uid);
		//if( 1 || $new ){		
		if($new ){		
			//初始化用户信息
			$tu->initAccount();
		}			
		$ret['infos'] = $data;		
		$ret['u'] = $uid;
		$ret['a'] = $tu->getdata();
		$ret['s'] = 'OK';
		$ret['t'] = time();
		$params['u']=$uid;
		TTLog::record(array('m'=>__METHOD__,'tm'=> $_SERVER['REQUEST_TIME'],'intp1'=>$new,'u'=>'uid','sp1'=>$params['pid']));
		return $ret;
	}

	/**
	 * 处理用户登录后，结算前的一些事务，如登录奖励
	 * @param $params
	 *  require     u            --  user id
	 * @return 
	 *              s            --  OK
	 *              days         --  持续登录的天数
	 */
	public function precheckout( $params )
	{
		$award = array( 1=>1000,2=>2000,3=>4000,4=>8000,5=>20000 );
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		$now = time();
		$today_start = strtotime ( date( TM_FORMAT,strtotime( date("Y-m-d",$now) ) ) );
		$yesterday_start = strtotime( date( TM_FORMAT,strtotime( date("Y-m-d",$now-86400) ) ) );
		$last = $tu->getf( array( 'lastawardtime','continued' ) );
		if( $last['lastawardtime'] >= $today_start ){
			$ret['s'] = 'repeat';
			return $ret;
		}
		if( $last['lastawardtime'] >= $yesterday_start ){
			$last['continued'] += 1;
		}
		else{
			$last['continued'] = 1;
		}
		$last['lastawardtime'] = $now;
		$tu->mputf( $last );
		$loc = $last['continued'];
		if( $loc > 5 ){
		    $loc = 5;
		}		
//		if( $loc < 5 ){
		if( $loc > 0  ){
			$tu->numch( TT::MONEY_STAT,$award[$loc] );
		}
		/*
		if( $loc >= 5 ){
			$loc = 5;			
			$id = $tu->getoid( 'advert',TT::OTHER_GROUP );
			$adv = $tu->getbyid( $id );
			$adv['bag'][1] += 1;//连续登录5天，奖励商业广告一 1个
			$adv['id'] = $id;
			$tu->puto( $adv,TT::ADVERT_GROUP,false );
		}
		*/
		$ret['s'] = 'OK';	
		$ret['days'] = $last['continued'];
		TTLog::record(array('m'=>__METHOD__,'tm'=> $_SERVER['REQUEST_TIME'],'u'=>$uid,'intp1'=>$last['continued']));
		return $ret;				
	}

	/**
	 * 更新用户信息
	 * @param $params
	 *  require  infos           --  用户数据
	 *  option                   icon        --  headicon
	 *  option                   name        --  mall name
	 *  option                   gender      --  
	 *  option                   session     --  session key
	 * @return 
	 *                            s              --  OK
	 */
	public function update_info($params)
	{
		$infos = $params['infos'];
		$uid = $params['u'];
		TTGenid::update($infos,$uid);
		$ret['s'] = 'OK';
		return $ret;
	}

	/**
	 * 获取所有或其他部分item
	 * @param $params
	 *  require    u               -- userid
	 *  optional   fid             -- 好友的id，获取好友的信息才有此参数，否则不传此参数
	 * @return 物品列表，id 为下标
	 *             s               --   OK,others fail
	 *             r               --   id为下标的数组
	 *             scale           --   mall的商店容量 
	 */
	public function get_items( &$params )
	{
		$uid = $params['u'];
		$fid = $params['fid'];
		if( $fid ){
			$uid = $fid;
		}
		$tu = new TTUser($uid);
		$ret['r']= $tu->get(TT::ITEM_GROUP);
//		$ret['u']= $tu->getAll();
		$ret['scale'] = $tu->getf(TT::CAPACITY_STAT);
		$ret['s'] = 'OK';
		return $ret;
	}

	/**
	 * 获取所有或部分car
	 * @param $params
	 *  require    u               -- userid
	 *  optional   fid             -- 好友的id，获取好友的信息才有此参数，否则不传此参数
	 * @return 物品列表，id 为下标
	 *             s               --   OK,others fail
	 *             c               --  cars
	 *             g               --  garage
	 * 
	 */
	public function get_cars( &$params )
	{
		$uid = $params['u'];
		$fid = $params['fid'];
		if( $fid ){
			$uid = $fid;
		}
		$tu = new TTUser( $uid );
		$ret['g'] = $tu->getf(TT::GARAGE_STAT);
		$ret['c'] = $tu->get(TT::CAR_GROUP);	    
		$ret['s'] = 'OK';
		return $ret;
	}


	/**
	 * 获取所有或部分goods
	 * @param $params
	 *  require    u               -- userid
	 *  optional   fid             -- 好友的id，获取好友的信息才有此参数，否则不传此参数
	 * @return 物品列表，id 为下标
	 *             s               --   OK,others fail
	 *             g               --   goods
	 */
	public function get_goods ( &$params )
	{
		$uid = $params['u'];
		$fid = $params['fid'];

		if( $fid ){
			$uid = $fid;
		}
		$tu = new TTUser( $uid );
		$ret['g'] = $tu->get(TT::GOODS_GROUP );
		$ret['s'] = 'OK';
		return $ret;
	}

	/**
	 * 扩大商厦
	 * @param $params
	 *   require  u         -- 玩家id
	 * @return 
	 *            s         --  OK
	 */

	public function enlarge_mall ( $params )
	{
		$level2money = array(//键为等级，值为所需金币
		         0=>0
			    ,2=>500
				,5=>1000
				,9=>10000
				,13=>10000
				,17=>30000
				,21=>30000
				,25=>50000
				,29=>80000
				,33=>100000
				,37=>200000
				,41=>300000
				,45=>500000
				,54=>800000
				,66=>1000000
				);	    			 
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		$exp = $tu->getf( TT::EXP_STAT );
		$need = UpgradeConfig::getUpgradeNeed( $exp );
//		$leftmoney = $tu->change( TT::MONEY_STAT,0-$level2money[$need['level']]); //有可能在之间的某个level调用此函数
        $l = 0;
        foreach( $level2money as $level=>$money ){
            if(  $level < $need['level']){
                $l = $level;
                continue;
            }
            break;
        }
        $leftmoney = $tu->change( TT::MONEY_STAT,0-$level2money[$l] ); 
		if( $leftmoney<0 ){
			$ret['s'] = 'money';
			return $ret;
		}
		$tu->putf( TT::CAPACITY_STAT,$need['shopwidth'].",".$need['shopheight'] );
		$ret['s'] = 'OK';
		return $ret;
	}

	/**
	 * 金手指
	 * @param $params
	 *   require  u               -- 玩家id
	 *            money           -- 增加金币数
	 *            gem             -- 增加宝石数
	 *            exp             -- 增加经验数
	 * @return 
	 *            s         --  OK
	 */
	public function cheat( $params )
	{
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		$tu->numch( TT::MONEY_STAT,$params['money'] );
		$tu->numch( TT::GEM_STAT,$params['gem'] );
		$tu->numch( TT::EXP_STAT,$params['exp'] );
		$ret['a'] = $tu->getf( array( TT::MONEY_STAT,TT::GEM_STAT,TT::EXP_STAT ) );
		$ret['s'] = 'OK';
		return $ret;
	}

	/**
	 * 更新用户形象等
	 * @param $params
	 *   require  u               -- 玩家id
	 *            ups             -- 形象数组
	 * @return 
	 *            s         --  OK
	 */	
	
	public function update_profile( $params )
	{
		$uid = $params['u'];
		$ups = $params['ups'];
		$tu = new TTUser( $uid );
		$tu->mputf( $ups );
		$ret['s'] = 'OK';
		return $ret;
	}
}
