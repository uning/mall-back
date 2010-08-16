<?php
class Platform
{    
	/**
	 * 赠送礼物，暂只支持赠送已有物品
	 * @param $params
	 *   require  u         -- 送礼人id
	 *            f         -- 受礼人id
	 *            msg       -- 送礼人的留言
	 *   option   xid       -- 礼物id，赠送已有物品时传此参数，
	 *            xtag      -- 新买物品的xml id，赠送新买物品时传此参数
	 * @return 
	 *            s         --  OK
	 */
	    
    public function send_gift ( $params )
    {
        $uid = $params['u'];
        $fid = $params['f'];
        $msg = $params['msg'];
        $xid = $params['xid'];
        $xtag = $params['xtag'];
        if( !$fid ){
            $ret['s'] = 'friendnotexsit';
            return $ret;
        }
        
        if( !$xid ){//赠送已有物品
            if( 0 ){//验证xml中是否存在此类物品
            }
            //验证送礼人是否有此物品
            $tm = new TTMain();//礼物只能是装饰品？
            $gift_obj = $tm->getbyid( $xid,$uid );
            if( !$gift_obj ){
                $ret['s'] = 'giftnotexsit';
                return $ret;
            }
            
            //从送礼人的物品列表中除去此礼物
            $tm->remove( $uid,$gift_obj['id'] );
        }
        
        if( !$xtag ){//赠送新买物品
            if( 0 ){//验证xml中是否存在此类物品
            }
            $tu = new TTUser( $uid );
            $buy_ret = $tu->buyItem( $xtag );
            if( $buy_ret['s'] != 'OK' )
                return $buy_ret;
                //如何得到新买物品的id？
        }
        
        $db = ServerConfig::connect_main_mysql( 0 );
        $present = ModelFactory::Present( $db );

        $now = time();
        $data['user_id'] = $fid;
        $data['donor_id'] = $uid;
        $data['message'] = $msg;
        $data['item_id'] = $gift_obj['tag'];//如果是新买物品 $data['item_id'] = 新物品的tag
        $data['done'] = 0;//未被好友领取
        $data['created_at'] = date( TM_FORMAT,$now );
        $present->insert( $data );
        
        $ret['s'] = 'OK';
        return $ret;
    }
    
    
	/**
	 * 接受礼物赠送
	 * @param $params
	 *   require  u         -- 受礼人id
	 *            f         -- 送礼人id
	 *            tag       -- 礼物种类
	 * @return 
	 *            s         --  OK
	 */
	    
    public function accept_gift ( $params )
    {
        $uid = $params['u'];
        $fid = $params['f'];
        $xid = $params['xid'];
        $msg = $params['msg'];
        if( 0 ){//验证xml中是否存在此物品
        }
        $db = ServerConfig::connect_main_mysql( 0 );
        $present = ModelFactory::Present( $db );
        
        $gift = $present->findByIDs( $uid,$fid,$xid );
        //验证逻辑
        if( !$gift ){
            $ret['s'] = 'notexsit';
            return $ret;
        }
        
        $tm = new TTMain();
        $gift_obj['tag'] = $gift['item_id'];
        $tm->put( $gift_obj,$uid,TT::ITEM_GROUP );
        
        $ret['s'] = 'OK';
        return $ret;
    }    
    
    
	/**
	 * 充值
	 * @param $params
	 *   require  u         -- 玩家id
	 *            oid       -- 订单id
	 * @return 
	 *            s         --  OK
	 */	    
    public function pay_callback ( $params )
    {
        $pid = $params['pid'];
        $uid = $params['id'];
        $oid = $params['oid'];
        if( 0 ){//验证订单是否存在，存在即返回
            $ret['s'] = 'exsit';
            return $ret;
        }
        
        $order_obj = array('order_id'=>$oid
                           ,'is_paid'=>true
                           ,'user_id'=>$uid
                           ,'pid'=>$pid
                           );
        $ret['s'] = 'OK';
        return $ret;
    }
}
