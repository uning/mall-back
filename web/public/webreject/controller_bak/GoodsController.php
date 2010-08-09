<?php
class GoodsController extends BaseController
{
	/**
	 * 直接购买物品到商店
	 *   
	 * @param $params
	 *  require   u     -- uid
	 *  require d       -- item data map （多个时为数组）
	 * @return 
	 *    s     --OK
	 */
	public function buy( $params )
	{
		$uid = $params['u'];
		$ug = ModelFactory::UserGood();
		if( is_array( $params['d'] ) ){
		    $ug->putmulti_super($params['d'],$uid);
		}
		else{
		    $ug->put_super($params['d'],$uid);
		}
        $ret['s'] = 'ok';
		return $ret;
	}	
	
	/**
	 * 上架
	 *   
	 * @param $params
	 *    require   u      -- uid
	 *    require d        -- item data map （多个时为数组）
	 * @return 
	 *    s     --OK
	 */
	public function exhibit_goods( $params )
	{
		$uid = $params['u'];
		$ug = ModelFactory::UserGood();
		if( is_array( $params['d'] ) ){
		    $ug->putmulti_super($params['d'],$uid);
		}
		else{
		    $ug->put_super($params['d'],$uid);
		}
        $ret['s'] = 'ok';
		return $ret;
	}
	
	/**
	 * 结算收入
	 *   
	 * @param $params
	 *    require   u      -- uid
	 * @return 

	 *    s   -- ok
	 */	
	public function checkout( $params )
	{
	    $uid = $params['u'];
	    $db = ServerConfig::getdb_by_userid( $uid );
	    $ug = ModelFactory::UserGood();
	    $ua = ModelFactory::UserAccount($db);
	    $ret['s'] = 'ok';
	    return $ret;
	}

}