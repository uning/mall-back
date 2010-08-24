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
}
