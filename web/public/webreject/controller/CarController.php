<?php

class CarController
{
    static $_config = array( 2001=>array( 'addgoods'=>1,'gem'=>array( 1=>1,10=>9,30=>24,100=>70 ) )
                            ,2002=>array( 'addgoods'=>2,'gem'=>array( 1=>2,10=>18,30=>48,100=>120 ) ) 
                            ,2003=>array( 'accelerate'=>3600,'gem'=>array( 1=>1,10=>9,30=>24,100=>70 ) ) 
                            ,2004=>array( 'accelerate'=>21600,'gem'=>array( 1=>5,10=>40,30=>90,100=>250 ) ) 
                            ,2005=>array( 'recall'=>1,'gem'=>array( 1=>10,10=>80,30=>180,100=>400 ) )
                            ,2006=>1
                            );    
    protected function ischange( $last_level,$cur_level )
    {
        $last = ItemConfig::$_config[$last_level];
        $cur = ItemConfig::$_config[$cur_level];
        if( $last['maxpopu'] != $cur['maxpopu'] || $last['garage'] != $cur['garage'] ||  $last['shopheight'] != $cur['shopheight'] || $last['shopwidth'] != $cur['shopwidth'] ){
            return true;
        }
        return false;
    }
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
		$index = 1;
		foreach( $params['c'] as $row ){
		    $car = ItemConfig::getItem( $row['tag']);
		    if( !$car ){
			    $ret['s'] = 'notexsit';
			    $ret['index'] = $index;
			    return $ret;
		    }
		    $buy_ret = $tu->buyItem($car['tag']);
		    if( $buy_ret['s'] != 'OK' ){
		        $ret['index'] = $index;
			    return $buy_ret;
		    }
//		    $row['t'] = 0;
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
		if( $add_exp ){
		    $last_exp = $tu->getf( TT::EXP_STAT );
		    $cur_exp = $tu->addExp( $add_exp );
		    $last_level = UpgradeConfig::getLevel( $last_exp );
		    $cur_level = UpgradeConfig::getLevel( $cur_exp );
		    if( $cur_level > $last_level ){
		        $bool = self::ischange( $last_level,$cur_level );
		        if( !$bool )
		            $tu->numch( TT::GEM_STAT,1 );
		    }
		}
		$now = time();
		$car_obj['goodsTag'] = $goodsTag;
		$car_obj['t'] = $now;
		$tu->puto( $car_obj,TT::CAR_GROUP );
		$gogoods_count = $tu->numch( 'gogoods_count',1 );
		$ret['s'] = 'OK';
		$ret['c'] = $car_obj;
/*      $ret['c']['id'] = $car_obj['id'];
        $ret['c']['tag'] = $car_obj['tag'];
        $ret['c']['t'] = $car_obj['t'];
        $ret['c']['goodsTag'] = $car_obj['goodsTag'];
*/        
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
/*
		if( !$car_obj['goodsTag'] ){
			$car_obj['goodsTag'] = 10101;// 暂时解决不能取货
			$goodsTag = 10101;
		}
*/
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
		if( $car_obj['recall'] != '1' ){
            if( $now - $car_obj['t'] < ( 1- 0.01*$car['reduceTime'] )* $goods['buytime'] ){//时间减免改为百分比
			    $ret['s'] = 'timeleft';
			    return $ret;
		    }
		}
//		$car_obj['t'] = 0;
        $num = $car['goodsNumber'];
        if( $car_obj['addgoods'] ){
            $num += $car_obj['addgoods'];
        }
//      $car_obj['help'][$uid] =  array('name'=>$mydata['name'],'icon'=>$mydata['icon'],'pid'=>$mydata['pid'],'dbid'=>$uid,'num'=>$num);
        if( $car_obj['help'] ){
            foreach( $car_obj['help'] as $data ){
                $num += $data['num'];
            }
            unset( $car_obj['help'] );
        }
		$goods_data['pos'] = 's';
		$goods_data['tag'] = $goodsTag;
		$ids = array();
		for( $i=0;$i<$num;$i++ ){
			if( isset( $goods_data['id'] ) )
				unset($goods_data['id']);
			$ids[$i]= $tu->puto( $goods_data,TT::GOODS_GROUP );
		}
		unset( $car_obj['t'] );
        unset( $car_obj['addgoods'] );
        unset( $car_obj['recall'] );
        unset( $car_obj['copolitTag'] );
        unset( $car_obj['help'] );
        $tu->puto( $car_obj,TT::CAR_GROUP,false );		
		$add_exp = $goods['exp']*$car['goodsNumber'];//乘以载重箱，经验不包括好友帮助增加的箱数
		if( $add_exp ){
		    $last_exp = $tu->getf( TT::EXP_STAT );
		    $cur_exp = $tu->addExp( $add_exp );
		    $last_level = UpgradeConfig::getLevel( $last_exp );
		    $cur_level = UpgradeConfig::getLevel( $cur_exp );
		    if( $cur_level > $last_level ){
		        $bool = self::ischange( $last_level,$cur_level );
		        if( !$bool )
		            $tu->numch( TT::GEM_STAT,1 );
		    }
		}		
		$ret['s'] = 'OK';
		$ret['c'] = $car_obj;
/*
        $ret['c']['id'] = $car_obj['id'];
        $ret['c']['goodsTag'] = $car_obj['goodsTag'];
*/        
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
	    if( !$copilot ){
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
	    $tu = new TTUser( $uid );
	    $copi = self::$_config[$tag];
	    if( !$copi ){
	        $ret['s'] = 'copinotexsit';
	        return $ret;
	    }
	    $ret['copi'] = $copi;  // for debug
	    $gem = $tu->change( 'gem',0-$copi['gem'][$num] );
	    if( $gem< 0 ){
	        $ret['s'] = 'gem';
	        return $ret;
	    }   
	    $id = $tu->getoid('copilot',TT::OTHER_GROUP );	    
	    $copilot = $tu->getbyid( $id );
	    $copilot['id'] = $id;
	    $copilot['bag'][$tag] += $num;
	    $tu->puto( $copilot );
	    
	    $ret['s'] = 'OK';
	    $ret['tag'] = $tag;
	    $ret['num'] = $num;
//	    $ret['copi'] = $tu->getbyid( $id );//for debug
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
	    if( !$copi ){
	        $ret['s'] = 'copinotexist';
	        return $ret;
	    }
	    $tu = new TTUser( $uid );
            $id = $tu->getoid( 'copilot',TT::OTHER_GROUP );
            $copilot = $tu->getbyid( $id );
	    if( !$copilot ){
	        $ret['s'] = 'copilotnotexsit';
	        return $ret;
	    }
	    $car_obj = $tu->getbyid( $cid );
	    if( !$car_obj ){
	        $ret['s'] = 'carnotexsit';
	        return $ret;
	    }
	    if( $car_obj['copolitTag'] ){
	        $ret['s'] = 'repeat';
	        return $ret;
	    }
	    if( $tag != 2006 ){
		    if( $copilot['bag'][$tag] < 1 ){
		        $ret['s'] = 'needbuy';
			    return $ret;
		    }
		    $copilot['bag'][$tag] -= 1;
		    $car_obj['copolitTag'] = $tag;
	    }
	    else{
	        $goodsTag = $car_obj['goodsTag'];
	        $goods = ItemConfig::getItem( $goodsTag );
	        if( $goods['buytime'] >= 1800 ){
	            $car = ItemConfig::getItem( $car_obj['tag'] );
		        $add_exp = $goods['exp']*$car['goodsNumber'];//乘以载重箱，经验不包括好友帮助增加的箱数
		        if( $add_exp ){
		            $last_exp = $tu->getf( TT::EXP_STAT );
		            $cur_exp = $tu->addExp( $add_exp );
		            $last_level = UpgradeConfig::getLevel( $last_exp );
		            $cur_level = UpgradeConfig::getLevel( $cur_exp );
		            if( $cur_level > $last_level ){
		                $bool = self::ischange( $last_level,$cur_level );
		                if( !$bool )
		                    $tu->numch( TT::GEM_STAT,1 );
		            }
		         }
	        }
	        unset( $car_obj['t'] );
	        unset( $car_obj['help'] );	        
	    }    
	    $copilot['id'] = $id;
	    $tu->puto( $copilot );
	    if( $copi['addgoods'] ){
	        $car_obj['addgoods'] += $copi['addgoods'];
	    }
	    if( $copi['accelerate'] && $car_obj['t'] > 0 ){
	        $car_obj['t'] -= $copi['accelerate'];
	    }
	    if( $copi['recall'] == 1 ){
	        $car_obj['recall'] = 1;
	    }	    
	    $tu->puto( $car_obj,TT::CAR_GROUP,false );
	    $ret['s'] = 'OK';
	    $ret['tag'] = $tag;
//	    $ret['car'] = $tu->getbyid( $cid );  //for debug
//	    $ret['copi'] = $tu->getbyid( $id );  // for debug
	    return $ret;
	}	
}
