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
	 * @return
	 *             s          --  OK,others fail
	 *             infos      --  好友信息数组
	 *             u          --  好友内部id数组 ? why? infos already have the id
	 *             a          --  好友帐户信息数组 //why not use map ,dbid 
	 */
	public function get($params)
	{	
		//暂时只返回10个好友测试
		//todo:
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
			if($infos){
				$ret['infos']=TTExtend::processlist($infos);
				$ret['s'] = 'OK';
				return $ret;
			}
		}
		else{
			$tu->putf( TT::FRIEND_STAT ,$fids);
		}
		$fids .= ",quest002,quest001,sr009";//for test
		$fl = explode(",",$fids);
		$rinfos= array();
		$dup=array();
		foreach( $fl as $pid ){
			if($dup[$pid])
				continue;
			$dup[$pid]=1;
			$finfos = TTGenid::getbypid($pid); //by tingkun
			$id = $finfos['id'];
			if($id){
				$fdid = $tu->getdid($id,'fr');
				$fdata = json_decode( $infos[$fdid],true);
				if(!$fdata ||  $fdata['ut']<$now - 3600){
					$ftu = new TTUser($finfos['id']);
					$acc = $ftu->getdata();
					if($fdata)
						$fdata = @array_merge($fdata,$acc);
					$acc['name'] = $finfos['name'];
					$acc['icon'] = $finfos['icon'];
					$acc['pid'] = $pid;
					$acc['ut']=$now;
					$acc['id']=$fdid;
					$acc['dbid']=$id;
					$tu->puto($acc,'fr',false);
					$rinfos[]=$acc;
				}else
					$rinfos[]=$fdata;
				unset($infos[$fdid]);
			}
			$rids = array_keys($infos);
                        $tu->remove($rids);
		}
		$ret['infos'] = &$rinfos;
		$ret['s'] = 'OK';
		return $ret;
	}


}

