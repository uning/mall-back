<?php

if(!class_exists('TokyoTyrant')){
	class TokyoTyrant{


	};
	class TokyoTyrantTable{
	};
}

define('USERNUM_PERTTDB','2000000');#one database user num
class TT{
	//如果需要索引，则直接加在这里
	const GOODS_GROUP='g';
	const GIFT_GROUP='gi';
	const CAR_GROUP='c';
	const FRIEND_GROUP='f';
	const MSG_GROUP='m';
	const ACHIVE_GROUP='a';
	const TASK_GROUP='t';
	//const SHOP_GROUP='s';
	const INFO_GROUP='i';
	const ITEM_GROUP='o';
	const SHOP_GROUP='o:s';
	const CINEMA_GROUP='o:m';
	const OTHER_GROUP='#';
	const ADVERT_GROUP='v';


	const GEM_STAT='gem'; //金钱
	const MONEY_STAT='money';//金币
	const EXP_STAT='exp';//经验
	const ACHIVEPRE_STAT='ach';//成就前缀
	const TASK_STAT = 'ts';    //用户执行任务的状态锁,0锁上
	const TASKPRE_STAT='task';//任务前缀
	const GARAGE_STAT='garage';//车库容量
	const CAPACITY_STAT='capacity';//mall容纳的商店数
	const SHOP_NUM = 'shop_num';//记录当前可卖货的商店数量
	const GRID_NUM = 'grid';//记录当前可卖货的商店数量
	const FRIEND_STAT = 'fids';//好友平台id

	const POPU='popu';//Popularity 人气
	const MAXPOPU = 'maxpopu';//最大人气值
	const COMPUTE_PONIT='compute';//上次计算时间
	const SHOP_SALEPRE = 'ssale';

	//玩家购买售出系统物品总数记录
	const BUY_STAT_USER = 'buyitem';
	const SALE_STAT_USER = 'saleitem';

	//系统信息，人气等
	const SYS_USER  = 'sys';

	public static $ttservers = array(
			'main'=> array(
				'type'=>'TTExtend',
				'procs'=>array(
					array(
						array('host'=>'127.0.0.1','port'=>'15000')
					     ),
					)
				),
			//前台用数据
			'data'=> array(
				'type'=>'TTExtend',
				'procs'=>array(
					array(
						array('host'=>'127.0.0.1','port'=>'15002')
					     ),
					),
				),
			//邀请，送礼等存储数据
			'other'=> array(
				'type'=>'TTExtend',
				'procs'=>array(
					array(
						array('host'=>'127.0.0.1','port'=>'15004')
					     ),
					),
				),
			//页面端暂存数据
			'web'=> array(
					'type'=>'TTExtend',
					'procs'=>array(
						array(
							array('host'=>'127.0.0.1','port'=>'16000')
						     ),
						),
				     ),

			//========================================table===========================
			//id 增长
			'genid' => array(
					'type'=>'TokyoTyrantTable',
					'procs'=>
					array(
						array(
							array('host'=>'127.0.0.1','port'=>'16004')
						     ),
					     )
					),

			'log'=> array(
					'type'=>'TTable',
					'procs'=>array(
						array(
							array('host'=>'127.0.0.1','port'=>'16002')
						     ),
						),
				     ),
			'stat' => array(
					'type'=>'TTable',
					'procs'=>
					array(
						array(
							array('host'=>'127.0.0.1','port'=>'16000')
						     ),
					     )
				       ),

			'link'=> array(
					'type'=>'TTable',
					'procs'=>array(
						array(
							array('host'=>'127.0.0.1','port'=>'16006')
						     ),
						),
				      ),
			'order'=> array(
					'type'=>'TTable',
					'procs'=>array(
						array(
							array('host'=>'127.0.0.1','port'=>'16008')
						     ),
						),
				       ),
			);




	static public function get_tt($name,$uid=0,$type='master')
	{
		static $inst;
		static $byhost;
		$id = floor($uid/USERNUM_PERTTDB);
		$ret = &$inst[$name.':'.$type.':'.$id];
		if( $ret )
			return $ret;

		$obj = self::$ttservers[$name]['type'];
		$config = &self::$ttservers[$name]['procs'];

		if($type=='master'){
			$r = 0;
		}else{
			$wc = count($config[$id]);
			$r = rand()%$wc;
		}
		$r= $config[$id][$r];


		$con = &$byhost[$r['host'].$r['port']];
		if($con ){
			$ret = $con;
			return $ret;
		}
		$con = new $obj($r['host'],$r['port']);
		$ret = $con;
		return $ret;
	}


	static function TTWeb(){return new TTUDB(0,'web');}
	static function TTDS($uid){return new TTUDB($uid,'data');}
	static function LinkTT(){static $c;if($c)return $c; $c = &self::get_tt('link');$c->needSV=false;return $c; }
	static function StatTT(){static $c;if($c)return $c; $c = &self::get_tt('stat');$c->needSV=false;return $c; }
	static function LogTT(){static $c;if($c)return $c; $c = &self::get_tt('log');$c->needSV=false;return $c;}
	
}
require_once('TTExtend.php');
require_once('TTUDB.php');

require_once('TTLog.php');
require_once('TTGenid.php');

//
require_once('TTUser.php');

require_once('TTDS.php');
require_once('TTable.php');


