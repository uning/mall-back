<?php
class Copilot
{
    static $_config = array( 1=>array( 'addgoods'=>1,'gem'=>array( 1=>1,10=>9,30=>24,100=>70 ) )
                            ,2=>array( 'addgoods'=>2,'gem'=>array( 1=>2,10=>18,30=>48,100=>120 ) ) 
                            ,3=>array( 'accelerate'=>3600,'gem'=>array( 1=>1,10=>9,30=>24,100=>70 ) ) 
                            ,4=>array( 'accelerate'=>21600,'gem'=>array( 1=>5,10=>40,30=>90,100=>250 ) ) 
                            ,5=>array( 'recall'=>1,'gem'=>array( 1=>10,10=>80,30=>180,100=>400 ) ) 
                            );
	
	/**
	 * 买副驾驶
	 * @param $params
	 * require          u             --   user_id
	 *                  tag           --   副驾驶tag
	 *                  num           --   一次购买数量
	 * @return
	 *                  s             --   OK
	 */
	public function buy( $params )
	{
	    $uid = $params['u'];
	    $tag = $params['tag'];
	    $num = $params['num']; 
	    $tu = new TTUser( $uid );
	    $copi = self::$_config[$tag];
	    if( !$copi ){
	        $ret['s'] = 'notexsit';
	        return $ret;
	    }
	    $ret['copi'] = $copi;  // for debug
	    $gem = $tu->change( 'gem',$copi['gem'][$num] );
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
	    $ret['copi'] = $tu->getbyid( $id );//for debug
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
	 */
	public function apply( $params )
	{
	    $uid = $params['u'];
	    $tag = $params['tag'];
	    $cid = $params['cid'];
	    $copi = self::$_config[$tag];
	    if( !$copi ){
	        $ret['s'] = 'notexist';
	        return $ret;
	    }
	    $tu = new TTUser( $uid );
            $id = $tu->getoid( 'copilot',TT::OTHER_GROUP );
            $copilot = $tu->getbyid( $id );
	    if( !$copilot ){
	        $ret['s'] = 'notexsit';
	        return $ret;
	    }
	    $car_obj = $tu->getbyid( $cid );
	    if( !$car_obj ){
	        $ret['s'] = 'carnotexsit';
	        return $ret;
	    }	    
		if( $copilot['bag'][$tag] < 1 ){
		    $ret['s'] = 'needbuy';
			return $ret;
		}
	    $copilot['bag'][$tag] -= 1;
	    $copilot['id'] = $id;
	    $tu->puto( $copilot );
	    if( $copi['addgoods'] ){
	        $car_obj['addgoods'] += $copi['addgoods'];
	    }
	    if( $copi['accelerate'] && $car_obj['t'] > 0 ){
	        $car_obj['t'] -= $copi['accelerate'];
	    }
	    if( $copi['recall'] == '1' ){
	        $car_obj['recall'] = '1';
	    }
	    $tu->puto( $car_obj,TT::CAR_GROUP );
	    $ret['s'] = 'OK';
	    return $ret;
	}
}
