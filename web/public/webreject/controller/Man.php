<?php

class Man{

	static $_config=array(
			1=>array('d_money'=>5000,'d_items'=>array(
					array('tag'=>'10101','idp'=>':g')))			
			,2=>array('d_money'=>5000,'d_items'=>array(
					array('tag'=>'10106','idp'=>':g')))
			,3=>array('d_money'=>5000,'d_items'=>array(
					array('tag'=>'10111','idp'=>':g')))
			,4=>array('d_money'=>5000,'d_items'=>array(
					array('tag'=>'71110','idp'=>':o')))
			,5=>array('d_money'=>50000,'d_gem'=>5)
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
Array
(
    [maxlevel=>] => 5
    [do_money] => 100
    [v_money] => 10
    [do_exp] => 100
    [v_exp] => 1
    [do_items] => Array
        (
            [0] => Array
                (
                    [tag] => shop
                    [idp] => :o:shop:o
                )

        )

)
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
			//return $ret;
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
					unset($idp);
					$item['id']=$uid.$idp;
					if( $config['group'] == 'g' ){
						$item['num'] = $config['unitcout'];
					}
					$item['pos'] = 's';
					$tu->puto($item);
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
				unset($idp);
				$item['id']=$uid.$idp;
				if( $config['group'] == 'g' ){
					$item['num'] = $config['unitcout'];
				}
				$item['pos'] = 's';
				$tu->puto($item);
				$award['items'][]=$item;
			}
		}	
		$tu->puto($mano);
		$ret['s'] = 'OK';
		$ret['award'] = $award;
		$ret['all'] = $tu->getf();
		return $ret;
	}
}

