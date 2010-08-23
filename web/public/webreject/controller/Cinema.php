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
//	    $ret['bcinemaobj'] = $cinema_obj;  //for debug
//	    $ret['btime'] = date( TM_FORMAT,$cinema_obj['ctime'] );
	    if( !$cinema_obj ){
	        $ret['s'] = 'notexsit';
	        return $ret;
	    }
	    $item = ItemConfig::getItem( $cinema_obj['tag'] );
	    $cinema_obj['ctime'] -= $item['selltime'];
//	    $ret['atime'] = date( TM_FORMAT,$cinema_obj['ctime'] );
//	    $ret['acinemaobj'] = $cinema_obj;  //for debug
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
//	    $ret['bshopobj'] = $shop_obj;  //for debug
//	    $ret['btime'] = date( TM_FORMAT,$shop_obj['ctime'] );
	    if( !$shop_obj ){
	        $ret['s'] = 'notexist';
	        return $ret;
	    }
	    $item = ItemConfig::getItem( $shop_obj['tag'] );
	    if( !$item ){
	        $ret['s'] = 'itemnotexsit';
	        return $ret;
	    }
	    if( $shop_obj['tag'] == '60102'){//电影院
	        if( $now - $shop_obj['ctime'] < $item['selltime']*60 ){//坐满30个才开映，再过2小时才放映结束
	            $ret['s'] = 'time';
	            return $ret;
	        }
	        $money = $item['sellmoney'];
	    }
	    elseif( $shop_obj['tag'] == '60103' || $shop_obj['tag'] == '60104' ){//健身房和按摩店
	        if( $now - $shop_obj['ctime'] < $item['selltime'] ){//开业时间需满足一定条件才可以收钱
	            $ret['s'] = 'time';
	            return $ret;	            
	        }
	        $money = $item['sellmoney'];
	    }
		elseif( $shop_obj['tag'] == '60105' ||  $shop_obj['tag'] == '60106' ){//上岛和7-11便利店
		    if( $now - $shop_obj['ctime'] < 3600 ){//开业1小时后就可以收钱，但最多只能积累6~9小时
	            $ret['s'] = 'time';
	            return $ret;
		    }
		    $gap = $now - $shop_obj['ctime'];
		    if( $gap > $item['selltime'] ){
		        $gap = $item['selltime'];
		    }
		    $money = $gap * $item['sellmoney'] / 3600;
	    }    	    	    
	    $tu->numch( TT::MONEY_STAT,$money );
	    $shop_obj['ctime'] = $now;//捡钱后可以重新进人
	    $tu->puto( $shop_obj,TT::CINEMA_GROUP );
//	    $ret['atime'] = date( TM_FORMAT,$shop_obj['ctime'] );
//	    $ret['ashopobj'] = $shop_obj;  //for debug
	    $ret['s'] = 'OK';
	    return $ret;
	}
}