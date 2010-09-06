<?php
class Advert 
{


	/**
	 * 购买广告
	 * @param $params
	 *   require  u      --  玩家id
	 *            tag    --  对应xml的id,广告种类
	 *            num    --  一次购买的广告数量
	 *            type   -- money or gem购买
	 *            
	 * @return 
	 *            s      --  OK,other failed
	 *            --  money,other failed
	 *            --  gem,other failed
	 *            --  level,
	 *            --  conf, config error
	 *            --  notexsit,未找到配置
	 *            tag    --  
	 *            num    --  一次购买的广告数量
	 */

	public function buy( $params )
	{
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		$adv_tag = $params['tag'];
		$num  = $params['num'];
		if(!$num)
			$num = 1;

		$id = $tu->getoid('advert',TT::OTHER_GROUP);
		$config = AdvertConfig::getAdvert( $adv_tag );
		if( !$config ){
			$ret['s'] = 'notexsit';
			return $ret;
		}
		$type = $config[$num];
		if(!$type){
			$ret['s'] = 'notexsit';
			$ret['c'] = $config;
			return $ret;
		}
		$tgem=$tu->change($type[0],-$type[1]);
		if($tgem< 1){
			$ret['s']=$type[0];
			return $ret;
		}
		$advbag = $tu->getbyid($id);	
		$advbag['bag'][$adv_tag]+=$num;
		$advbag['id']=$id;
		$tu->puto( $advbag,TT::ADVERT_GROUP,false);
//		$ret['advbag'] = $advbag; //for debug
        $ret['s'] = 'OK';
		$ret['tag'] =  $adv_tag;
		$ret['num'] =  $num;
		$ret[$type[0]] =  $tgem;
		return $ret;
	}
	/**
	 * 使用广告
	 * @param $params
	 *   require  u      --  玩家id
	 *            tag    --  某类广告id;
	 *            
	 * @return 
	 *            s      --  OK,other failed
	 *            s      --  nofind,没有该广告
	 *            s      --  error,
				
	 */

	public function set($params)
	{
		$uid = $params['u'];
		$adv_tag = $params['tag'];
		$adv = AdvertConfig::getAdvert( $adv_tag );
		if( !$adv ){
			$ret['s'] = 'notexsit';
			return $ret;
		}
		$tu = new TTUser( $uid );
		//增加扣钱操作和加人气值

		$id = $tu->getoid('advert',TT::OTHER_GROUP);
		$advbag = $tu->getbyid($id);
		if(!$advbag || $advbag['bag'][$adv_tag]<1){
			$ret['s']='notexsit';
			$ret['r']=$advbag;
			return $ret;
		}
		$now = time();
		$advbag['use'][$now]=$adv_tag;
		$advbag['bag'][$adv_tag] -=1;
		
		$exp = $tu->getf( TT::EXP_STAT );
		$ua = UpgradeConfig::getUpgradeNeed( $exp );
		$maxpopu = $ua['maxpopu'] + $adv['maxpopular'];
		if( $maxpopu > $tu->getf('max_popu') )
		    $tu->putf( 'maxpopu',$maxpopu );

	    //使用广告次数
		$ret['s'] = 'OK';
		$ret['t'] = $now;
		$ret['tag'] =  $adv_tag;
		$tu->puto( $advbag,TT::ADVERT_GROUP,false);
		return $ret;
	}


	/**
	 * 获取广告
	 * @param $params
	 *   require  u       --  玩家id
	 * @return 
	 *   s                --  OK,other failed
	 *   use              -- 使用广告宣传队列，包括历史和当前正在使用
	 *                    starttime=>tag
	 *                    1234445=>122
	 *   bag              tag=>num,数组
	 */
	public function get( $params)
	{
		$uid = $params['u'];
		$tu = new TTUser($uid);
		$id = $tu->getoid('advert',TT::OTHER_GROUP);
		$advbag = $tu->getbyid($id);
//      $ret['advbag'] = $advbag;
		if( !$advbag['bag'] && !$advbag['use']){
			$ret['s']='notexsit';
			return $ret;
		}
		$advbag['s'] = 'OK';
		$advbag['id'] = $id;
		return $advbag;
	}

}
