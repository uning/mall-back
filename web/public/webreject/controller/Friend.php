<?php

/**
 * 
 * @author uning
 *
 */
class Friend{
	/**
	 * 获取好友当天任务
	 * @param $params
	 *  require    u      -- userid
	 *             fid    -- friend id
	 * @return
	 *   s   OK,others fail
	 *   t   tasks        --        任务
	 *                    id        --  任务id
	 *                    tag       --  任务种类
	 *                    ct        --  任务生成时间
	 *                    at        --  任务接受时间
	 *                    ut        --  更新时间
	 *                    s         --　任务状态，0为未接受未共享，1为接受，2为完成未领取奖励，3为分享给好友，4为完成后领取了奖励
	 *                    sc        --  记录任务来源，自己的任务为0,否则是分享来的母任务id，以防重复领取任务以及//计算奖励
	 *                    wc        --  记录希望该任务被分享的好友数
	 *                    ac        --  记录接受该分享任务的好友数
	 *                    gc        --  记录领取该分享任务的奖励份数             
	 */
	public function get_tasks ( $params )
	{
		$uid = $params['u'];
		$fid = $params['fid'];
		$tu = new TTUser($uid);
		$ret = array();
		$ftasks = $tu->get( $fid,TT::TASK_GROUP );
		foreach( $ftasks as $ftask ){
			if( $ftask['sc'] == '0' ){//好友通过领取别人分享得来的任务不被显示
				$ret['t'][] = $ftask;
			}
		}
		$ret['s'] = 'OK';
		return $ret;
	}

	/**
	 * 成为邻居
	 * @param $params
	 *  require  u           --  user id
	 *           nid         --  neighbor id
	 * @return 
	 *           s           --  OK
	 */
	public function dis_neighbor($params)
	{
		$uid = $params['u'];
		$nid = $params['nid'];
		$tu = new TTUser( $uid );
		$ftu = new TTUser( $nid);
		$mfdid = $ftu->getdid($uid,'fr');
		$fdid = $tu->getdid($nid,'fr');

		$fdata = $tu->getbyid($fdid);
		$mfdata = $ftu->getbyid($mfdid);
		if(!$fdata || !$mfdata){
			//$ret['s']='nofriend'; 
			//return $ret;
			$fdata['id']  = $fdid;
			$mfdata['id'] = $mfdid;
		}
		$fdata['neighbor'] = 'no';
		$mfdata['neighbor'] = 'no';
		$ftu->puto($mfdata,'fr',false);
		$tu->puto($fdata,'fr',false);
		$ret['s'] = 'OK';
		return $ret;
	}	

	/**
	 * 邀请成为邻居
	 * @param $params
	 *  require  u           --  user id
	 *           nid         --  neighbor id
	 *       or   npid         --  neighbor平台id
	 * @return 
	 *           s           --  OK
	 */
	public function invite_neighbor($params)
	{
		$uid = $params['u'];
		$nid = $params['nid'];
		if(!$nid){
			$npid = $params['npid'];
			$datas = TTGenid::genid(array('pid'=>$npid));
			$nid = $datas['id'];
		}


		$tu = new TTUser( $uid );
		$ftu = new TTUser( $nid);
		$mfdid = $ftu->getdid($uid,'fr');
		$fdid = $tu->getdid($nid,'fr');

		$fdata = $tu->getbyid($fdid);
		$mfdata = $ftu->getbyid($mfdid);
		if(!$fdata || !$mfdata){
			//$ret['s']='nofriend'; 
			//return $ret;
			$fdata['id']  = $fdid;
			$mfdata['id'] = $mfdid;
		}
		$fdata['neighbor'] = 'invite';
		$mfdata['neighbor'] = 'invite';
		$ftu->puto($mfdata,'fr',false);
		$tu->puto($fdata,'fr',false);
		$ret['s'] = 'OK';
		return $ret;
	}	

	/**
	 * 接受成为邻居
	 * @param $params
	 *  require  u           --  user id
	 *           nid         --  neighbor id
	 * @return 
	 *           s           --  OK
	 */
	public function accept_neighbor($params)
	{
		$uid = $params['u'];
		$nid = $params['nid'];


		$tu = new TTUser( $uid );
		$ftu = new TTUser( $nid);
		$mfdid = $ftu->getdid($uid,'fr');
		$fdid = $tu->getdid($nid,'fr');

		$fdata = $tu->getbyid($fdid);
		$mfdata = $ftu->getbyid($mfdid);
		if(!$fdata || !$mfdata){
			$ret['s']='nofriend'; 
			return $ret;
		}
		$fdata['neighbor'] = 'accept';
		$mfdata['neighbor'] = 'accept';
		$ftu->puto($mfdata,'fr',false);
		$tu->puto($fdata,'fr',false);
		$ret['s'] = 'OK';
		return $ret;
	}




	/**
	 * 更新好友列表并返回好友信息。若参数为空，则返回存储的好友信息。
	 * @param $params
	 *  require    u          --  userid
	 *             fids       --  好友平台id字符串，用逗号隔开
	 *             t       -- update ,强制调用平台方法获取好友
	 * @return
	 *             s          --  OK,others fail
	 *             infos      --  好友信息数组
	 *             u          --  好友内部id数组 ? why? infos already have the id
	 *             a          --  好友帐户信息数组 //why not use map ,dbid 
	 */
	public function get($params)
	{	
		$now = time();
		$uid = $params['u'];
		$upd = $params['t'];
		$tu = new TTUser( $uid );
		$fids = $params['fids'];
		$infos = $tu->get('fr',false);
		if( !$fids ){
			$fids = $tu->getf( TT::FRIEND_STAT );
			if(!$fids ||$upd=='update' ){//call from platform
				require_once WEB_ROOT.'platform_tools.php';
				$fids = get_friends($uid);
				if($fids)
					$tu->putf( TT::FRIEND_STAT ,$fids);
			}			
		}
		else{
			$tu->putf( TT::FRIEND_STAT ,$fids);
		}
		$fl = explode(',',$fids);
		$rinfos= array();
		$dup=array();
		$now = time();
		$dup['253382225']=1;
		$friend_count = 0;
		//记录好友个数
		foreach( $fl as $pid ){
			if($dup[$pid])
				continue;
			$dup[$pid]=1;
			$finfos = TTGenid::getbypid($pid); //by tingkun
			$id = $finfos['id'];
			if($id){
				$fdid = $tu->getdid($id,'fr');
				$fdata = json_decode( $infos[$fdid],true);
				//if(!$fdata ||  $fdata['ut']<$now - 3600){
				if(!$fdata ||  $fdata['ut']<$now - 3600){
					$ftu = new TTUser($finfos['id']);
					$acc = $ftu->getdata(array('money','exp','gem'));
					if(!$acc['exp'])
 						continue ;
					$acc['name'] = $finfos['name'];
					$acc['icon'] = $finfos['icon'];
					$acc['pid'] = $pid;
					$acc['ut']=$now;
					$acc['id']=$fdid;
					$acc['dbid']=$id;
					$tu->puto($acc);
					$rinfos[]=$acc;
				}else
					$rinfos[]=$fdata;
				unset($infos[$fdid]);
			}
			$friend_count++;            
			}
			if( $friend_count > $tu->getf('friend_count') ) {
				$tu->putf( 'friend_count',$friend_count );
			}
			//*
			$rinfos[]=array('name'=>'GM','icon'=>'http://hd45.xiaonei.com/photos/hd45/20080915/11/09/tiny_tDX1_3400c200150.jpg',
					'pid'=>'253382225','exp'=>'10000','dbid'=>2,'ht'=>$now,'help_car'=>1);//GM
			//*/
			$ret['infos'] = &$rinfos;
			$ret['s'] = 'OK';
			return $ret;
		}


		public function debug_get($params)
		{	
			$now = time();
			$uid = $params['u'];
			$tu = new TTUser( $uid );
			$fids = $params['fids'];
			$infos = $tu->get('fr',false);
			if( !$fids ){
				$fids = $tu->getf( TT::FRIEND_STAT );
				if(!$fids ){
					$fids = "quest01,quest02,quest03,quest04,quest05,quest06,quest07,quest08,quest09";
				}			
				/*
				   if($infos){
				   $ret['infos']=TTExtend::processlist($infos);
				   $ret['s'] = 'OK';
				   return $ret;
				   }
				 */
			}
			else{
				$tu->putf( TT::FRIEND_STAT ,$fids);
			}
			$fl = explode(',',$fids);
			$rinfos= array();
			$dup=array();
			$now = time();
			$dup['253382225']=1;
			//$fl[]='253382225';
			$friend_count = 0;
			//记录好友个数
			foreach( $fl as $pid ){
				if($dup[$pid])
					continue;
				$dup[$pid]=1;
				$finfos = TTGenid::getbypid($pid); //by tingkun
				$id = $finfos['id'];
				if($id){
					$fdid = $tu->getdid($id,'fr');
					$fdata = json_decode( $infos[$fdid],true);
					//if(!$fdata ||  $fdata['ut']<$now - 3600){
					if(!$fdata ||  $fdata['ut']<$now - 3600){
						$ftu = new TTUser($finfos['id']);
						$acc = $ftu->getdata();
						$acc['name'] = $finfos['name'];
						$acc['icon'] = $finfos['icon'];
						$acc['pid'] = $pid;
						$acc['ut']=$now;
						$acc['id']=$fdid;
						$acc['dbid']=$id;
						$tu->puto($acc);
						$rinfos[]=$acc;
					}else
						$rinfos[]=$fdata;
					unset($infos[$fdid]);
				}
					else{
						$ret['notget'][]=$pid;
					}
					//$rids = array_keys($infos);
					//$tu->remove($rids);
					$friend_count++;            
				}
				if( $friend_count > $tu->getf('friend_count') ) {
					$tu->putf( 'friend_count',$friend_count );
				}
				//*
				$rinfos[]=array('name'=>'GM','icon'=>'http://hdn.xnimg.cn/photos/hdn121/20100807/1345/h_tiny_WtRB_190e0000358b2f75.jpg',
						'pid'=>'253382225','exp'=>'10000','dbid'=>2,'ht'=>$now,'help_car'=>1);//GM
				//*/


				$ret['infos'] = $rinfos;
				$ret['fids'] = $fids;
				$ret['s'] = 'OK';
				return $ret;
			}

			/**
			 * 拜访
			 * @param $params
			 *  require  u           --  user id
			 *           f         --  friend id
			 * @return 
			 *           s           --  OK
			 --  visited,已经拜访过
			 --  nofriend,不是朋友
			 award        
			 exp         --奖励经验
			 money       --奖励金钱 
			 */
			public function visit($params)
			{
				$uid = $params['u'];
				$nid = $params['f'];

				$tu = new TTUser( $uid );
				$ftu = new TTUser( $nid);
				$fdid = $tu->getdid($nid,'fr');

				$now = time();
				$now_date = date('Ymd',$now);
				$fdata = $tu->getbyid($fdid);
				if(!$fdata ){
					$ret['s']='nofriend'; 
					return $ret;
				}

				$vt = $fdata['vt'];
				$vt_date = date('Ymd',$vt);
				if($vt_date == $now_date){
					$ret['s']='visited';
					return $ret;
				}

				/*
				   $flevel = $ftu->getLevel();
				   $exp = 1 + $flevel*4;
				   $money = 50 + $flevel*50;
				   $ret['money'] = $tu->chMoney($money);
				   $ret['exp']  = $tu->addExp($exp);

				   $ret['award']['money']=$money;
				   $ret['award']['exp']=$exp;
				 */       
				$fdata['vt']=$now;
				$tu->puto($fdata,'fr',false);
				$ret['s'] = 'OK';
				return $ret;
			}
			/**
			  五，增加好友箱数：						

			  说明：	货车出发之后，放进仓库之前，好友均可点击，点击会增加货物箱数。				
			  按钮问题：好友帮助加箱数的按钮，在货车运货过程中和货车回来都有。而当货车回来，但是没有放进仓库时，只有到好友家才有按钮，自己的副驾驶功能消失。				
			  限制：	每人每日可帮一个好友一次。				
			  级别越高增加的箱数越多。					
			  级别：	箱数：				
			  1	1				
			  20	2				
			  40	3				
			  被帮助者奖励：	礼物为一箱货物。				
			  帮助者奖励：	获得所进货物经验（进货+取货的经验和）相等的经验。	
			 * @param $params
			 *  require  u           --  user id
			 *           f         --  friend id
			 *           cid         --  car id
			 * @return 
			 *           s           --  OK
			 --  helped,已经拜访过
			 --  nofriend,不是朋友
			 award        
			 exp         --奖励经验
			 money       --奖励金钱 

			 */
			public function help_car($params)
			{
				$uid = $params['u'];
				$nid = $params['f'];
				$cid = $params['cid'];


				$tu = new TTUser( $uid );
				$ftu = new TTUser( $nid);
				$fdid = $tu->getdid($nid,'fr');

				$now = time();
				$now_date = date('Ymd',$now);
				$fdata = $tu->getbyid($fdid);
				if(!$fdata ){
					$ret['s']='nofriend'; 
					return $ret;
				}

				$vt = $fdata['ht'];
				$vt_date = date('Ymd',$vt);
				if($vt_date == $now_date && $fdata['help_car']=='1'){
					$ret['s']='helped';
					return $ret;//for test
				}

				$car = $ftu->getbyid($cid);	
				if(!$car){

					$ret['s']='nocar '.$cid;
					return $ret;
				}
				if(count($car['help'])>5){
					$ret['s']='max';
					return $ret;
				}
				$goodsid = $car['goodsTag'];
				$gconfig = ItemConfig::getItem($goodsid);
				$add_exp = $gconfig['exp'];
				if(!$add_exp){
					$ret['s']='nogoods';
					$ret['g']=$gconfig;
					return $ret;
				}
				$level = $tu->getLevel();
				$num = 1;
				if($level >19) 
					$num = 2;
				if($level >39) 
					$num = 3;
				//$mydata = TTGenid::getbyid($uid); 
				$car['help'][$uid] =  $num;
				$add_exp *= 4;
				$ret['exp']  = $tu->addExp($add_exp);
				$ret['award']['exp'] = $add_exp;
				$fdata['ht'] = $now;
				$fdata['help_car'] = 1;
				$tu->puto($fdata,'fr',false);
				$ftu->puto($car,'',false);
				$ret['cid']=$cid;
				$ret['s'] = 'OK';
				$ret['t'] = $now;
				return $ret;
			}
		}

