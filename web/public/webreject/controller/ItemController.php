<?php

class ItemController {
	/**
	 * @param $params
	 *  require             u          -- user id
	 *                      d          -- item        支持购买多种物品
	 *                                 tag            --  tag to distiguish item type
	 *                                 pos            --  position for describe item 
	 * @return 
	 *                      s          -- OK ,or other fail
	 *                                     notexsit ,配置不一致
	 *                                     notbuy ,不能购买的物品
	 *                                     level,等级未到 
	 *                                     achive， 所需成就buy_need_achiveid 未达成 
	 *                                     task,所需任务未达成
	 *                                     buynumlimit,限量版已经卖完
	 *                                     money，
	 *				       gem
	 *			index     -- 第几个物品开始后面的物品没有购买成功
	 *
	 *                      ids        -- the new generate id
	 */
	public function buy($params)
	{
		$uid = $params['u'];
		$ids=array();
		$tu = new TTUser($uid);
		$pop = 0 ;
		$shop_num  = 0;
		$now = time();
		foreach($params['d'] as $index=>$row){
			$tag = $row['tag'];
			$num = 1;
			$item = ItemConfig::getItem($tag);
			if ( !$item ){
				$ret['s'] = 'notexsit';
				$ret['index'] = $index;
				return $ret;
			}
			
			$buy_ret = $tu->buyItem( $tag,$num );
			if( $buy_ret['s'] != 'OK' ){
				$buy_ret['index'] = $index;
				return $buy_ret;
			}	
			if( $item['type'] == "ro" ){
//			    $shop_num += $item['gridWidth'];
				$row['id'] = $tu->getdid( false,TT::SHOP_GROUP );//shop id
			}
			elseif( $row['tag'] == '60102' ){//电影院买后立即可播放电影
			    $row['id'] = $tu->getdid( false,TT::CINEMA_GROUP );
			    $row['ctime'] = $now;
			}
			else{//不维护店面人气，但厕所的人气需包含
				$row['id'] = $tu->getdid( false,TT::ITEM_GROUP );//other
				$pop += $item['pop'];
			}
			$ret['ids'][$index] = $tu->puto($row,TT::ITEM_GROUP,false); 
		}
		if($pop)
			$tu->numch( TT::POPU,$pop);
		$ret['s'] = 'OK';
		return $ret;
	}


	/**
	 * 移动
	 * @param $params
	 *  require u              --             user id
	 *          d              --             item数组
	 *                         id             --        type id(类型id)
	 *                         pos            --        position information of items
	 * @return 
	 *                         s              --        OK ,or other fail
	 *                                     notexsit ,不存在
	 *				      gem
	 */
	public function move($params)
	{
		$uid = $params['u'];
		$tu = new TTUser($uid);
		$index = 1;
		$ids = array();
		$pop=0;
		$now = time();
		foreach( $params['d'] as $index=> $row ){			
			$item_obj = $tu->getbyid( $row['id'] );
			if( !$item_obj ){
				$ret['s'] = 'notexsit';
				$ret['index'] = $index;
				return $ret;
			}
			$item = ItemConfig::getItem( $item_obj['tag'] );
			if ( !$item ){
				$ret['s'] = 'notexsit';
				$ret['index'] = $index;
				return $ret;
			}
			if( $item['type'] == 'ro' && $row['pos']=='s'){
				//todo $tu 结算	
				require_once 'GoodsController.php';
				GoodsController::checkout($params);
			}
			if( $item['type'] != 'ro' ){//改为不计算店面的人气
			    if( $item['tag'] == '60102'){//移动电影院后，结算时间应当
			        $row['ctime'] = $now;
			    }
				if( $row['pos'] != 's' && $item_obj['pds'] == 's' ){
					$pop += $item['pop'];
				}
				else if( $row['pos'] == 's' && $item_obj['pds'] != 's' ){
					$pop -= $item['pop'];
				}
			}
			/*
			else{//对货物尚未卖完的店面进行移动时要先单个结算，确定货物队列为空时才能移动
			    if( $item_obj['goods'] ){
			        if( $shop_ret['s'] == 'notempty' ){
			            $ret['s'] = 'notempty';
			            $ret['index'] = $index;
			           // return $ret;
			        }
			    }
			}*/
		   foreach($row as $k=>$v)
                       $item_obj[$k]=$v;
		    $ret[$row['id']]=$item;
		    $tu->puto($item_obj,'',false);//reduce a get op 
		}
		/*
		if($shop_num){
			$tu->numch(TT::SHOP_NUM,$shop_num);           
		}
		*/
		if($pop)
			$tu->numch( TT::POPU,$pop);
		$ret['s'] = 'OK';
		return $ret;
	}

	/**
	 * 卖出
	 * @param $params
	 *  require u  -- user id
	 *  require d  -- item_id 数组
	 *     
	 * @return 
	 *  s   -- OK ,or other fail
	 *                                     notexsit ,不存在配置
	 *                                     notsale ,不能购买的物品
	 *                                     level,等级未到 
	 *                                     achive， 所需成就buy_need_achiveid 未达成 
	 *                                     task,所需任务未达成
	 *                                     buynumlimit,限量版已经卖完
	 *                                     money，
	 *				      gem
	 */
	public function sale($params)
	{
		$uid = $params['u'];
		$tu = new TTUser( $uid );

		foreach( $params['d'] as $index=> $id ){
			$item_obj = $tu->getbyid( $id);
			if( !$item_obj ){
				$ret['s'] =  'notexsit';
				$ret['index'] = $index;
				return $ret;
			}
			$item = ItemConfig::getItem( $item_obj['tag'] );
			if ( !$item ){
				$ret['s'] = 'notexsit';
				$ret['index'] = $index;
				return $ret;
			}
			$sale_ret = $tu->saleItem( $item_obj );
			if( $sale_ret['s'] != 'OK' ){
				$sale_ret['index'] = $index;
				return $sale_ret;
			}
			if( $item_obj['pos']!='s' ){
//				$shop_num  -=  $item['gridWidth'];
				$pop       -=  $item['pop'];
			}
//			$tu->remove( $id );
		}
		/*
		if($shop_num){
			$tu->numch(TT::SHOP_NUM,$shop_num);           
		}
		*/
		if($pop)
			$tu->numch( TT::POPU,$pop);
		$tu->remove( $params['d'] );
//		$ret['remain'] = $tu->get( TT::ITEM_GROUP ); for debug
		$ret['s'] = 'OK';
		return $ret;
	}
}

