<?php

class CarController
{
	static $_config = array( 2001=>array( 'addgoods'=>1,'gem'=>array( 1=>1,10=>9,30=>24,100=>70 ) )
			,2002=>array( 'addgoods'=>2,'gem'=>array( 1=>2,10=>18,30=>48,100=>120 ) ) 
			,2003=>array( 'accelerate'=>3600,'gem'=>array( 1=>1,10=>9,30=>24,100=>70 ) ) 
			,2004=>array( 'accelerate'=>21600,'gem'=>array( 1=>5,10=>40,30=>90,100=>250 ) ) 
			,2005=>array( 'accelerate'=>356400,'gem'=>array( 1=>10,10=>80,30=>180,100=>400 ) )
			,2006=>1
			);
	/**
	 * 购买卡车
	 * @param $params
	 *  require u  -- user id
	 *  require c  -- car type
	 *             pos    --  position information about car
	 *             tag    --  tag to distinguish car type
	 * @return 
	 *  s   -- OK ,or other fail
	 *  ids  -- the new generate id
	 *  a   --  the account of user 
	 */    
	public function buy($params)
	{
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		$ids = array();
		foreach( $params['c'] as $index=>$row ){
			$buy_ret = $tu->buyItem($row['tag']);
			if( $buy_ret['s'] != 'OK' ){
				$ret['index'] = $index;
				return $buy_ret;
			}
			$row['id'] = $tu->getdid(null,TT::CAR_GROUP);	
			$tu->puto($row);
			$ids[$index]=$row['id'];
		}	
		$ret['s'] = 'OK';
		$ret['ids'] = $ids;
		return $ret;
	}

	/**
	 * 卖出卡车
	 * @param $params
	 * require  u         --  user_id
	 * require  d         --  car id array
	 * @return
	 * s   -- OK
	 * a   -- user account
	 */
	public function sale($params)
	{
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		foreach ( $params['d'] as $index=>$id ){
			$car_obj = $tu->getbyid($id);
			if( !$car_obj ){
				$ret['s'] = 'notexist';
				$ret['index'] = $index;
				return $ret;
			}
			$sale_ret = $tu->saleItem( $car_obj);
			if( $sale_ret['s'] != 'OK' ){
				$sale_ret['index'] = $index;
				return $sale_ret;
			}
		}
		$tu->remove( $params['d'] );
		$ret['s'] = 'OK';
		return $ret;
	}


	/**
	 * 移动货车
	 * @param $params
	 * require  u  -- user_id
	 * require  c  -- car to  be moved
	 *             id   -- the car id
	 *             pos  -- the car position
	 * @return
	 * s   --  OK
	 */
	public function move($params)
	{
		$uid = $params['u'];
		$tu = new TTUser($uid);
		$ids = array();
		foreach( $params['c'] as $index=>$row ){		
			$car_obj = $tu->getbyid( $row['id']);
			if( !$car_obj ){
				$ret['s'] = 'notexist';
				$ret['index'] = $index;
				return $ret;
			}
			if( $car_obj['t'] ){
				$ret['s'] = 'go_goods';
				$ret['index'] = $index;
				return $ret;
			}
			$tu->puto($row,TT::CAR_GROUP,true);
		}
		$ret['s'] = 'OK';
		return $ret;
	}	

	/**
	 * 进货
	 * @param $params
	 * require  u  -- user_id
	 * require  c  -- the car to be lold
	 *             id        -- id of the car
	 *             goodsTag   -- goods type the car can load
	 * require  w  -- 支付方式，m为金币，g为宝石
	 * @return
	 * s   -- OK
	 * c   -- car status
	 */
	public function go_goods($params)
	{
		$uid = $params['u'];
		$goodsTag = $params['c']['goodsTag'];
		$cid = $params['c']['id'];
		$tu = new TTUser($uid);
		$car_obj = $tu->getbyid( $cid );
		if( !$car_obj ){
			$ret['s'] = 'notexist';
			return $ret;
		}
		if( $car_obj['t'] ){
			$ret['s'] = 'doing';
			return $ret;
		}
		$car = ItemConfig::getItem( $car_obj['tag'] );
		if( !$car ){
			$ret['s'] = 'caritemnotexist';
			return $ret;
		}			
		$goods = ItemConfig::getItem( $goodsTag );
		if( !$goods ){
			$ret['s'] = 'goodsitemnotexist';
			return $ret;
		}
		$buy_ret = $tu->buyItem( $goods['tag'],$car['goodsNumber']);
		if( $buy_ret['s'] != 'OK' ){
			return $buy_ret;
		}
		$add_exp = $goods['exp']*$car['goodsNumber'];
		if( $add_exp ){
		    $tu->addExp( $add_exp );
		}
		$now = time();
		$car_obj['goodsTag'] = $goodsTag;
		$car_obj['t'] = $now;
		$tu->puto( $car_obj,TT::CAR_GROUP );
		$gogoods_count = $tu->numch( 'gogoods_count',1 );
		$ret['s'] = 'OK';
		$ret['c'] = $car_obj;   
		return $ret;
	}

	/**
	 * 收货物
	 * @param $params
	 * require  u  -- user_id
	 * require  c  -- car to be unload  包含goodsTag
	 *             id        -- id of the car
	 *             goodsTag   -- goods type the car can unload
	 * @return
	 * s   -- OK
	 * c   -- car status
	 * g   -- goods ids
	 */
	public function  get_goods($params)
	{
		$uid = $params['u'];
		$cid = $params['c']['id'];
		$goodsTag = $params['c']['goodsTag'];
		$tu = new TTUser($uid);
		$now = time();
		$car_obj = $tu->getbyid( $cid );
		$gogoodstime = $car_obj['t'];
		if( !$car_obj ){
			$ret['s'] = 'carobjnotexist';
			return $ret;
		}
		$car = ItemConfig::getItem( $car_obj['tag'] );
		if( !$car ){
			$ret['s'] = 'caritemnotexist';
			return $ret;
		}
		$goods = ItemConfig::getItem( $goodsTag );
		if( !$goods ){
			$ret['s'] = 'goodstypenotexist';
			return $ret;
		}
		if( $car_obj['goodsTag'] != $goodsTag ){
			$ret['s'] = 'goodstagincorrect';
			return $ret;
		}
		if( !$car_obj['t'] ){
			$ret['s'] = 'notgogoods';
			return $ret;
		}
		if( $now - $car_obj['t'] < $goods['buytime'] ){//取消货车的时间减免
			$ret['s'] = 'timeleft';
			return $ret;
		}
		
		if( $car_obj['addgoods'] ){
			$num += $car_obj['addgoods'];
			unset( $car_obj['addgoods'] );
		}
		if( $car_obj['help'] ){
			foreach( $car_obj['help'] as $data ){
				$num += $data;
			}
			unset( $car_obj['help'] );
		}
		if( $car_obj['copolitTag'] ){
			unset( $car_obj['copolitTag'] );
		}
		unset( $car_obj['t'] );        
		$num += $car['goodsNumber'];

		$tu->puto( $car_obj,TT::CAR_GROUP,false);
		$ret['c'] = $car_obj;
		if( $now - $gogoodstime > 3*$goods['buytime'] ){//货物过期
		    $ret['s'] = 'expiration';
		    return $ret;
		}
		$goods_data['pos'] = 's';
		$goods_data['tag'] = $goodsTag;
		$ids = array();
		for( $i=0;$i<$num;$i++ ){
			$id = $tu->getoid(null,TT::GOODS_GROUP );
			$goods_data['id'] = $id;
			$ids[$i] = $id;
			$tu->puto( $goods_data,TT::GOODS_GROUP ,false);
		} 
		
		$add_exp = $goods['exp']*$car['goodsNumber'];//乘以载重箱，经验不包括好友帮助增加的箱数
		if( $add_exp ){
		    $tu->addExp( $add_exp );
		}
		
		$ret['s'] = 'OK';    
		$ret['g'] = $ids;
		return $ret;
	}

	/**
	 * 获取副驾驶
	 * @param $params
	 * require          u             --   user_id
	 * @return
	 *                  s             --   OK
	 *                  copi          --   副驾驶道具
	 */
	public function get_copolit( $params )
	{
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		$id = $tu->getoid( 'copilot',TT::OTHER_GROUP );
		$copilot = $tu->getbyid( $id );
		if( !$copilot || !$copilot['bag']){
			$ret['s'] = 'copilotnotexsit';
			return $ret;
		}
		$ret['s'] = 'OK';
		$ret['copi'] = $copilot;
		return $ret;
	}	

	/**
	 * 买副驾驶
	 * @param $params
	 * require          u             --   user_id
	 *                  tag           --   副驾驶tag
	 *                  num           --   一次购买数量
	 * @return
	 *                  s             --   OK
	 *                  tag           --   副驾驶tag
	 *                  num           --   一次购买数量
	 *                  copi          --   测试信息
	 */
	public function buy_copolit( $params )
	{
		$uid = $params['u'];
		$tag = $params['tag'];
		$num = $params['num']; 
		
		$stat['tag']=$tag;
		$stat['op']='buy_copolit';
		$stat['num']=$num;
		$stat['u']=$uid;
		$stat['tm']=$_SERVER['REQUEST_TIME'];
		$gemt = TT::GemTT();

		$tu = new TTUser( $uid );
		$copi = self::$_config[$tag];
		if( !$copi ){
			$ret['s'] = 'copinotexsit';

			$stat['s']=$ret['s'];
			$gemt->putKeep(null,$stat);
			return $ret;
		}
		$gem = $tu->change( 'gem',0-$copi['gem'][$num] );
		if( $gem< 0 ){
			$stat['s']=$ret['s'];
			$gemt->putKeep(null,$stat);
			$ret['s'] = 'gem';
			$stat['s']=$ret['s'];
			$gemt->putKeep(null,$stat);
			return $ret;
		}   
		$id = $tu->getoid('copilot',TT::OTHER_GROUP );	    
		$copilot = $tu->getbyid( $id );
		$copilot['id'] = $id;
		$copilot['bag'][$tag] += $num;
		$tu->puto( $copilot );

		$ret['s'] = 'OK';
		$ret['gem'] = $gem;


		$ret['tag'] = $tag;
		$ret['num'] = $num;

		$stat['gem'] = $copi['gem'][$num];
		$stat['t'] = 'gem';
		$stat['s']=$ret['s'];
		$gemt->putKeep(null,$stat);
		return $ret;
	}

	/**
	 * 使用副驾驶
	 * @param $params
	 * require          u             --   user_id
	 *                  tag           --   副驾驶类别
	 *                  cid           --   car id
	 * @return
	 *                  s             --   OK
	 *                  tag           --   副驾驶tag
	 *                  car           --   测试信息
	 *                  copi          --   测试信息
	 */
	public function apply_copolit( $params )
	{
		$uid = $params['u'];
		$tag = $params['tag'];
		$cid = $params['cid'];
		$copi = self::$_config[$tag];

		$stat['tag']=$tag;
		$stat['op']='apply_copolit';
		$stat['num']=$num;
		$stat['u']=$uid;
		$stat['tm']=$_SERVER['REQUEST_TIME'];
		$gemt = TT::GemTT();

		if( !$copi ){
			$ret['s'] = 'copinotexist';

			$stat['s']=$ret['s'];
			$gemt->putKeep(null,$stat);
			return $ret;
		}
		$tu = new TTUser( $uid );
		$id = $tu->getoid( 'copilot',TT::OTHER_GROUP );
		$copilot = $tu->getbyid( $id );
		$car_obj = $tu->getbyid( $cid );
		if( !$car_obj ){
			$ret['s'] = 'carnotexsit';
			$stat['s']=$ret['s'];
			$gemt->putKeep(null,$stat);
			return $ret;
		}
		if( $tag != 2006 && $car_obj['copolitTag'] ){
			$ret['s'] = 'repeat';
			return $ret;
		}
		$goods = ItemConfig::getItem( $car_obj['goodsTag'] );
		if( $tag != 2006 ){
			if( $copilot['bag'][$tag] < 1 ){
				$ret['s'] = 'needbuy';
				return $ret;
			}
			$copilot['bag'][$tag] -= 1;
			$car_obj['copolitTag'] = $tag;
		}
		else{
			if( $goods['buytime'] >= 1800 ){
				$car = ItemConfig::getItem( $car_obj['tag'] );
				$add_exp = $goods['exp']*$car['goodsNumber'];//乘以载重箱，经验不包括好友帮助增加的箱数
				if( $add_exp ){
				    $tu->addExp( $add_exp );
				}
			}
			unset( $car_obj['addgoods'] );
			unset( $car_obj['accelerate'] );
			unset( $car_obj['t'] );
			unset( $car_obj['help'] );
			unset( $car_obj['goodsTag'] );
			unset( $car_obj['copolitTag']);
		}
		$copilot['id'] = $id;
		$now = time();
		$tu->puto( $copilot );
		if( $copi['addgoods'] ){
			$car_obj['addgoods'] += $copi['addgoods'];
		}
		if( $copi['accelerate'] && $car_obj['t'] ){
			if( $now - $car_obj['t'] + $copi['accelerate'] > $goods['buytime'] ){
			    $car_obj['t'] = $now - $goods['buytime'];
			}
			else{
			    $car_obj['t'] -= $copi['accelerate'];
			}
		}
		$tu->puto( $car_obj,TT::CAR_GROUP,false );
		$ret['s'] = 'OK';
		$ret['tag'] = $tag;

		$stat['s']=$ret['s'];
		$gemt->putKeep(null,$stat);
		return $ret;
	}	
}
