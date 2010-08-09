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
		$ret['bua'] = $tu->getAll();
		foreach( $ret['bitems'] as $item ){
			$itemids[] = $item['id'];
		}		
		foreach( $ret['bgoods'] as $goods ){
			$goodsids[] = $goods['id'];
		}
		$tu->remove( $aid );
		$tu->remove( $itemids );
		$tu->remove( $goodsids );
		$tu->initAccount();
		$ret['aua'] = $tu->getAll();
		$ret['aitems'] = $tu->get( TT::ITEM_GROUP  );
		$ret['agoods'] = $tu->get( TT::GOODS_GROUP );
		$ret['aadverts'] = $tu->getbyid( $aid );	
		return $ret;
	}    
}
