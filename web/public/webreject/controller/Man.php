<?php

class Man{

	static $_config=array(

			1=>array('d_money'=>5000,'d_exp'=>'150','d_items'=>array(
					array('tag'=>'10101','idp'=>':g')))			
			,2=>array('d_money'=>5000,'d_exp'=>'150','d_items'=>array(
					array('tag'=>'10106','idp'=>':g')))
			,3=>array('d_money'=>5000,'d_exp'=>'150','d_items'=>array(
					array('tag'=>'10111','idp'=>':g')))
			,4=>array('d_money'=>5000,'d_exp'=>'150','d_items'=>array(
					array('tag'=>'71110','idp'=>':o')))
			,5=>array('d_money'=>50000,'d_exp'=>'150','d_gem'=>5)
	);
		    
	static private function getAwardConf($step)
	{
		return self::$_config[$step];
	}
    
	/**
	 * @param $params
	 *  require u  -- user id
         *
	 * @return 
	 *  s   -- OK ,or other fail
	 *  d  -- the man obj
                  step=>(vtime,dtime)
	 */
	public function get($params)
	{
		$uid = $params['u'];
		$tu = new TTUser($uid);

		$id = $tu->getoid('mannual',TT::OTHER_GROUP);
		$ret['d'] = $tu->getbyid($id);
		$ret['s'] = 'OK';
		return $ret;
	}


	/**
         * update
	 * @param $params
	 *  require u        -- user 
	 *                    step  -- 新手步数
	 *                 //  v  -- view
	 *                 //  d  -- do
	 * @return 
	 *  s   -- OK ,or other fail
         *  money  -- total money
         *  exp    -- total exp 
	 *  award 
                 money
                 exp
                 items(物品对象数组) 
	 *  d   --新的manual对象
	 */
	public function update($params)
	{
		$uid = $params['u'];
		//$da = $params['d'];
		$step  = $params['step'];
		//$v = ;//$da['v'];
		$d =  1; //;$da['d'];

		$ret = array();
		$conf = self::getAwardConf($step); 	
		if(!$v && !$d){
			$ret['s']='noaction';	
			return $ret;
		}
		if(!$conf){
			$ret['s']='noconf';	
			return $ret;
		}

		$tu = new TTUser($uid);
		$id = $tu->getoid('mannual',TT::OTHER_GROUP);
		$mano = $tu->getbyid($id);
		$mean_gap = 20;
		$now = time();

		$mano['id']=$id;
		$level = $tu->getLevel();
		if($level > $conf['maxlevel']){
			$ret['s']='level';	
			$ret['d']=$mano;
			//return $ret;
		}	

		if($v&&$mano[$step]['vtime']>0){
			$ret['s']='already';	
			$ret['d']=$mano;
			return $ret;
		}
		if($d&&$mano[$step]['dtime']>0){
			$ret['s']='already';	
			$ret['d']=$mano;
			return $ret;//
		}
		$award = array();
		if($v){
			$mano[$step]['vtime']=$now;
			$m = $conf['v_money'];		
			if($m>0){
				$ret['money']=	$tu->chMoney($m);
				$award['money']=$m;
			}
			$m = $conf['v_gem'];
			if($m>0){
				$ret['gem']=	$tu->chGem($m);
				$award['gem']=$m;
			}			
			$m = $conf['v_exp'];		
			if($m>0){
				$ret['exp']=	$tu->addExp($m);
				$award['exp']=$m;
			}

			$items = $conf['v_items'];
			if($items){
				foreach($items as $item){
					$config = ItemConfig::getItem( $item['tag'] );
					$idp = $item['idp'];
//					$item['id']=$uid.$idp;
					unset( $item['idp'] );
					$item['pos'] = 's';
					if( $config['group'] == 'g' ){
						$item['num'] = $config['unitcout'];
						$tu->puto( $item,TT::GOODS_GROUP );
					}
					else{
					    $tu->puto( $item,TT::ITEM_GROUP );
					}
					$award['items'][]=$item;
				}
			}
			$tu->puto( $mano );
			$ret['award'] = $award;
			$ret['s'] = 'OK';
			return $ret;
		}
		$mano[$step]['dtime']=$now;
		$m = $conf['d_money'];		
		if($m>0){
			$ret['money']=	$tu->chMoney($m);
			$award['money']=$m;
		}
		$m = $conf['d_gem'];		
		if($m>0){
			$ret['gem']=	$tu->chGem($m);
			$award['gem']=$m;
		}		
		$m = $conf['d_exp'];
		if($m>0){
			$ret['exp']=	$tu->addExp($m);
			$award['exp']=$m;
		}
		$items = $conf['d_items'];
		if($items){
		    foreach($items as $item){
			    $config = ItemConfig::getItem( $item['tag'] );
			    $idp = $item['idp'];
//			    $item['id']=$uid.$idp;
			    unset( $item['idp'] );
			    $item['pos'] = 's';
			    if( $config['group'] == 'g' ){
				    $item['num'] = $config['unitcout'];
				    $tu->puto( $item,TT::GOODS_GROUP );
			    }
			    else{
			        $tu->puto( $item,TT::ITEM_GROUP );
			    }
			    $award['items'][]=$item;
			}
		}
		$tu->puto($mano);
		$ret['s'] = 'OK';
		$ret['award'] = $award;
		return $ret;
	}

	/**
         * update
	 * @param $params
	 *  require u        -- user 
	 *          type     -- 满足type
	 * @return 
	 *  s   -- OK ,or other fail
                   max,超出限制
                   notype,超出限制
         *  money  -- total money
         *  exp    -- total exp 
	 *  award 
                 money
                 exp
	 *  d   --新的对象
	 */
	public function satisfy($params)
	{
		static $type2award=array(
				1=>array('money'=>10,'exp'=>2)
			      );
		$uid = $params['u'];
		$type  = $params['type'];
		$award = $type2award[$type];
		//$type  = 1;
		if(!$award ){
			$ret['s']='notype';
			return $ret;
		}

		$tu = new TTUser($uid);
		$id = $tu->getoid('satisfy',TT::OTHER_GROUP);
		$data = $tu->getbyid($id);
		$data['id'] = $id;
		$now = time();
                $now_date = date('Ymd',$now);
		
		if($data['date']!=$now_date){
                   $data['r']=array();
		   $data['date']=$now_date;
		}
		if(++$data['r'][$type]>10){
			$ret['s'] = 'max';
			return $ret;
		}
		$ret['award']=$award;
		foreach($award as $k=>$v){
			$ret[$k] = $tu->numch($k,$v);
		}
		$ret['d']=$data;
		$ret['s']='OK';
		$tu->puto($data,'',false);
		return $ret;
	}
	
}

