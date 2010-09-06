<?php
require_once dirname(__FILE__)."/../../AchieveConfig.php";
class Achieve
{
	/**
	 * 获取所有成就
	 * @param $params
	 *  require    u      --  userid
	 * @return
	 *             s                --  OK,others fail
	 *                              --  empty,没有记录
	 *             a                --  成就
	 *                    tag         --   成就种类，对应xml的id
	 *                    is_done     --   是否达成，否为0
	 *                    finish_num  --   目标数的完成数
	 */	

	public function get( $params )
	{
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		$id = $tu->getoid( 'achieves',TT::OTHER_GROUP );
		$achieves = $tu->getbyid( $id );
		$aparams = $tu->getf( array('max_popu','friend_count','gogoods_count','total_count','total_sale') );
//		$ret['aparams'] = $aparams;
/*		if( !$achieves ){
			$ret['s'] = 'empty';
			return $ret;
		}*/
		$ret = array();
                $ret['achieves'] = $achieves;
		foreach( AchieveConfig::$_config as $tag=>$conf){
			if($achieves[$tag]){
				$ret['a'][]=array('tag'=>$tag,'is_done'=>1);
//				$ret['test'][$tag]=array('tag'=>$tag,'is_done'=>1);
			}	
			else {//if($aparams[$conf['group']]>=$conf['aimNum']){
				$ret['a'][]=array('tag'=>$tag,'is_done'=>0,'finish_num'=>$aparams[$conf['group']]);
//				$ret['test'][$tag]=array('tag'=>$tag,'is_done'=>0);
			}
		}	
		$ret['s'] = 'OK';
		return $ret;
	}

	/**
	 * 达成成就
	 * @param $params
	 *  require    u      --  userid
	 *             tag    --  成就种类，对应xml的id
	 * @return
	 *   s                --  OK,others fail
	 */	

	public function finish( $params )
	{
		$uid = $params['u'];
		$tag = $params['tag'];
		$tu = new TTUser( $uid );
		$id = $tu->getoid( 'achieves',TT::OTHER_GROUP );
		$achieves = $tu->getbyid( $id );
		if( $achieves[$tag] ){
			$ret['s'] = 'repeat';
			return $ret;
		}
		$config = AchieveConfig::getAchieve( $tag );
		$count = $tu->getf($config['group']);
		if( $count < $config['aimNum'] ){//检验是否真的完成
			$ret['s'] = 'unfinish';
			return $ret;
		}
		$achieves[$tag] = time();
		$money = $tu->chMoney($config['rewardMoney'] );
		$exp = $tu->numch( 'exp',$config['rewardRep'] );

		if( $config['item'] ){//成就达成的物品奖励
		}
        
		$achieves['id'] = $id;
//		$ret['b'] = $achieves;
		$tu->puto( $achieves );
		$ret['a'] = $tu->getbyid( $id );
		$ret['s'] = 'OK';
		return $ret;
	}    
}
