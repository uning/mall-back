<?php

class CarController
{
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
		//todo:添加购买验证逻辑
		//购买多个支持
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		$ids = array();
		$index = 1;
		foreach( $params['c'] as $row ){
		    $car = ItemConfig::getItem( $row['tag']);
		    if( !$car ){
			    $ret['s'] = 'notexsit';
			    $ret['msg'] = "the $index item in the array";
			    return $ret;
		    }
		    $buy_ret = $tu->buyItem($car['tag']);
		    if( $buy_ret['s'] != 'OK' ){
		        $ret['msg'] = "the $index item in the array";
			    return $buy_ret;
		    }
		    $row['t'] = 0;
		    $ids[] = $tu->puto($row,TT::CAR_GROUP);
		    $index++;
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
		$index = 1;
		$tu = new TTUser( $uid );
		foreach ( $params['d'] as $id ){
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
		    $index++;
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
		$index = 1;
		$ids = array();
        foreach( $params['c'] as $row ){		
		    $car_obj = $tu->getbyid( $row['id']);
		    if( !$car_obj ){
			    $ret['s'] = 'notexist';
			    $ret['index'] = $index;
			    return $ret;
		    }
		    if( $car_obj['t'] != 0 ){
			    $ret['s'] = 'go_goods';
			    $ret['index'] = $index;
			    return $ret;
		    }
		    $tu->puto($row,TT::CAR_GROUP,true);
		    $index++;
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
		if( $car_obj['t'] != 0 ){
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
		$tu->addExp( $add_exp );
		$now = time();
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
        if( $now - $car_obj['t'] < ( 1- 0.01*$car['reduceTime'] )* $goods['buytime'] ){//时间减免改为百分比
			$ret['s'] = 'timeleft';
			return $ret;
		}
		$car_obj['t'] = 0;
		$tu->puto( $car_obj,TT::CAR_GROUP );
        $num = $car['goodsNumber'];
		$goods_data['pos'] = 's';
		$goods_data['tag'] = $goodsTag;
		$ids = array();
		for( $i=0;$i<$num;$i++ ){
			if( isset( $goods_data['id'] ) )
				unset($goods_data['id']);
			$ids[$i]= $tu->puto( $goods_data,TT::GOODS_GROUP );
		}
		$add_exp = $goods['exp']*$num;//乘以载重箱
		$tu->addExp( $add_exp );
		$ret['s'] = 'OK';
		$ret['c'] = $car_obj;
		$ret['g'] = $ids;
		return $ret;
	}

}
