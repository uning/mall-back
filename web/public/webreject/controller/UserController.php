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
		// 需要修改：初始化成就系统 ，加上登录触发结算
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
		$ret['all'] = $tu->getAll();
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
		/*
		   $award = array( 1=>array( 'money'=>1000 )
		   ,2=>array( 'money'=>2000 )
		   ,3=>array( 'money'=>4000 )
		   ,4=>array( 'money'=>8000 )
		   ,5=>array( 'advtag'=>1 )
		   );
		 */
		$award = array( 1=>1000,2=>2000,3=>4000,4=>8000 );
		$uid = $params['u'];
		$tu = new TTUser( $uid );

//		$ret['bdata'] = $tu->getdata(); //for debug
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
		if( $loc < 5 ){
			$tu->numch( TT::MONEY_STAT,$award[$loc] );
		}
		if( $loc >= 5 ){
			$loc = 5;
			/*
			$adv_obj['tag'] = 1;
			$tu->puto( $adv_obj,TT::ADVERT_GROUP );
			*/
			
			$id = $tu->getoid( 'advert',TT::OTHER_GROUP );
			$adv = $tu->getbyid( $id );
			$adv['bag'][1] += 1;//连续登录5天，奖励商业广告一 1个
			$adv['id'] = $id;
			$tu->puto( $adv,TT::ADVERT_GROUP,false );
		}
		$ret['s'] = 'OK';	
		$ret['days'] = $last['continued'];
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
	 * 更新好友列表并返回好友信息。若参数为空，则返回存储的好友信息。
	 * @param $params
	 *  require    u          --  userid
	 *             fids       --  好友平台id字符串，用逗号隔开
	 * @return
	 *             s          --  OK,others fail
	 *             infos      --  好友信息数组
	 *             u          --  好友内部id数组 ? why? infos already have the id
	 *             a          --  好友帐户信息数组 //why not use map ,dbid 
	 */
	public function update_friends($params)
	{//暂时只返回10个好友测试
		//todo:
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		$fids = $params['fids'];
		$ret = array();
		if( !$fids ){//传参为空，返回存储的好友
			//给出几个假数据
			//			$fids = $tu->getf( TT::FRIEND_STAT );
			if(!$fids ){
				//for test
				$fids = "quest01,quest02,quest03,quest04,quest05,quest06,quest07,quest08,quest09";
			}			
		}
		else{
			$tu->putf( TT::FRIEND_STAT ,$fids);
		}
		$fids .= ",quest002,quest001";
		$fl = explode(",",$fids);
		foreach( $fl as $pid ){
			$p['pid'] = $pid;
			$finfos = TTGenid::getid($p); //by tingkun
			//print_r($finfos);
			if($finfos && $finfos['id']){//shit code
				//may be get friend into group
				$ftu = new TTUser($finfos['id']);
				$id = $finfos['id'];
				$acc = $ftu->getdata();
				$finfos = array_merge($finfos,$acc);
				$ret['infos'][] = $finfos;//what happened? two array ,one for id by tingkun
			}
		}
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
	 * 获取装饰,物品
	 * @param $params
	 *  require    u               -- userid
	 *  optional   fid             -- 好友的id，获取好友的信息才有此参数，否则不传此参数
	 * @return 物品列表，id 为下标
	 *             s               --   OK,others fail
	 */
	public function get_all( &$params )
	{
		$uid = $params['u'];
		$fid = $params['fid'];
		if( $fid ){
			$uid = $fid;
		}
		$tu = new TTUser( $uid );
		$ret['r'] = $tu->getAll();
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
	 *            cap       -- 商厦容量，字符串，长宽用逗号隔开
	 * @return 
	 *            s         --  OK
	 */

	public function enlarge_mall ( $params )
	{

		//该数组是UpgradeConfig.php中数组的一个子集，不涉及商厦容量无变化的元素
		$mall_level = array(
				0=>array('level'=>1,'capacity'=>"3,2",'needmoney'=>0)
			    ,1=>array('level'=>2,'capacity'=>"3,3",'needmoney'=>500)
				,2=>array('level'=>5,'capacity'=>"4,3",'needmoney'=>1000)
				,3=>array('level'=>9,'capacity'=>"5,3",'needmoney'=>10000)
				,4=>array('level'=>13,'capacity'=>"5,4",'needmoney'=>10000)
				,5=>array('level'=>17,'capacity'=>"6,4",'needmoney'=>30000)
				,6=>array('level'=>21,'capacity'=>"7,4",'needmoney'=>30000)
				,7=>array('level'=>25,'capacity'=>"7,5",'needmoney'=>50000)
				,8=>array('level'=>29,'capacity'=>"8,5",'needmoney'=>80000)
				,9=>array('level'=>33,'capacity'=>"8,6",'needmoney'=>100000)
				,10=>array('level'=>37,'capacity'=>"9,6",'needmoney'=>200000)
				,11=>array('level'=>41,'capacity'=>"10,6",'needmoney'=>300000)
				,12=>array('level'=>45,'capacity'=>"10,7",'needmoney'=>500000)
				,13=>array('level'=>54,'capacity'=>"10,8",'needmoney'=>800000)
				,14=>array('level'=>66,'capacity'=>"11,8",'needmoney'=>1000000)
				);	    
		$uid = $params['u'];
		$tu = new TTUser( $uid );

		$ua = $tu->getf( array( TT::CAPACITY_STAT,TT::EXP_STAT ) );
		$i = 0;//找出当前商厦状态对应数组中第几个
		foreach( $mall_level as $k=>$v ){
			if( $v['capacity'] != $ua['capacity'] ){
				continue;
			}
			$i = $k;		    
		}

		$level = UpgradeConfig::getLevel( $ua['exp'] );
		if( $level < $mall_level[$i+1]['level'] ){
			$ret['s'] = 'level';
			return $ret;
		}
		//检查金币
		$leftmoney = $tu->change( TT::MONEY_STAT,0-$mall_level[$i+1]['needmoney'] );
		if( $leftmoney<0 ){
			$ret['s'] = 'money';
			return $ret;
		}

		$tu->putf( TT::CAPACITY_STAT,$mall_level[$i+1]['capacity']);
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
		$data = array();
		foreach ($ups as $k=>$v){
			if( $v )
				$data[$k] = $v;
		}
		$tu->mputf( $data );
		$ret['s'] = 'OK';
		return $ret;
	}
}
