<?php
require_once dirname(__FILE__)."/../../AchieveConfig.php";
class Tool
{
	public function showids( $params )
	{
		$begin_id = 1;
		$ret = array();
		for( $i=$begin_id;$i<150;$i++ ){
			$data['id'] = $i;
			$info = TTGenid::update($data);
			$ret['info'][$i] = $info;
//          CrabTools::myprint($info,RES_DATA_ROOT."/showids.$i");
		}
		return $ret;
	}

	public function genusers( )
	{
	    $ret = array();
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
		$ret['bcars'] = $tu->get( TT::CAR_GROUP );	
		foreach( $ret['bitems'] as $item ){
			$itemids[] = $item['id'];
		}		
		foreach( $ret['bgoods'] as $goods ){
			$goodsids[] = $goods['id'];
		}
		foreach( $ret['bcars'] as $car ){
			$carids[] = $car['id'];
		}		
		$tu->remove( $aid );
		$tu->remove( $itemids );
		$tu->remove( $goodsids );
		$tu->remove( $carids );
		$ret['bua'] = $tu->getf( array( TT::MONEY_STAT,TT::GEM_STAT,TT::EXP_STAT,TT::COMPUTE_PONIT,TT::SHOP_NUM,TT::POPU,TT::GARAGE_STAT,TT::CAPACITY_STAT,TT::TASK_STAT,'lastawardtime','continued') );
//		$data = array( TT::MONEY_STAT,TT::GEM_STAT,TT::EXP_STAT,TT::COMPUTE_PONIT,TT::SHOP_NUM,TT::POPU,TT::GARAGE_STAT,TT::CAPACITY_STAT,TT::TASK_STAT,'lastawardtime','continued');
//		$tu->mputf( $data );
        $tu->numch( TT::MONEY_STAT,0 - $ret['bua']['money'] );
        $tu->numch( TT::GEM_STAT,0 - $ret['bua']['gem'] );
        $tu->numch( TT::EXP_STAT,0 - $ret['bua']['exp'] );
        $tu->numch( TT::COMPUTE_PONIT,0 - $ret['bua']['compute'] );
        $tu->numch( TT::SHOP_NUM,0 - $ret['bua'][TT::SHOP_NUM] );
        $tu->numch( TT::POPU,0 - $ret['bua'][TT::POPU] );
        $tu->numch( TT::GARAGE_STAT,0 - $ret['bua'][TT::GARAGE_STAT] );
        $tu->numch( 'lastawardtime',0 - $ret['bua']['lastawardtime'] );
        $tu->numch( 'continued',0 - $ret['bua']['continued'] );
//        $data = array( TT::CAPACITY_STAT=>"3,2",TT::TASK_STAT=>'1' );
//        $tu->mputf( $data );
		$tu->initAccount();
		$ret['aua'] = $tu->getf( array( TT::MONEY_STAT,TT::GEM_STAT,TT::EXP_STAT,TT::COMPUTE_PONIT,TT::SHOP_NUM,TT::POPU,TT::GARAGE_STAT,TT::CAPACITY_STAT,TT::TASK_STAT,'lastawardtime','continued') );		
		$ret['all'] = $tu->getAll();
		$ret['aitems'] = $tu->get( TT::ITEM_GROUP  );
		$ret['agoods'] = $tu->get( TT::GOODS_GROUP );
		$ret['acras'] = $tu->get( TT::CAR_GROUP );
		$ret['aadverts'] = $tu->getbyid( $aid );	
		return $ret;
	}
	
	public function resetpop( $params )
	{
	    $uid = $params['u'];
	    $tu = new TTUser( $uid );
	    $ret['bshopnum'] = $tu->numch( TT::SHOP_NUM,0 );
	    $ret['ashopnum'] = $tu->numch( TT::SHOP_NUM,0-$ret['bshopnum'] );
	    /*
		$ret['bpop'] = $tu->numch( TT::POPU,0 );
		$ret['apop'] = $tu->numch( TT::POPU,0-$ret['bpop'] );
		*/
	    return $ret;
	}
	
	public function reset_cinema( $params )
	{
	    $uid = $params['u'];
	    $tu = new TTUser( $uid );
	    $items = $tu->get( TT::ITEM_GROUP );
	    if( !$items ){
	        $ret['s'] = 'empty';
	        return $ret;
	    }
	    $rids = array();
	    foreach( $items as $item ){
	        if( $item['tag'] == '60102' ){
	            $rids[] = $item['id'];
	            $id = $tu->getdid( false,TT::CINEMA_GROUP );
	            $new = $item;
	            $new['id'] = $id;
	            $tu->puto($new,TT::ITEM_GROUP,false);
	        }
	    }
	    $tu->remove( $rids );
	    $ret['s'] = 'OK';
	    return $ret;
	}

	public function addgoods( $params )
	{
	    $goods_obj = array();
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		for( $tag = 10101;$tag<10113;$tag++ ){ 
			for( $j=0;$j<5;$j++ ){
				if( isset( $goods_obj['id'] ) ){
					unset( $goods_obj['id']);
				}
    		    $goods = ItemConfig::getItem( $tag );
		    	$goods_obj['num'] = $goods['unitcout'];
	    		$goods_obj['tag'] = $tag;
    			$goods_obj['pos'] = 's';
    			$tu->puto( $goods_obj,TT::GOODS_GROUP );
			}
		}
		$ret['s'] = 'OK';
		return $ret;
	}
	
	public function test_condition( $params )
	{
		$uid = $params['u'];
		$con = $params['con'];
		$tu = new TTUser( $uid );
		$data = array();
		$ret = array();
		foreach ($con as $k=>$v){
			if( $v )
				$data[$k] = $v;
		}
		$tu->mputf( $data );
		foreach( $con as $k=>$v ){
		    $ret['data'][$k] = $tu->getf( $k );
		}
		$ret['s'] = 'OK';
		return $ret;
	}

	public function test_feed( $params )
	{
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		$id = $tu->getoid( 'achieves',TT::OTHER_GROUP );
		$achieves = $tu->getbyid( $id );
		$aparams = $tu->getf( array('popu','invite_count','gogoods_count','total_count','total_sale') );
		$ret['baparams'] = $aparams;
		foreach( AchieveConfig::$_config as $tag=>$conf ){
			$ret['tag'][] = $tag;
			$ret['conf'][] = $conf;
		}

		$data = array( 'total_count'=>50000,'total_sale'=>100000,'gogoods_count'=>3000 );
		$tu->mputf( $data );
		$aparams = $tu->getf( array('popu','invite_count','gogoods_count','total_count','total_sale') );
		$ret['aaparams'] = $aparams;
		$ret['s'] = 'OK';
		return $ret;
	}

    public function test_gen( $params )
    {
        $uid1 = $params['u1'];
        $uid2 = $params['u2'];
        $u1 = TTGenid::getbyid( $uid1 );
        $u2 = TTGenid::getbyid( $uid2 );
        $ret['u1'] = $u1;
        $ret['u2'] = $u2;
        if( $u1['pid'] == $u2['pid'] ){
            $ret['equal'] = 'true';
        }
        else{
            $ret['equal'] = 'false';
        }
        return $ret;
    }
    /**
     * 互相加为好友
     */
    public function add_friends( $params )
    {
        $pids = $params['pids'];
        $ret['pids'] = $pids;
        $apids = explode( ",",$pids );
        $length = count( $apids );
        for( $i=0;$i<$length;$i++ ){
            $ui = TTGenid::getbypid( $apids[$i] );
            $tu = new TTUser( $ui['id'] );
            $ret['bf'][$i] = $tu->getf( TT::FRIEND_STAT );
            $tu->putf( TT::FRIEND_STAT,$pids );
            $ret['af'][$i] = $tu->getf( TT::FRIEND_STAT );
        }
        
/*        
        $pids1 = $params['pids1'];
        $pids2 = $params['pids2'];
$ret['pids1'] = $pids1;
$ret['pids2'] = $pids2; 
        $pid_array1 = explode(",",$pids1);
        $pid_array2 = explode(",",$pids2);
$ret['array1'] = $pid_array1;
$ret['array2'] = $pid_array2;
        foreach( $pid_array1 as $pid1 ){
            $u1 = TTGenid::getbypid( $pid1 );
            $tu1 = new TTUser( $u1['id'] );
        }
        foreach( $pid_array2 as $pid2 ){
            $u2 = TTGenid::getbypid( $pid2 );
            $tu2 = new TTUser( $u2['id'] );
        }
        $ret['bfids1'] = $tu1->getf( TT::FRIEND_STAT );
        $ret['bfids2'] = $tu2->getf( TT::FRIEND_STAT );
//        $tu1->putf( TT::FRIEND_STAT,$pid2 );
//        $tu2->putf( TT::FRIEND_STAT,$pid1 );
        $ret['afids1'] = $tu1->getf( TT::FRIEND_STAT );
        $ret['afids2'] = $tu2->getf( TT::FRIEND_STAT );
*/        
        return $ret;
    }

	/**
	 * 处理仍有货物的商店被放进仓库的坏数据，同时清除店面和商品
	 * @param $params
	 *  require u              --             user id
	 * @return 
	 *          s              --        OK ,or other fail
	 */
    
    public function cleanShopInStoreWithGoods( $params )
    {
        $uid = $params['u'];
        $tu = new TTUser( $uid );
        $goods = $tu->get( TT::GOODS_GROUP );
        $shops = $tu->get( TT::SHOP_GROUP );
        $ret = array();
        foreach ( $shops as $shop ){
            if( $shop['pos'] == 's' ){
                $rgids = array();
                $count = 0;
                foreach( $goods as $good ){
                    if( $good['pos']['y'] == $shop['id'] ){
                        $rgids[] = $good['id'];
                        $count++;
                        $ret['deletegoods'][][] = $good;
                    }
                }
                if( $count ){//如果有货则删除
                    $tu->remove( $shop['id'] );
                }
                $tu->remove( $rgids );
                $ret['deleteshops'][] = $shop;
            }
        }
        $ret['s'] = 'OK';
        return $ret;
    }
    
    public function resetNull( )
    {//解决上架的货物中num字段为空或零的情况
        $ret = array();
        for( $i=1;$i<50000;$i++ ){
            $uid = $i;
            $tu = new TTUser( $uid );
            $goods = $tu->get( TT::GOODS_GROUP );
            if( $goods ){
                foreach( $goods as $index=>$goods_obj ){
                    $ret[$i]['bgoods'][$index] = $goods_obj;    // for debug
                    $item = ItemConfig::getItem( $goods_obj['tag'] );
                    $ret[$i]['item'][$index] = $item;        // for debug
                    if( !$goods_obj['num'] )
                        $goods_obj['num'] = $item['unitcout'];
                    $ret[$i]['agoods'][$index] = $goods_obj;        // for debug
                    
                    // save new goods_obj
                }
            }
        }
        return $ret;
    }
 }
