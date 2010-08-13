<?php
//require_once dirname(__FILE__)."/../../AchieveConfig.php";
class Achieve
{    
	static $_config=array(1001=>array('type'=>'total_sale','aimNum'=>'1000','levelNeed'=>'1','rewardMoney'=>'1000','rewardRep'=>'100','tag'=>'1001',)
			,1002=>array('type'=>'total_sale','aimNum'=>'5000000','levelNeed'=>'20','rewardMoney'=>'10000','rewardRep'=>'1000','tag'=>'1002',)
			,1003=>array('type'=>'total_sale','aimNum'=>'25000000','levelNeed'=>'30','rewardMoney'=>'50000','rewardRep'=>'5000','tag'=>'1003',)
			,1004=>array('type'=>'total_sale','aimNum'=>'50000000','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','tag'=>'1004',)
			,1005=>array('type'=>'total_count','aimNum'=>'500','levelNeed'=>'1','rewardMoney'=>'1000','rewardRep'=>'100','tag'=>'1005',)
			,1006=>array('type'=>'total_count','aimNum'=>'1000000','levelNeed'=>'20','rewardMoney'=>'10000','rewardRep'=>'1000','tag'=>'1006',)
			,1007=>array('type'=>'total_count','aimNum'=>'2500000','levelNeed'=>'30','rewardMoney'=>'50000','rewardRep'=>'5000','tag'=>'1007',)
			,1008=>array('type'=>'total_count','aimNum'=>'5000000','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','tag'=>'1008',)
			,1015=>array('type'=>'invite_count','aimNum'=>'1','levelNeed'=>'1','rewardMoney'=>'1000','rewardRep'=>'100','tag'=>'1015',)
			,1016=>array('type'=>'invite_count','aimNum'=>'3','levelNeed'=>'1','rewardMoney'=>'10000','rewardRep'=>'1000','tag'=>'1016',)
			,1017=>array('type'=>'invite_count','aimNum'=>'5','levelNeed'=>'10','rewardMoney'=>'50000','rewardRep'=>'5000','tag'=>'1017',)
			,1018=>array('type'=>'invite_count','aimNum'=>'10','levelNeed'=>'20','rewardMoney'=>'100000','rewardRep'=>'10000','tag'=>'1018',)
			,1023=>array('type'=>'popu','aimNum'=>'200','levelNeed'=>'5','rewardMoney'=>'1000','rewardRep'=>'100','tag'=>'1023',)
			,1024=>array('type'=>'popu','aimNum'=>'350','levelNeed'=>'20','rewardMoney'=>'10000','rewardRep'=>'1000','tag'=>'1024',)
			,1025=>array('type'=>'popu','aimNum'=>'450','levelNeed'=>'30','rewardMoney'=>'50000','rewardRep'=>'5000','tag'=>'1025',)
			,1026=>array('type'=>'popu','aimNum'=>'600','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','tag'=>'1026',)
			,1027=>array('type'=>'gogoods_count','aimNum'=>'100','levelNeed'=>'1','rewardMoney'=>'10000','rewardRep'=>'1000','tag'=>'1027',)
			,1028=>array('type'=>'gogoods_count','aimNum'=>'3000','levelNeed'=>'30','rewardMoney'=>'50000','rewardRep'=>'5000','tag'=>'1028',)
			,1029=>array('type'=>'gogoods_count','aimNum'=>'6000','levelNeed'=>'50','rewardMoney'=>'50000','rewardRep'=>'5000','tag'=>'1029',)
			,1030=>array('type'=>'gogoods_count','aimNum'=>'10000','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','tag'=>'1030',)
			,1031=>array('type'=>'advert_count','aimNum'=>'10','levelNeed'=>'20','rewardMoney'=>'10000','rewardRep'=>'1000','tag'=>'1031',)
			,1032=>array('type'=>'advert_count','aimNum'=>'40','levelNeed'=>'30','rewardMoney'=>'50000','rewardRep'=>'5000','tag'=>'1032',)
			,1033=>array('type'=>'advert_count','aimNum'=>'80','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','tag'=>'1033',)
			,1034=>array('type'=>'advert_count','aimNum'=>'200','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','tag'=>'1034',)
			,);
	public static function getAchieve ( $tag )
	{
		return self::$_config[$tag];
	}
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
		$aparams = $tu->getf(array('popu','invite_count','gogoods_count','total_count','total_sale'));
		$ret['aparams'] = $aparams;
/*		if( !$achieves ){
			$ret['s'] = 'empty';
			return $ret;
		}*/
		$ret = array();
                $ret['achieves'] = $achieves;
		foreach( self::$_config as $tag=>$conf){
			if($achieves[$tag]){
				$ret['a'][]=array('tag'=>$tag,'is_done'=>1);
				$ret['test'][$tag]=array('tag'=>$tag,'is_done'=>1);
			}	
			else {//if($aparams[$conf['type']]>=$conf['aimNum']){
				$ret['a'][]=array('tag'=>$tag,'is_done'=>0,'finish_num'=>$aparams[$conf['type']]);
				$ret['test'][$tag]=array('tag'=>$tag,'is_done'=>0);
			}
		}	
		$ret['s'] = 'ok';
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
		$config = self::getAchieve( $tag );
		$count = $tu->getf($config['type']);
		if( $count < $config['aimNum'] ){//检验是否真的完成
			$ret['s'] = 'unfinish';
			return $ret;
		}
		$achieves[$tag] = time();
		$money = $tu->numch( 'money',$config['rewardMoney'] );
		$exp = $tu->numch( 'exp',$config['rewardRep'] );

		if( $config['item'] ){//成就达成的物品奖励
		}
        
		$achieves['id'] = $id;
		$ret['b'] = $achieves;
		$tu->puto( $achieves );
		$ret['a'] = $tu->getbyid( $id );
		$ret['s'] = 'OK';
		return $ret;
	}    
}
