<?php
class GoodsController extends BaseController
{
	/**
	 * 直接购买物品到商店　——仅供测试用
	 *   
	 * @param $params
	 *  require   u     -- uid
	 *  require   d     -- item data map （多个时为数组）
	 *                  num     -- 每箱物品个数
	 *                  tag     -- 
	 *                  pos     --
	 *                  stime   --
	 *  require   sid   -- 商店id
	 * @return 
	 *  s     --OK
	 *  
	 */
	public function buy( $params )
	{
		$uid = $params['u'];
        $now = time();
		//$num = 1;//$item['unitcout'];
		$tu = new TTUser($uid);
		$ids = array();
		foreach ( $params['d'] as $goods){
            $ret['goods'] = $goods;
			$item = ItemConfig::getItem($goods['tag']);
			if( !$item ){
				//写入日志
				continue;
			}
			if( isset( $goods['id'] ) ){
			    unset( $goods['id']);
			}
			$shop = $tu->getbyid( $goods['pos']['y'] );
			$goods['stag'] = $shop['tag']; 
			$goods['stime'] = $now + $goods['pos']['x'];
			$goods['num'] = $item['unitcout'];
			$ids[] = $tu->puto($goods,TT::GOODS_GROUP);
		}
		$ret['s'] = 'OK';
		$ret['ids'] = $ids;
		return $ret;
	}

	/* @param $params
	 *  require   u     -- uid
	 *  require   d     -- item id array
	 * @return 
	 *  s     --OK
	 */	
	public function remove( $params )
	{
	    $uid = $params['u'];
	     $tu = new TTUser($uid);
	    $index = 1;
	    foreach( $params['d'] as $row ){
	        $tu->remove($row['id']);
	    }
	    $ret['s'] = 'OK';
	    return $ret;
	}
	
	/**
	 * 上架,在shop记录里记录商品id
	 * @param $params
	 *    require   u        -- uid
	 *    require   d        -- item data map （多个时为数组）
	 *                       id   -- the ids of goods
	 * @return 
	 *    s     --OK
	 *    s     --nogood ,商品不存在
	 *    s     --noshop ,商品不存在
                      max,已经放满
	 */
	public function exhibit_goods( $params )
	{
		$uid = $params['u']; 
		$tu = new TTUser($uid);
		$shops =array();
		$shopconfs =array();
		$now = time();
		foreach ( $params['d'] as $index => $goods ){
			$sid =  $goods['pos']['y'];
			$shop_obj = & $shops[$sid]; 
            if(!$shop_obj)
                $shop_obj = $tu->getbyid( $sid );
			if( !$shop_obj ){
				$ret['s'] = 'noshop';
				$ret['msg'] = "the $index item in the array";
				return $ret;
			}
			$sconfig = & $shopconfs[$shop_obj['tag'] ];
			if(!$sconfig)
				$sconfig =  ItemConfig::getItem( $shop_obj['tag'] ); 
			if( !$sconfig ){
				$ret['s'] = 'noshop';
				$ret['msg'] = " $index config not find invalid data";
				return $ret;
			}
			$goods_obj = $tu->getbyid( $goods['id'] );
			if( !$goods_obj ){
			    $ret['s'] = 'goodsnotexsit';
			    return $ret;
			}
			$item = ItemConfig::getItem( $goods_obj['tag'] );
			//if( !$item ){
			//	$ret['s'] = 'goodsnotexsit '.$goods['tag'];
			//	return $ret;
			//}
			$cnt= count($shop_obj['goods']);
			$maxcnt = $item['backnum'] + 1; //todo : get backgoods num of shop
			if($cnt > $maxcnt){
				//$ret['s'] = 'max';
				//return $ret;
			}
//			$shop_obj['goods'][$goods['id']]=$now+$index;  //没必要
//			$goods['stime'] =  $now + $index;
            $goods['stime'] =  $now + $goods['pos']['x']; //对同一商店同一时间上架的货物，按出售顺序将上架时间轻微调整以方便处理
			$goods['num'] =  $item['unitcout'];//todo:read the unit count
			$goods['stag']  =  $shop_obj['tag'];//商店类型
			$tu->puto($goods,GOODS_GROUP);
//			$ret['goods'][] = $goods;
//			$ret['item'][] = $item;
		}
		foreach($shops as $s){
			$tu->puto($s);
		}
		$ret['s'] = 'OK';
		return $ret;
	}


	/**
	 * 返回
	 * array(时间长度，卖出速率)
	 * @param $param
	 * @param $shop_idle_time
	 * @param $g
	 * @return unknown_type
	 */
	protected function getTimeRates( &$tu,&$gaps,&$used,$computetime,$popu,$max_popu,$now,$shop_num )
	{//如果已使用广告队列不为空，按宣传时间排序,结算完后删除广告队列
		//		$ret['bused'] = $used;
		$adlist = array();
		$ret = array();
		$length = 0;
		foreach( $used as $time=>$tag ){
			$adlist[$length]['time'] = $time;
			$adlist[$length]['tag'] = $tag;
			$length++;
		}
		//		$ret['adlist'] = $adlist;
		$endpoint = $computetime;
		//		$ret['endpoint'] = $endpoint;
		for( $i=0;$i<$length;$i++ ){
			$adv = AdvertConfig::getAdvert( $adlist[$i]['tag'] );
			//			$ret['advinfor'][$i] = $adv;
			$add_advpopu = $popu + $adv['popularity'];			
			$max_addadvpopu = $max_popu + $adv['maxpopular'];
			if( $add_advpopu > $max_addadvpopu ){
				$add_advpopu = $max_addadvpopu;
			}
			//			$ret['max_addadvpopu'][$i] = $max_addadvpopu;
			//			$ret['add_advpop'][$i] = $add_advpopu; 
			//			$ret['max_popu'][$i] = $max_popu;
			//第一段宣传需要单独处理(之前未单独处理，当只有一个广告且宣传时间小于结算时间时，直接被当成最后一段宣传处理，导致瞬间卖光货物)
			if( $adlist[$i]['time'] < $endpoint ){//只会在第一个宣传段出现，其余都是　$adlist[$i]['time'] = $endpoint	    
				if( $length == 1 ){//仅有一段宣传时间，单独处理后跳出循环
					if( $now - $adlist[$i]['time'] >= $adv['allTime'] ){//自然失效 且可能之后有间断
						$gaps[] = array( $adv['allTime'] -( $endpoint - $adlist[$i]['time'] ),$add_advpopu/( $shop_num*15 ) );
						$gaps[] = array( $now - ( $adlist[$i]['time'] + $adv['allTime'] ),$popu/( $shop_num*15 ) );
					}
					else{//返回未失效的部分
						$gaps[] = array( $now - $endpoint,$add_advpopu/( $shop_num*15 ) );
						$used = array( $adlist[$i]['time']=>$adlist[$i]['tag'] );
						//   		                $ret['1use'] = $used;
					} 			       
					break;
				}
				else{//之后还有宣传段，处理第一个宣传时间段
					if( $adlist[$i+1]['time'] - $adlist[$i]['time'] >= $adv['allTime'] ){//自然失效 且可能之后有间断
						$gaps[] = array( $adv['allTime'] -( $endpoint - $adlist[$i]['time'] ),$add_advpopu/( $shop_num*15 ) );
						$gaps[] = array( $adlist[$i+1]['time'] - ( $adlist[$i]['time'] + $adv['allTime'] ),$popu/( $shop_num*15 ) );
					}
					else{//替换失效
						$gaps[] = array( $adlist[$i+1]['time'] - $endpoint,$add_advpopu/( $shop_num*15 ) );
					}
					continue;
				}
			}
			//若只在登录时调用，则不可能出现第一段宣传的起始时间大于结算时间的情况
			/*
			   if( $adlist[$i]['time'] > $endpoint ){//第一个宣传前有间断
			   $gaps[] = array( $adlist[$i]['time']-$endpoint,1 );
			   $endpoint = $adlist[$i]['time'];
			   }
			 */
			if( $i<$length-1){//避免越界，逻辑上也需要单独处理最后一段宣传
				if( $adlist[$i+1]['time'] - $adlist[$i]['time'] >= $adv['allTime'] ){//自然失效
					$gaps[] = array( $adv['allTime'],$add_advpopu/( $shop_num*15 ) );
					$endpoint += $adv['allTime'];
					if( $endpoint < $adlist[$i+1]['time'] ){//自然失效后，离下一段宣传开始前有段无宣传时间
						$gaps[] = array( $adlist[$i+1]['time'] - $endpoint,$popu/( $shop_num*15 ) );
						$endpoint = $adlist[$i+1];
					}
				}
				else{//非自然失效，替换失效，中间不存在无宣传时间段
					$gaps[] = array( $adlist[$i+1]['time'] - $adlist[$i]['time'],$add_advpopu/( $shop_num*15 ) );
					$endpoint = $adlist[$i+1]['time'];
				}
			}
			else{//单独处理最后一段宣传
				if( $now - $adlist[$i]['time'] >= $adv['allTime'] ){//自然失效
					$gaps[] = array( $adv['allTime'],$add_advpopu/( $shop_num*15 ) );
					$gaps[] = array( $now - $adlist[$i]['time'] - $adv['allTime'],$popu/( $shop_num*15 ) );
				}
				else{//最后一段广告不存在替换失效，返回未失效的部分
					$gaps[] = array( $now - $adlist[$i]['time'],$add_advpopu/( $shop_num*15 ) );
					$used = array( $adlist[$i]['time']=>$adlist[$i]['tag'] );
					//					$ret['mused'] = $used;
				}
			}
		}
		//删除已使用广告队列
		return $ret;
	}
	/**
	 * 结算收益
	 * 取出每个店面所有上架商品
	 * 按上架时间排序，售卖
	 * @return 
	 */
	protected function compute( &$tu )
	{	    
		$now = time();
		//		$ret['now'] = $now;
		//$min_gap = 120;
		$min_gap = 0;
		//获取人气和宣传值
		$params = $tu->getf( array(TT::POPU,TT::COMPUTE_PONIT,TT::SHOP_NUM,TT::EXP_STAT) );
		$ret['params'] = $params;

		$status = $tu->getf( array(TT::MONEY_STAT,TT::GEM_STAT) );
		if( !$params['shop_num'] ){
			$ret['s'] = 'noshopexist';
			return $ret;
		}
		$goods = $tu->get( TT::GOODS_GROUP );
		//		$ret['goods'] = $goods;
		$shopids = array();
		//按时间排序
		$condata = array();
		foreach( $goods as $row ){
			if( $row['pos'] != 's' ){//除去未上架的货物
				$sid = $row['pos']['y']; //shop 
				//$order = $good['pos']['x']; //售卖顺序
				if(!$sid )
					continue ;
				$stime = $row['stime']; //上架时间 
				$stag = $row['stag']; //商店类型
				$shopids[$sid] = $stag;
				$condata[$sid][$stime] = $row; //for unique time index
			}
		}
		//		$ret['shopids'] = $shopids;
		if(!$condata){
			$ret['s']='nogoods';
			return $ret;
		}
		$ret['condata'] = $condata;
		$popu = $params[TT::POPU];
		$ret['bpopu'] = $popu;
		$ua = UpgradeConfig::getUpgradeNeed( $params['exp'] );
		//		$ret['ua'] = $ua;
		$shop_num = $params['shop_num'];
		$ret['bshopnum'] = $shop_num;
		/*
		if( !$shop_num ){//处理店面格数为零的异常情况
			$shops = $tu->get( TT::SHOP_GROUP );
			foreach( $shops as $shop ){
				$ret['shop_num_shop'][] = $shop;
				$item = ItemConfig::getItem( $shop['tag'] );
				$shop_num += $item['gridWidth'];
			}
		}
		*/
		$shops = $tu->get( TT::SHOP_GROUP );
		foreach( $shops as $shop ){
		    $shop_num = 0;
			$ret['shop_num_shop'][] = $shop;
			$item = ItemConfig::getItem( $shop['tag'] );
			$shop_num += $item['gridWidth'];
		}		
		$ret['ashopnum'] = $shop_num;
		if( !$popu ){//处理人气为零的异常情况，先按店面格数算的固有人气值，再和该等级对应的最大人气值比较
			$popu = $shop_num*15;//此时忽略了厕所等人气加成
		}
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
		foreach( $condata as $s=>$gs ){
			$sconfig = ItemConfig::getItem( $shopids[$s] );
			//			$ret['sconfig'][$s] = $sconfig;
			//			$ret['before_gs'][] = $gs;
			array();
			if( 0 ){//对电影院等特殊商店加入结算时间
			}
			ksort($gs);
			//          $ret['after_gs'][] = $gs;
			$curtime = 0;//可以售卖新商品时间
			$cgoods = array();
			foreach( $gs as $t=>$g ){
				//			    if( $g['pos']['x'] == $order ){
				//				    $ret['before_g'][$s][$t] = $g;
				$gconfig = ItemConfig::getItem($g['tag']);
				//				    $ret['gconfig'][$s][$t] = $gconfig;
				$ctime = $g['ctime'];//上次结算时间
				if($curtime < $t)
					$curtime = $t; //上架时间
				if( $curtime< $ctime )
					$curtime = $ctime;
				$g['ctime'] = $now;  //　忽略之　　//结算时间不宜在此赋值，这样会把一些诸如在待售队列中本没有算
				//				    $ret['tloop'][$s][$t] = date( TM_FORMAT,$curtime );
				$gaps = array();
				if( $used_advert ){
					$tmp = self::getTimeRates( $tu,$gaps,$used_advert,$curtime,$popu,$ua['maxpopu'],$now,$shop_num );
			            $ret['advertisement'][$s][$t] = $tmp;
				}
				else{
					$gaps = array( array( $now-$curtime,$popu/( $shop_num*15 ) ));
				}					
				$ret['gaps'][$s][$t] = $gaps;
				//				    foreach($gaps as $gr){
				foreach( $gaps as $k=>$gr ){//测试信息需要该索引值
					$stime = $gr[0];
					if( $sconfig['gridWidth'] )					
						$pertime = $gconfig['selltime']/( $sconfig['gridWidth'] * $gr[1] );
					if( $pertime )
						$snum = floor( $stime/$pertime );
					$ret['pertime'][$s][$t][$k] = $pertime;
					if($snum >= $g['num']){//卖完了
						$asnum = $g['num'];
					}
					else{
						$asnum = $snum;
					}
					$ret['asnum'][$s][$t][$k][$g['tag'] ] = $asnum;
					$ret['sell'][$g['tag']] += $asnum;
					$sale_count += $asnum;//记录销售份数，成就用
					//					    $curtime += ( $asnum * $pertime );
					//                        $curtime += $k;
					$income += $asnum* $gconfig['sellmoney'];  //sellmoney是单份物品的卖价
					$g['num'] -= $asnum;
					//					    $g['scount'] += $asnum;
					if( $g['num']==0 ){//当前时间段卖光此箱货物，继续卖下一个货物
						$cgoods[]=$g;
						$selloutids[] = $g['id'];
						break;//跳出时间段循环，继续卖同一商店下一个上架时间的货物（在同一商店，同一时间上架但售卖顺序不同的货物，已在上架时微调成不同上架时间）
					}
				}
				if( $g['num']!= 0 ){
					$tu->puto( $g,TT::GOODS_GROUP );//未卖完的商品需要保存回库，继续卖同一商店下一个上架时间的货物（在同一商店，一时间上架但售卖顺序不同的货物，已在上架时微调成不同上架时间）
					break;//跳出上架时间循环，但是继续店铺循环，终止同一店铺的货物队列中其他货物的结算
				}
				//			    }
			}
		}
			//删除使用过的广告队列
		unset( $adv['use'] );
		if( $used_advert ){//如果是空数组
			$adv['use'] = $used_advert;
		}
		$adv['id'] = $aid;
/*		
		$adv = $tu->getbyid( $aid );
		$used_advert = $adv['use'];
		$ret['advert'] = $adv;		
*/
	    //$ret['aaaadv'] = $adv;
		$tu->puto( $adv,TT::ADVERT_GROUP,false );
//        $tu->puto( $adv );
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
	
	/**
	 * 结算卖货
	 * @param $params
	 *   u   - userid
         *   sids  - shop ids
	 * @return 
	 *   s  - OK,noneed(短期内没有需要结算的商品),busy(太快)
	 *   income  - 获得金币
	 *   money   - 总金币
	 *   selloutids - 卖完删除的id
	 *   goods   --数量有辩护的商品列表
	 */  
	public function checkout($params)
	{
		$uid = $params['u'];
		$sids= $params['sids'];
		$tu = new TTUser( $uid );
//		return $tu->compute($sids);
//		return $tu->compute( $tu );
        return self::compute( $tu );
	}
}
