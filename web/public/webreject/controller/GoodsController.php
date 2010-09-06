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
	static protected function getTimeRates( &$advert,$computetime,$now,$popu,$max_popu,$gridWidth )
	{

		$pratio = 0;
		$plong = 0;
		if($popu > $max_popu)
			$popu = $max_popu;
		$old_ratio = $popu/15/$gridWidth;
		$ret=array();
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

			//if( $add_advpopu > $tu->get('max_popu') ){
			//	$tu->putf( 'max_popu',$add_advpopu );
			//}

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
	 * 售卖货物
	 *   
	 * @param $params
	 *  require   u     -- uid
	 *  require   d     -- goods id 数组
	 * @return 
	 *  s     --OK
	 *  
	 */
	public function sale( $params )
	{
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		foreach( $params['d'] as $index=>$id ){
			$goods_obj = $tu->getbyid( $id );
			if( !$goods_obj ){
				$ret['s'] =  'notexsit';
				$ret['index'] = $index;
				return $ret;	            
			}
			$sale_ret = $tu->saleItem( $goods_obj );
			if( $sale_ret['s'] != 'OK' ){
				$sale_ret['index'] = $index;
				return $sale_ret;
			}	        
		}
		$tu->remove( $params['d'] );
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
		$stat = array();    // for stat
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
			$stat['tags'][$index] = $goods_obj['tag'];
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
			//$shop_obj['goods'][$goods['pos']['x']]= $goods['id'];  //没必要
			$shop_obj['goods'][$goods['id']]=$now + $goods['pos']['x'];  //没必要
			$goods['stime'] =  $now + $goods['pos']['x']; //对同一商店同一时间上架的货物，按出售顺序将上架时间轻微调整以方便处理
			$goods['num'] =  $item['unitcout'];
			$goods['stag']  =  $shop_obj['tag'];//商店类型
			$tu->puto($goods,GOODS_GROUP);
		}
		foreach($shops as $s){
			//for what?
			//$goods = $s['goods'];
			//$ngoods = asort($goods);
			//$s['goods'] = $ngoods;
			$tu->puto($s);
		}
		TTLog::record(array('m'=>__METHOD__,'s'=>'OK','u'=>$uid,'tm'=> $_SERVER['REQUEST_TIME'],'p'=>json_encode($stat)));
		$ret['s'] = 'OK';
		return $ret;
	}



	/**
	 * 结算卖货
	 * @param $params use shop
	 *   u   - userid
	 *   sids  - shop ids
	 * @return 
	 *   s  - OK,noneed(短期内没有需要结算的商品),busy(太快)
	 *   income  - 获得金币
	 *   money   - 总金币
	 *   selloutids - 卖完删除的id
	 *   goods   --数量有辩护的商品列表
	 */  
	static public function shop_checkout($params)
	{
		$uid = $params['u'];
		$sids= $params['sids'];
		$now= $params['now'];
		if(!$now){
			$now=time();
		}
		$tu = new TTUser( $uid );
		//获取人气和宣传值
		$shops = $tu->get( TT::SHOP_GROUP);
		foreach( $shops as $shop ){
			if( $shop['pos'] != 's' ){
				$item = ItemConfig::getItem( $shop['tag'] );
				@asort($shop['goods']);
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
			$ret['s']='OK';
			$ret['msg']='nogoods';
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

			$curtime = $shop['ctime'];//可以售卖新商品时间
			$cgoods = array();
			$shop_changed=false;
			foreach( $gs as $t=>$g ){
				$gconfig = ItemConfig::getItem($g['tag']);
				if(!$gconfig){
					continue;
				}
				$ctime = $g['ctime'];//上次结算时间
				if($curtime < $t)
					$curtime = $t; //上架时间
				if( $curtime< $ctime )
					$curtime = $ctime;
				if($curtime < $g['stime'])
					$curtime = $g['stime'];
				$gaps =  self::getTimeRates($used_advert,$curtime,$now,$popu,$maxpopu,$total_width);
				foreach( $gaps as $k=>$gr ){//测试信息需要该索引值
					//$snum = floor( $gr[0]/$gconfig['selltime']*$gr[1] );
					$pertime = $gr[0]/$gconfig['selltime']*$gr[1]*$sconfig['gridWidth'];
					$snum = floor( $gr[0]/$pertime);
					if($snum >= $g['num']){//卖完了
						$asnum = $g['num'];
					}
					else{
						$asnum = $snum;
					}
					if($asnum==0){
						break;
					} 

					$ret['sell'][$g['tag']] += $asnum;
					$sale_count += $asnum;//记录销售份数，成就用
					$income += $asnum* $gconfig['sellmoney'];  //sellmoney是单份物品的卖价
					$g['num'] -= $asnum;
					$curtime +=  floor($asnum * $pertime);//

					$shop_changed=true;
					$shop['ctime']=$curtime;
					$g['ctime'] = $curtime;  
					if( $g['num']==0 ){//当前时间段卖光此箱货物，继续卖下一个货物
						$cgoods[]=$g;
						$selloutids[] = $g['id'];
						unset($shop['goods'][$g['id']]);
						break;//跳出时间段循环，继续卖同一商店下一个上架时间的货物（在同一商店，同一时间上架但售卖顺序不同的货物，已在上架时微调成不同上架时间）
					}
				}//foreach group
				if( $g['num']!= 0 ){
					$tu->puto( $g,TT::GOODS_GROUP);
					break;//跳出上架时间循环，但是继续店铺循环，终止同一店铺的货物队列中其他货物的结算
				}
			}//foreach goods
			if($shop_changed){
				$tu->puto($shop);
			}
		}//foreach shop





		//总销售份数
		$now_sale_count = $tu->numch( 'total_count',$sale_count );
		//总销售额
		$now_total_sale = $tu->numch( 'total_sale',$income );

		//记录玩家每一种物品卖出量
		if($ret['sell']){
			foreach($ret['sell'] as $gid=>$num){
				$tu->numch("sale_goods_$gid",$num);	
			}
		}

		$ret['s'] = 'OK';
		$ret['income'] = $income;
		$ret['money']  = $tu->chMoney($income);
		$ret['t'] = $now;
		$ret['rids'] = $selloutids;
		$ret['u'] = $uid;
		TTLog::record(array('m'=>__METHOD__,'u'=>$uid,'tm'=> $_SERVER['REQUEST_TIME'],'p'=>json_encode($ret)));
		$tu->remove( $selloutids);
		return $ret;
	}

	static public function dcheckout($params)
	{
		$uid = $params['u'];
		$sids= $params['sids'];
		$now= $params['now'];
		$tu = new TTUser( $uid );
		if(!$now)
			$now = time();
		$debug = true;
		$goods = $tu->get( TT::GOODS_GROUP);
		//$ret['goods']=$goods;
		foreach( $goods as $g ){
			$shopid = $g['pos']['y'];
			if(!$shopid || $shopid=='s')
				continue;
			if(!isset($condata[$shopid]['shop'])){
				$shop = $tu->getbyid($shopid);
				if(!$shop){
					//error log
					$ret['goods_no_shop'][] = $g;
					continue;
				}
				$condata[$shopid]['shop'] = $shop;
				$item = ItemConfig::getItem( $shop['tag'] );
				$condata[$shopid]['sconfig']= $item;
				$total_width += $item['gridWidth'];
			}
			$stime = $g['stime']; //上架时间 
			$condata[$shopid]['goods'][$stime]= $g;

		}
		//$ret['condata'] = $condata;

		if(!$condata || !$total_width){
			$ret['s']='OK';
			$ret['msg']='nogoods';
			$ret['condata']=$condata;
			$ret['total_width']=$total_width;
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
			//	$tu->puto($adv);
		}else if($used_advert){
			unset($adv['use']);
			//	$tu->puto($adv);
		}

		$selloutids = array();
		$income = 0;
		$sale_count = 0; //销售份数
		$popu +=15*$total_width;
		foreach( $condata as $s=>$vvv ){
			$sconfig = &$vvv['sconfig'];
			$gs  = &$vvv['goods'];
			ksort($gs);
			$shop = &$vvv['shop'];

			$curtime = $shop['ctime'];//可以售卖新商品时间
			$cgoods = array();
			$shop_changed=false;
			foreach( $gs as $t=>$g ){
				$gconfig = ItemConfig::getItem($g['tag']);
				if(!$gconfig){
					continue;
				}
				$ctime = $g['ctime'];//上次结算时间
				if($curtime < $t)
					$curtime = $t; //上架时间
				if( $curtime< $ctime )
					$curtime = $ctime;
				if($curtime < $g['stime'])
					$curtime = $g['stime'];
				$gaps = self::getTimeRates($used_advert,$curtime,$now,$popu,$maxpopu,$total_width);
				$ret[$g['id']]['startctime']=$curtime;
				$ret[$g['id']]['gaps']=$gaps;
				$ret[$g['id']]['shop']=$s;
				$ret[$g['id']]['mydata']=$g;
				foreach( $gaps as $k=>$gr ){//测试信息需要该索引值
					$pertime = $gconfig['selltime']/$gr[1]/$sconfig['gridWidth'];
					$snum = floor( $gr[0]/$pertime);
					if($snum >= $g['num']){//卖完了
						$asnum = $g['num'];
					}
					else{
						$asnum = $snum;
					}


					$ret['sell'][$g['tag']] += $asnum;
					$sale_count += $asnum;//记录销售份数，成就用
					$income += $asnum* $gconfig['sellmoney'];  //sellmoney是单份物品的卖价
					$g['num'] -= $asnum;
					$curtime +=  floor($asnum * $pertime);//
					$g['ctime'] = $curtime;  
					$shop['ctime']=$curtime;
					if($debug){
						$ret[$g['id']][$k]['sell_num']=$asnum;
						$ret[$g['id']][$k]['endcurtime']=$curtime;
						$ret[$g['id']][$k]['gap']=$gr[0];
						$ret[$g['id']][$k]['pertime']=$pertime;
						$ret[$g['id']][$k]['ratio']=$gr[1];
						$ret[$g['id']][$k]['left_num']=$g['num'];
						$ret[$g['id']][$k]['basespertime'] = $gconfig['selltime'];
						$ret[$g['id']][$k]['shopwidth'] = $sconfig['gridWidth'];
					}
					if( $g['num']==0 ){//当前时间段卖光此箱货物，继续卖下一个货物
						$cgoods[]=$g;
						$selloutids[] = $g['id'];
						$shop_changed=true;
						unset($shop['goods'][$g['id']]);
						break;//跳出时间段循环，继续卖同一商店下一个上架时间的货物（在同一商店，同一时间上架但售卖顺序不同的货物，已在上架时微调成不同上架时间）
					}
				}
				if( $g['num']!= 0 ){
					if(!$debug)
						$tu->puto( $g,TT::GOODS_GROUP );
					break;//跳出上架时间循环，但是继续店铺循环，终止同一店铺的货物队列中其他货物的结算
				}
			}//foreach goods
			if($shop_changed){
				if(!$debug)
					$tu->puto( $g,TT::GOODS_GROUP );
			}
		}//foreach shop



		if($debug){
			$ret['total_width'] = $total_width;
			$ret['popu']   = $popu;
			$ret['maxpopu']   = $maxpopu;
			$ret['params']   = $params;
		}


		//总销售份数
		//总销售额

		//记录玩家每一种物品卖出量

		$ret['s'] = 'OK';
		$ret['income'] = $income;
		//$ret['money']  = $tu->numch('money',$income);
		$ret['t'] = $now;
		$ret['rids'] = $selloutids;
		$ret['u'] = $uid;
		return $ret;
	}
	/**
	 * 结算卖货 with goods
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
		$now=time();
		$tu = new ttuser( $uid );
		//获取人气和宣传值

		$goods = $tu->get(TT::GOODS_GROUP);
		foreach( $goods as $g ){
			$shopid = $g['pos']['y'];
			if(!$shopid || $shopid=='s')
				continue;
			if(!isset($condata[$shopid]['shop'])){
				$shop = $tu->getbyid($shopid);
				if(!$shop){
					//error log
					continue;
				}
				$condata[$shopid]['shop'] = $shop;
				$item = ItemConfig::getItem( $shop['tag'] );
				$condata[$shopid]['sconfig']= $item;
				$total_width += $item['gridWidth'];
			}
			$stime = $g['stime']; //上架时间 
			$condata[$shopid]['goods'][$stime]= $g;

		}

		if(!$condata || !$total_width){
			$ret['s']='OK';
			$ret['msg']='nogoods';
			return $ret;
		}

		$params = $tu->getf( array(TT::POPU,TT::EXP_STAT) );
		$popu = $params[TT::POPU];
		if($popu<0)
			$popu = 0;
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
			ksort($gs);
			$shop = &$vvv['shop'];

			$curtime = $shop['ctime'];//可以售卖新商品时间
			$cgoods = array();
			$shop_changed=false;
			$shop_empty = true;
			foreach( $gs as $t=>$g ){
				$gconfig = ItemConfig::getItem($g['tag']);
				if(!$gconfig){
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
				$gaps =  self::getTimeRates($used_advert,$curtime,$now,$popu,$maxpopu,$total_width);
				foreach( $gaps as $k=>$gr ){//测试信息需要该索引值
					//$snum = floor( $gr[0]/$gconfig['selltime']*$gr[1] );
					$pertime = $gconfig['selltime']/$gr[1]/$sconfig['gridWidth'];
					$snum = floor( $gr[0]/$pertime);
					if($snum >= $g['num']){//卖完了
						$asnum = $g['num'];
					}
					else{
						$asnum = $snum;
					}
					if($asnum==0){
						break;
					} 

					$ret['sell'][$g['tag']] += $asnum;
					$sale_count += $asnum;//记录销售份数，成就用
					$income += $asnum* $gconfig['sellmoney'];  //sellmoney是单份物品的卖价
					$g['num'] -= $asnum;
					$curtime +=  floor($asnum * $pertime);//

					$shop_changed=true;
					$shop['ctime']=$curtime;
					if( $g['num']==0 ){//当前时间段卖光此箱货物，继续卖下一个货物
						$cgoods[]=$g;
						$selloutids[] = $g['id'];
						unset($shop['goods'][$g['id']]);
						break;//跳出时间段循环，继续卖同一商店下一个上架时间的货物（在同一商店，同一时间上架但售卖顺序不同的货物，已在上架时微调成不同上架时间）
					}
				}//foreach group
				if( $g['num']!= 0 ){
					$tu->puto( $g,TT::GOODS_GROUP);
					$shop_empty = false;
					break;//跳出上架时间循环，但是继续店铺循环，终止同一店铺的货物队列中其他货物的结算
				}
			}//foreach goods
			if($shop_changed){
				if($shop_empty){
					unset($shop['goods']);
				}
				$tu->puto($shop);
			}
		}//foreach shop





		//总销售份数
		$now_sale_count = $tu->numch( 'total_count',$sale_count );
		//总销售额
		$now_total_sale = $tu->numch( 'total_sale',$income );

		//记录玩家每一种物品卖出量
		if($ret['sell']){
			foreach($ret['sell'] as $gid=>$num){
				$tu->numch("sale_goods_$gid",$num);	
			}
		}

		$ret['s'] = 'OK';
		$ret['income'] = $income;
		$ret['money']  = $tu->chMoney($income);
		$ret['t'] = $now;
		$ret['rids'] = $selloutids;
		TTLog::record(array('m'=>__METHOD__,'u'=>$uid,'tm'=> $_SERVER['REQUEST_TIME'],'p'=>json_encode($ret)));
		$tu->remove( $selloutids);
		return $ret;
	}

}
