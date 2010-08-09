<?php
class Tool
{
	public function showids( $params )
	{
		$begin_id = 1;
		for( $i=$begin_id;$i<150;$i++ ){
			$data['id'] = $i;
			$info = TTGenid::update($data);
			$ret['info'][$i] = $info;
                        CrabTools::myprint($info,RES_DATA_ROOT."/showids.$i");
		}
		return $ret;
	}

	public function genusers( )
	{
		for( $i=1;$i<10;$i++){
			$user['pid'] = "quest0$i";
			$user['name'] = "crab$i";
            $user['icon'] = "http://hdn.xnimg.cn/photos/hdn311/20090521/1025/tiny_9b7s_40842b204236.jpg";
			$ret[$i]['id'] = TTGenid::genid($user);
		}
		return $ret;
	}

	/**
	 * 清数据，输入  uid   u
	 */

	public function clean( $params )
	{
		$uid = $params['u'];
		$tu = new TTUser($uid);
		$ret['bitems'] = $tu->get( TT::ITEM_GROUP );
		$ret['bgoods'] = $tu->get( TT::GOODS_GROUP );
		$aid = $tu->getoid( 'advert',TT::OTHER_GROUP );
		$ret['aid'] = $aid;
		$ret['badverts'] = $tu->getbyid( $aid );	
		foreach( $ret['bitems'] as $item ){
			$itemids[] = $item['id'];
		}		
		foreach( $ret['bgoods'] as $goods ){
			$goodsids[] = $goods['id'];
		}
		$tu->remove( $aid );
		$tu->remove( $itemids );
		$tu->remove( $goodsids );
		$ret['aua'] = $tu->getAll();
		$ret['aitems'] = $tu->get( TT::ITEM_GROUP  );
		$ret['agoods'] = $tu->get( TT::GOODS_GROUP );
		$ret['aadverts'] = $tu->getbyid( $aid );	
		return $ret;
	}
	
	public function all ( $params )
	{
		$uid = $params['u'];
		$tu = new TTUser($uid);
		/*
		$ret['bitems'] = $tu->get( TT::ITEM_GROUP );
		$ret['bgoods'] = $tu->get( TT::GOODS_GROUP );
		$ret['bcars'] = $tu->get( TT::CAR_GROUP );
		*/
		$ret['bua'] = $tu->getf( array( TT::MONEY_STAT,TT::GEM_STAT,TT::EXP_STAT,TT::COMPUTE_PONIT,TT::SHOP_NUM,TT::POPU,TT::GARAGE_STAT,TT::CAPACITY_STAT,TT::TASK_STAT,'lastawardtime','continued') );
//		$data = array( TT::MONEY_STAT,TT::GEM_STAT,TT::EXP_STAT,TT::COMPUTE_PONIT,TT::SHOP_NUM,TT::POPU,TT::GARAGE_STAT,TT::CAPACITY_STAT,TT::TASK_STAT,'lastawardtime','continued');
//		$tu->mputf( $data );
        $tu->numch( TT::MONEY_STAT,10000 - $ret['bua']['money'] );
        $tu->numch( TT::GEM_STAT,1 - $ret['bua']['gem'] );
        $tu->numch( TT::EXP_STAT,0 - $ret['bua']['money'] );
        $tu->numch( TT::COMPUTE_PONIT,0 - $ret['bua']['compute'] );
        $tu->numch( TT::SHOP_NUM,2 - $ret['bua'][TT::SHOP_NUM] );
        $tu->numch( TT::POPU,30 - $ret['bua'][TT::POPU] );
        $tu->numch( TT::GARAGE_STAT,1 - $ret['bua'][TT::GARAGE_STAT] );
        $tu->numch( 'lastawardtime',10000 - $ret['bua']['lastawardtime'] );
        $tu->numch( 'continued',10000 - $ret['bua']['continued'] );
        $data = array( TT::CAPACITY_STAT=>"3,2",TT::TASK_STAT=>'1' );
        $tu->mpuf( $data );
		$ret['aua'] = $tu->getf( array( TT::MONEY_STAT,TT::GEM_STAT,TT::EXP_STAT,TT::COMPUTE_PONIT,TT::SHOP_NUM,TT::POPU,TT::GARAGE_STAT,TT::CAPACITY_STAT,TT::TASK_STAT,'lastawardtime','continued') );
		$aid = $tu->getoid( 'advert',TT::OTHER_GROUP );
		$ret['aid'] = $aid;
		$ret['badverts'] = $tu->getbyid( $aid );	
		foreach( $ret['bitems'] as $item ){
			$itemids[] = $item['id'];
		}		
		foreach( $ret['bgoods'] as $goods ){
			$goodsids[] = $goods['id'];
		}
		foreach( $ret['bcars'] as $car ){
			$carids[] = $car['id'];
		}
//		$ret['ball'] = $tu->getAll();
		return $ret;
	}
}
