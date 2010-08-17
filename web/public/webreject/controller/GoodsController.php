<?php
class GoodsController
{
	/**
	 * 返回
	 * array(时间长度，卖出速率)
	 * @param $param
	 * @param $shop_idle_time
	 * @param $g
	 * @return unknown_type
	 */
	static protected function getTimeRates(&$advert,$computetime,$now,$popu,$max_popu,$gridWidth )
	{

		$pratio = 0;
		$plong = 0;
		if($popu > $max_popu)
                   $popu = $max_popu;
		$old_ratio = $popu/15/$gridWidth;
		foreach( $advert as $start=>$tag ){
			if($start > $now)
			    continue;
			$adv = AdvertConfig::getAdvert( $tag );
			if($start  + $adv['allTime'] < $computetime)
				continue;

			if($pratio && $plong){
				$gap = $start -$computetime; 
				if($gap <$plong){
					$ret[]=array($gap ,$pratio);
				}
				else{
					$ret[]=array($plong,$pratio);
					$ret[]=array($gap -$plong ,$old_ratio);
				}
				$computetime = $start;

			}
			if($computetime < $start){
				$ret[]=array($start - $computetime,$old_ratio);
				$computetime = $start;
			}
			$add_advpopu = $popu + $adv['popularity'];			
			$max_addadvpopu = $max_popu + $adv['maxpopular'];
			if( $add_advpopu > $max_addadvpopu ){
				$add_advpopu = $max_addadvpopu;
			}
			$plong  =  $adv['allTime'];
			$pratio =  1 + $add_advpopu/$gridWidth/15;
		}
		$start = $now;
		if($pratio && $plong){
			$gap = $start -$computetime; 
			if($gap <$plong){
				$ret[]=array($gap ,$pratio);
			}
			else{
				$ret[]=array($plong,$pratio);
				$ret[]=array($gap -$plong ,$old_ratio);
			}
			$computetime = $start;
		}
		if($computetime < $start){
			$ret[] = array($start - $computetime,$old_ratio);
			$computetime = $start;
		}
		return $ret;
	}


	
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
			$item = ItemConfig::getItem($goods['tag']);
			if( !$item ){
				//写入日志
				continue;
			}
			$shop = $tu->getbyid( $goods['pos']['y'] );
			$goods['stag'] = $shop['tag']; 
			$goods['stime'] = $now + $goods['pos']['x'];
			$goods['num'] = $item['unitcout'];
			$gid = $tu->getdid('',TT::GOODS_GROUP);
			$goods['id'] = $gid;
			$tu->puto($goods,TT::GOODS_GROUP);
			$ids[] = $gid ; 
			$shop['goods'][$gid] = $now ;
			$tu->puto($shop);
		}
		$ret['s'] = 'OK';
		$ret['ids'] = $ids;
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
				$ret['index'] = $index;
				return $ret;
			}
			$sconfig = & $shopconfs[$shop_obj['tag'] ];
			if(!$sconfig)
				$sconfig =  ItemConfig::getItem( $shop_obj['tag'] ); 
			if( !$sconfig ){
				$ret['s'] = 'noshop';
				$ret['index'] = $index;
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
			$shop_obj['goods'][$goods['id']]=$now + $goods['pos']['x'];  //没必要
			$goods['stime'] =  $now + $goods['pos']['x']; //对同一商店同一时间上架的货物，按出售顺序将上架时间轻微调整以方便处理
			$goods['num'] =  $item['unitcout'];
			$goods['stag']  =  $shop_obj['tag'];//商店类型
			$tu->puto($goods,GOODS_GROUP);
		}
		foreach($shops as $s){
			$tu->puto($s);
		}
		$ret['s'] = 'OK';
		return $ret;
	}

	/**
	 * 结算收益
	 * 取出每个店面所有上架商品
	 * 按上架时间排序，售卖
	 * @return 
	 */
	static protected function compute( &$tu ,$now=null)
	{	    
		if(!$now)
			$now = time();
		//获取人气和宣传值
		$params = $tu->getf( array(TT::POPU,TT::EXP_STAT,'shop_num') );
//		$ret['params'] = $params;
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
				$sconfig= ItemConfig::getItem( $stag );

				$sconfigs[$sid] = $sconfig;
				$total_width   += $sconfig['gridWidth'];
			}
		}
//		$ret['shopids'] = $shopids;
		if(!$condata){
			$ret['s']='nogoods';
			return $ret;
		}
//		$ret['condata'] = $condata;
		$popu = $params[TT::POPU];
                

//		$ret['bpopu'] = $popu;
		$ua = UpgradeConfig::getUpgradeNeed( $params['exp'] );
//		$ret['ua'] = $ua;

		/*
		$shops = $tu->get( TT::SHOP_GROUP );
		foreach( $shops as $shop ){
//			$ret['shop_num_shop'][] = $shop;
			if( $shop['pos'] != 's' ){
			    $item = ItemConfig::getItem( $shop['tag'] );
			    $shop_num += $item['gridWidth'];
			}
		}

//		$ret['shopnum'] = $shop_num;
		if( !$shop_num ){
			$ret['s'] = 'noshopexist';
			return $ret;
		}
		$shop_popu = $shop_num*15;//只算店面人气
		$popu += $shop_popu;

		if( $popu > $ua['maxpopu'] ){
			$popu = $ua['maxpopu'];
		}
		 */						
//		$ret['apopu'] = $popu;
		$aid = $tu->getoid( 'advert',TT::OTHER_GROUP );
		$adv = $tu->getbyid( $aid );
		$used_advert = $adv['use'];
		$selloutids = array();
		$income = 0;
		$sale_count = 0; //销售份数

		if($total_width)
			$apopu = $params[TT::POPU]/$total_width/15;
		foreach( $condata as $s=>$gs ){
			$sconfig = ItemConfig::getItem( $shopids[$s] );
			ksort($gs);
			$curtime = 0;//可以售卖新商品时间
			$cgoods = array();
			foreach( $gs as $t=>$g ){
				$gconfig = ItemConfig::getItem($g['tag']);
//				$ret['gconfig'][$s][$t] = $gconfig;
				$ctime = $g['ctime'];//上次结算时间
				if($curtime < $t)
					$curtime = $t; //上架时间
				if( $curtime< $ctime )
					$curtime = $ctime;
				$g['ctime'] = $now;  //　忽略之　　//结算时间不宜在此赋值，这样会把一些诸如在待售队列中本没有算
				$gaps = array();
				/*
				if( $used_advert ){
					$tmp = $tu->getTimeRates( $gaps,$used_advert,$curtime,$popu,$ua['maxpopu'],$now,$shop_num );
//			        $ret['advertisement'][$s][$t] = $tmp;
				}
				else{
					$gaps = array( array( $now-$curtime,$popu/( $shop_num*15 ) ));
				}*/					
				$gaps = array( array( $now-$curtime, 1 + $apopu ) );

				$ret[$s]['shopconfig']=$sconfig;
				foreach( $gaps as $k=>$gr ){//测试信息需要该索引值
					$stime = $gr[0];
					$snum = floor( $gr[0]/$gconfig['selltime']*$gr[1] );

					if($snum >= $g['num']){//卖完了
						$asnum = $g['num'];
					}
					else{
						$asnum = $snum;
					}
					$ret[$s][$g['id']][$k]['sell_num']=$asnum;
					$ret[$s][$g['id']][$k]['curtime']=$curtime;
					$ret[$s][$g['id']][$k]['gap']=$gr[0];
					$ret[$s][$g['id']][$k]['ratio']=$gr[1];
					$ret[$s][$g['id']][$k]['basespertime']=$gconfig['selltime'];
					$ret[$s][$g['id']][$k]['shopwidth']=$sconfig['gridWidth'];

					$ret['sell'][$g['tag']] += $asnum;
					$sale_count += $asnum;//记录销售份数，成就用
					$income += $asnum* $gconfig['sellmoney'];  //sellmoney是单份物品的卖价
					$g['num'] -= $asnum;
					$curtime +=  floor($asnum * $pertime);//faint ,这个都不加

					if( $g['num']==0 ){//当前时间段卖光此箱货物，继续卖下一个货物
						$cgoods[]=$g;
						$selloutids[] = $g['id'];
						break;//跳出时间段循环，继续卖同一商店下一个上架时间的货物（在同一商店，同一时间上架但售卖顺序不同的货物，已在上架时微调成不同上架时间）
					}
				}//foreach gap
				if( $g['num']!= 0 ){
					$tu->puto( $g,TT::GOODS_GROUP );
					break;//跳出上架时间循环，但是继续店铺循环，终止同一店铺的货物队列中其他货物的结算
				}
			}//foreach goods
		}//foreach shop

		unset( $adv['use'] );
		if( $used_advert ){//如果是空数组
			$adv['use'] = $used_advert;
		}
		$adv['id'] = $aid;
		$ret['shop_num'] = $shop_num;
		$ret['popu']   = $popu;
		$ret['params']   = $params;


		//总销售份数
		$now_sale_count = $tu->numch( 'total_count',$sale_count );
		//总销售额
		$now_total_sale = $tu->numch( 'total_sale',$income );
		$ret['s'] = 'OK';
		$ret['income'] = $income;
		$ret['money']  = $tu->numch('money',$income);
		$ret['t'] = $now;
		$tu->remove( $selloutids );
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
	public function old_checkout($params)
	{
		$uid = $params['u'];
		$sids= $params['sids'];
		$now= $params['now'];
		$tu = new TTUser( $uid );
		return self::compute( $tu,$now );
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
	static public function checkout($params)
	{
		$uid = $params['u'];
		$sids= $params['sids'];
		$now= $params['now'];
		$tu = new TTUser( $uid );
		if(!$now)
			$now = time();
		else
                     $debug = true;
		//获取人气和宣传值
		$shops = $tu->get( TT::SHOP_GROUP);
		foreach( $shops as $shop ){
			if( $shop['pos'] != 's' ){
				$item = ItemConfig::getItem( $shop['tag'] );
				$gids = @array_keys($shop['goods']);
				if($gids){
					$gs  = $tu->getbyids($gids);
					if($gs['g']){
						$condata[$shop['id']]['sconfig']= $item;
						$condata[$shop['id']]['shop']= $shop;
						$condata[$shop['id']]['goods']= &$gs['g'];
						$total_width += $item['gridWidth'];
					}else{
						unset($shop['goods']);//=array();
						$tu->puto($shop);	
					}
				}
			}
		}


		if(!$condata || !$total_width){
			$ret['s']='nogoods';
			return $ret;
		}
		$params = $tu->getf( array(TT::POPU,TT::EXP_STAT) );
		$popu = $params[TT::POPU];
		$ua = UpgradeConfig::getUpgradeNeed( $params['exp'] );
                $maxpopu = $ua['maxpopu'];
		$aid = $tu->getoid( 'advert',TT::OTHER_GROUP );
		$adv = $tu->getbyid( $aid );
		$used_advert = $adv['use'];
		if(!$used_advert)
                   $used_advert = array();
		
		//处理广告
		foreach($used_advert as $k=>$v){
			$adv = AdvertConfig::getAdvert( $tag );
			if($start  + $adv['allTime'] < $now)
				continue;
			$savead[$k]= $v;
		}

		if($savead){
			$adv['use']=$savead;
			$tu->puto($adv);
		}else if($used_advert){
			unset($adv['use']);
			$tu->puto($adv);
		}

		$selloutids = array();
		$income = 0;
		$sale_count = 0; //销售份数
		$popu +=15*$total_width;
		foreach( $condata as $s=>$vvv ){
			$sconfig = &$vvv['sconfig'];
			$gs  = &$vvv['goods'];
			$shop = &$vvv['shop'];

			$curtime = 0;//可以售卖新商品时间
			$cgoods = array();
			$shop_changed=false;
			foreach( $gs as $t=>$g ){
				$gconfig = ItemConfig::getItem($g['tag']);
				if(!$gconfig){
					$ret['noconf'][$g['id']]=$g;
					continue;
				}
				$ctime = $g['ctime'];//上次结算时间
				if($curtime < $t)
					$curtime = $t; //上架时间
				if( $curtime< $ctime )
					$curtime = $ctime;
				if($curtime < $g['stime'])
					$curtime = $g['stime'];
				$g['ctime'] = $now;  
				$gaps = $tmp = self::getTimeRates($used_advert,$curtime,$now,$popu,$maxpopu,$total_width);
				//$gaps = array( array( $now-$curtime, 1 + $apopu ) );
				foreach( $gaps as $k=>$gr ){//测试信息需要该索引值
					$snum = floor( $gr[0]/$gconfig['selltime']*$gr[1] );
					if($snum >= $g['num']){//卖完了
						$asnum = $g['num'];
					}
					else{
						$asnum = $snum;
					}

					if($debug){
						$ret[$s][$g['id']][$k]['sell_num']=$asnum;
						$ret[$s][$g['id']][$k]['curtime']=$curtime;
						$ret[$s][$g['id']][$k]['gap']=$gr[0];
						$ret[$s][$g['id']][$k]['ratio']=$gr[1];
						$ret[$s][$g['id']][$k]['basespertime']=$gconfig['selltime'];
						$ret[$s][$g['id']][$k]['shopwidth']=$sconfig['gridWidth'];
					}

					$ret['sell'][$g['tag']] += $asnum;
					$sale_count += $asnum;//记录销售份数，成就用
					$income += $asnum* $gconfig['sellmoney'];  //sellmoney是单份物品的卖价
					$g['num'] -= $asnum;
					$curtime +=  floor($asnum * $pertime);//
					if( $g['num']==0 ){//当前时间段卖光此箱货物，继续卖下一个货物
						$cgoods[]=$g;
						$selloutids[] = $g['id'];
						$shop_changed=true;
						unset($shop['goods'][$g['id']]);
						break;//跳出时间段循环，继续卖同一商店下一个上架时间的货物（在同一商店，同一时间上架但售卖顺序不同的货物，已在上架时微调成不同上架时间）
					}
					$ret[$s][$g['id']][$k]['shop']=$shop;
				}
				if( $g['num']!= 0 ){
					if(!$debug)
						$tu->puto( $g,TT::GOODS_GROUP );
					break;//跳出上架时间循环，但是继续店铺循环，终止同一店铺的货物队列中其他货物的结算
				}
			}//foreach goods
			if($shop_changed){
				if(!$debug)
					$tu->puto($shop);
			}
		}//foreach shop



		if($debug){
			$ret['total_width'] = $total_width;
			$ret['popu']   = $popu;
			$ret['maxpopu']   = $maxpopu;
			$ret['params']   = $params;
		}


		//总销售份数
		$now_sale_count = $tu->numch( 'total_count',$sale_count );
		//总销售额
		$now_total_sale = $tu->numch( 'total_sale',$income );

		$ret['s'] = 'OK';
		$ret['income'] = $income;
		$ret['money']  = $tu->numch('money',$income);
		$ret['t'] = $now;
		if(!$debug)
			$tu->remove( $selloutids );
		return $ret;
	}
}
