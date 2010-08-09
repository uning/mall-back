<?php

define('USERNUM_PERTTDB','2000000');#one database user num
class TT{
	//如果需要索引，则直接加在这里
	const GOODS_GROUP='g';
	const CAR_GROUP='c';
	const FRIEND_GROUP='f';
	const MSG_GROUP='m';
	const ACHIVE_GROUP='a';
	const TASK_GROUP='t';
	const SHOP_GROUP='s';
	const INFO_GROUP='i';
	const ITEM_GROUP='o';
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
//	const PROMOT_STAT='promot';//广告宣传锁,0锁上
	const SHOP_NUM = 'shop_num';//记录当前可卖货的商店数量
	const FRIEND_STAT = 'fids';//好友平台id

	const POPU='popu';//Popularity 人气
	const PROMOT_TAG='prom_tag';// 宣传种类
	const PROMOT='prom';// 宣传提高比率
	const PROMOT_START='prom_start';// 宣传开始时间(s)
	const PROMOT_PERIOD='prom_long';// 宣传时长(s)
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
					0=>array(
						'master'=>array(
							array('host'=>'127.0.0.1','port'=>'15000')
							),
						'slave'=>array(
							array('host'=>'127.0.0.1','port'=>'15000')
							)
						),
					)
				),
			//前台用数据
			'data'=> array(
				'type'=>'TTExtend',
				'procs'=>array(
					0=>array(
						'master'=>array(
							array('host'=>'127.0.0.1','port'=>'15002')
							),
						),
					),
				),
			//邀请，送礼等存储数据
			'other'=> array(
					'type'=>'TTExtend',
					'procs'=>array(
						0=>array(
							'master'=>array(
								array('host'=>'127.0.0.1','port'=>'15004')
								),
							),
						),
				       ),
			//金币等
			'web'=> array(
					'type'=>'TTExtend',
					'procs'=>array(
						array(
							'master'=>array(
								array('host'=>'127.0.0.1','port'=>'16000')
								),
							'slave'=>array(
								array('host'=>'127.0.0.1','port'=>'16001')
								),
						     ),
						),
				      ),
			//id 增长
			'genid' => array(
					'type'=>'TTExtendTable',
					'procs'=>
					array(
						array(
							'master'=>array(
								array('host'=>'127.0.0.1','port'=>'16004')
								),
						     ),
					     )
					),

			'log'=> array(
					'type'=>'TTExtendTable',
					'procs'=>array(
						array(
							'master'=>array(
								array('host'=>'127.0.0.1','port'=>'10010')
								),
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
			$wc = count($config[$id][$type]);
			$r = rand()%$wc;
		}
		$r= $config[$id][$type][$r];


		$con = &$byhost[$r['host'].$r['port']];
		if($con ){
			$ret = $con;
			return $ret;
		}
		$con = new $obj($r['host'],$r['port']);
		$ret = $con;
		return $ret;
	}


	static function getpre($k1,$k2)
	{
		$ret = '';
		for($i=0;;$i++){
			$a = $k2[$i];
			if($k1[$i]!==$a)
				break;      
			$ret .= $a;
		} 	
		return $ret;
	}

	/**
	 * 遍历代码
	 * TODO:just a trick  code , that we can get 
	 * if kpre not change in a call ,we can do nothing
	 *$kpre = '';
	 *while($d=TT::next_recs($t,$kpre,$count)
	 *{

	 *}          
	 *
	 **/
	static public function next_recs(&$t,&$kpre,$count=500)
	{
             $keys = $t->fwmKeys($kpre,$count);
             if(!$keys)
                return null;
	     $d = $t->get($keys);
	     $c = count($d);
             if($c<$count){
               $kpre .= '__';
	       return $d;
             }     
	     $kpre = self::getpre(end($keys),prev($keys));
	     if($kpre == ''){
		     $kpre = end($keys);
	     }
             return $d;
	}
	
}
require_once('TTExtend.php');
require_once('TTUDB.php');

require_once('TTLog.php');
require_once('TTGenid.php');

//
//require_once('TTUser.php');

require_once('TTDS.php');


