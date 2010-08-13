<?php	
	/**
	 * 单个店结算卖货
	 * @param $params
	 *            u           - userid
                  sids        - shop ids
	 * @return 
	 *   s  - OK,noneed(短期内没有需要结算的商品),busy(太快)
	 *   income  - 获得金币
	 *   money   - 总金币
	 *   selloutids - 卖完删除的id
	 *   goods   --数量有辩护的商品列表
	 */
class Bak
{
	public function checkshop( $params )
	{
	    $uid = $params['u'];
	    $sid = $params['sid'];
	    $tu = new TTUser( $uid );
	    $shop_obj = $tu->getbyid( $sid );
	    if( !$shop_obj ){
	        $ret['s'] = 'notexsit';
	        return $ret;
	    }
	    $goods_list = $shop_obj['goods'];
	    $condata = array();
	    if( !$goods_list ){
	        $ret['s'] = 'nogoods';
	        return $ret;
	    }
		foreach( $goods_list as $goods_id=>$stime ){
	        $goods_obj = $tu->getbyid( $goods_id );
	        $condata[$stime] = $goods_obj;
	    }
		$ret['condata'] = $condata;
		$popu = $params[TT::POPU];
		$ret['bpopu'] = $popu;
		$ua = UpgradeConfig::getUpgradeNeed( $params['exp'] );
		$ret['ua'] = $ua;	    
//算人气
		$shops = $tu->get( TT::SHOP_GROUP );
		foreach( $shops as $shop ){
			$ret['shop_num_shop'][] = $shop;
			if( $shop['pos'] != 's' ){
			    $item = ItemConfig::getItem( $shop['tag'] );
			    $shop_num += $item['gridWidth'];
			}
		}		
		$ret['ashopnum'] = $shop_num;
		if( !$shop_num ){
			$ret['s'] = 'noshopexist';
			return $ret;
		}		
		$shop_popu = $shop_num*15;//只算店面人气
		$popu += $shop_popu;
		if( $popu > $ua['maxpopu'] ){
			$popu = $ua['maxpopu'];
		}	
		$ret['apopu'] = $popu;
	    
		$aid = $tu->getoid( 'advert',TT::OTHER_GROUP );
		$adv = $tu->getbyid( $aid );
		$used_advert = $adv['use'];
		//		$ret['bbbbbadvert'] = $adv;
		$computetime = $params[TT::COMPUTE_PONIT];
		//		$ret['now'] = date( TM_FORMAT,$now );
		//		$ret['lastcomputetime'] = date( TM_FORMAT,$computetime );	    
		$selloutids = array();
		$income = 0;
		$special = 0; //特殊商店的收入
		$sale_count = 0; //销售份数
		$now = time();
		$sconfig = ItemConfig::getItem( $shop_obj['tag'] );
			//			$ret['sconfig'][$s] = $sconfig;
		ksort($condata);
		$curtime = 0;//可以售卖新商品时间
		$cgoods = array();
		foreach( $condata as $t=>$g ){
			$gconfig = ItemConfig::getItem($g['tag']);
			$ctime = $g['ctime'];//上次结算时间
			if($curtime < $t)
				$curtime = $t; //上架时间
			if( $curtime< $ctime )
				$curtime = $ctime;
			$g['ctime'] = $now;
			$ret['tloop'][$t] = date( TM_FORMAT,$curtime );
			$gaps = array();
			if( $used_advert ){
				$tmp = self::getTimeRates( $tu,$gaps,$used_advert,$curtime,$popu,$ua['maxpopu'],$now,$shop_num );
//			            $ret['advertisement'][$s][$t] = $tmp;
			}
			else{
				$gaps = array( array( $now-$curtime,$popu/( $shop_num*15 ) ));
			}					
			$ret['gaps'][$t] = $gaps;
//			foreach($gaps as $gr){
			foreach( $gaps as $k=>$gr ){//测试信息需要该索引值
				$stime = $gr[0];
				if( $sconfig['gridWidth'] )					
					$pertime = $gconfig['selltime']/( $sconfig['gridWidth'] * $gr[1] );
				if( $pertime )
					$snum = floor( $stime/$pertime );
				$ret['pertime'][$t][$k] = $pertime;
				if($snum >= $g['num']){//卖完了
					$asnum = $g['num'];
				}
				else{
					$asnum = $snum;
				}
//				$ret['asnum'][$s][$t][$k][$g['tag'] ] = $asnum;
				$ret['sell'][$g['tag']] += $asnum;
				$sale_count += $asnum;//记录销售份数，成就用
				$income += $asnum* $gconfig['sellmoney'];  //sellmoney是单份物品的卖价
				$g['num'] -= $asnum;
//				$g['scount'] += $asnum;
				if( $g['num']==0 ){//当前时间段卖光此箱货物，继续卖下一个货物
					$cgoods[]=$g;
					$selloutids[] = $g['id'];
					unset( $shop_obj['goods'][$g['id']] );
					break;//跳出时间段循环，继续卖同一商店下一个上架时间的货物（在同一商店，同一时间上架但售卖顺序不同的货物，已在上架时微调成不同上架时间）
				}
			}
			$tu->puto( $shop_obj,TT::SHOP_GROUP );
			if( $g['num']!= 0 ){
				$tu->puto( $g,TT::GOODS_GROUP );//未卖完的商品需要保存回库，继续卖同一商店下一个上架时间的货物（在同一商店，一时间上架但售卖顺序不同的货物，已在上架时微调成不同上架时间）
				break;//跳出上架时间循环，但是继续店铺循环，终止同一店铺的货物队列中其他货物的结算
			}
		}
//删除使用过的广告队列
		unset( $adv['use'] );
		if( $used_advert ){//如果是空数组
			$adv['use'] = $used_advert;
		}
		$adv['id'] = $aid;
		$tu->puto( $adv,TT::ADVERT_GROUP,false );
		//总销售份数
		$now_sale_count = $tu->numch( 'total_count',$sale_count );
		//总销售额
		$now_total_sale = $tu->numch( 'total_sale',$income );
		$ret['s'] = 'OK';
		$ret['income'] = $income;
		$ret['money']  = $tu->numch('money',$income);
		$ret['t'] = $now;
		$tu->remove( $selloutids );
		$tu->putf( TT::COMPUTE_PONIT,$now );
		return $ret;
	}
}