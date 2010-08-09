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
		foreach($params['d'] as $index=>$row){
			$tag = $row['tag'];
			$num = 1;
			$item = ItemConfig::getItem($tag);
			if ( !$item ){
				$ret['s'] = 'notexsit';
				$ret['index'] = $index;
				$ret['msg'] = "the $index item in the array";
				return $ret;
			}
			
			$buy_ret = $tu->buyItem( $tag,$num );
			if( $buy_ret['s'] != 'OK' ){
				$buy_ret['msg'] = "the $index item in the array";
				return $buy_ret;
			}	
			if( $item['type'] == "ro" ){
			    $shop_num += $item['gridWidth'];
				$row['id']=$tu->getdid(false,TT::SHOP_GROUP);//shop id
			}
			else{
				$row['id']=$tu->getdid(false,TT::ITEM_GROUP);//other
			}
			$pop += $item['pop'];
			$ret['ids'][$index] = $tu->puto($row,TT::ITEM_GROUP,false); 
		}
		if($shop_num){
			$tu->numch(TT::SHOP_NUM,$shop_num);           
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
	{//todo:根据物品属性,人气增减
		$uid = $params['u'];
		$tu = new TTUser($uid);
		$index = 1;
		$ids = array();
		$pop=0;
		foreach( $params['d'] as $index=> $row ){			
			$item_obj = $tu->getbyid( $row['id'] );
			if( !$item_obj ){
				$ret['s'] = 'notexsit';
				$ret['msg'] = "the $index item in the array";
				return $ret;
			}
			$item = ItemConfig::getItem( $item_obj['tag'] );
			if ( !$item ){
				$ret['s'] = 'notexsit';
				$ret['index'] = $index;
				$ret['msg'] = "the $index item in the array";
				return $ret;
			}
			if($row['pos']!='s'){
			    if( $item['type'] == "ro" )
				    $shop_num += $item['gridWidth'];
				$pop += $item['pop'];
			}else{
			    if( $item['type'] == "ro" )
				    $shop_num -= $item['gridWidth'];
				$pop -= $item['pop'];
			}
			$ids[] = $tu->puto($row);
		}
		if($shop_num){
			$tu->numch(TT::SHOP_NUM,$shop_num);           
		}
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
				$ret['msg'] = "the $index item in the array";
				return $ret;
			}
			$item = ItemConfig::getItem( $item_obj['tag'] );
			if ( !$item ){
				$ret['s'] = 'notexsit';
				$ret['index'] = $index;
				$ret['msg'] = "the $index item in the array";
				return $ret;
			}
			$sale_ret = $tu->saleItem( $item_obj );
			if( $sale_ret['s'] != 'OK' ){
				$sale_ret['msg'] = "the $index item in the array";
				return $sale_ret;
			}
			if( $item_obj['pos']!='s' ){
				$shop_num  -=  $item['gridWidth'];
				$pop       -=  $item['pop'];
			}
//			$tu->remove( $id );
		}
		if($shop_num){
			$tu->numch(TT::SHOP_NUM,$shop_num);           
		}
		if($pop)
			$tu->numch( TT::POPU,$pop);
		$tu->remove( $params['d'] );
		$ret['remain'] = $tu->get( TT::ITEM_GROUP );
		$ret['s'] = 'OK';
		return $ret;
	}
}

