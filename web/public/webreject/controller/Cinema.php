<?php
class Cinema
{


	/**
	 * 把顾客强行拖进电影院，目前按4分钟进一个计算
	 * @param $params
	 *   require  u               -- 玩家id
	 *            cid             -- cinema id
	 * @return 
	 *            s         --  OK
	 */	
	public function enter( $params )
	{
	    $uid = $params['u'];
	    $cid = $params['cid'];
	    $tu = new TTUser( $uid );
	    $cinema_obj = $tu->getbyid( $cid );
	    $ret['bcinemaobj'] = $cinema_obj;  //for debug
	    $ret['btime'] = date( TM_FORMAT,$cinema_obj['ctime'] );
	    if( !$cinema_obj ){
	        $ret['s'] = 'notexsit';
	        return $ret;
	    }
	    $item = ItemConfig::getItem( $cinema_obj['tag'] );
	    $cinema_obj['ctime'] -= $item['selltime'];
	    $ret['atime'] = date( TM_FORMAT,$cinema_obj['ctime'] );
	    $ret['acinemaobj'] = $cinema_obj;  //for debug
	    $tu->puto( $cinema_obj,TT::CINEMA_GROUP );
	    $ret['s'] = 'OK';
	    return $ret;
	}

	/**
	 * 捡钱并结算
	 * @param $params
	 *   require  u               -- 玩家id
	 *            sid             -- shop id 不仅限于cinema
	 * @return 
	 *            s         --  OK
	 */	
	public function pick( $params )
	{
	    $uid = $params['u'];
	    $sid = $params['sid'];
	    $now = time();
	    $tu = new TTUser( $uid );
	    $shop_obj = $tu->getbyid( $sid );
	    $ret['bshopobj'] = $shop_obj;  //for debug
	    $ret['btime'] = date( TM_FORMAT,$shop_obj['ctime'] );
	    if( !$shop_obj ){
	        $ret['s'] = 'notexist';
	        return $ret;
	    }
	    $item = ItemConfig::getItem( $shop_obj['tag'] );
	    if( !$item ){
	        $ret['s'] = 'itemnotexsit';
	        return $ret;
	    }
	    if( $now-$shop_obj['ctime'] < $item['selltime']*60 ){
	        $ret['s'] = 'time';
	        return $ret;
	    }
	    $tu->numch( TT::MONEY_STAT,$item['sellmoney'] );
	    $shop_obj['ctime'] = $now;//捡钱后可以重新进人
	    $tu->puto( $shop_obj,TT::CINEMA_GROUP );
	    $ret['atime'] = date( TM_FORMAT,$shop_obj['ctime'] );
	    $ret['ashopobj'] = $shop_obj;  //for debug
	    $ret['s'] = 'OK';
	    return $ret;
	}
}