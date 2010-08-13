<?php
class TaskController
{	
	/**
	 * 分享任务给所有好友
	 *   
	 * @param $params
	 *  require   u       --  uid
	 *            tid     --  任务id
	 * @return 
	 *            s       --  OK，正常
	 *                        notexsit，该任务不存在
	 *                        cantshare,状态不为0，不能共享
	 *                        
	 *            tid     --  task id 
	 */
	public function share( $params )
	{
		$uid = $params['u'];
		$tid = $params['tid'];
		$tu = new TTUser( $uid );
		$task_obj = $tu->getbyid( $tid);

		if( !$task_obj ){
			$ret['s'] = 'notexsit';
			return $ret;
		}
		if( $task_obj['s'] != '0' ){
			$ret['s'] = 'cantshare';
			return $ret;
		}
		//确定去好友家没有分享按钮
		$task_obj['s'] = 3;
		$id = $tu->puto( $task_obj,TT::TASK_GROUP );
		$ret['tid'] = $id;
		$ret['s'] = 'OK';
		return $ret;
	}


	/**
	 * 请求好友的任务
	 *   
	 * @param $params
	 *  require   u              --  uid
	 *            tid            --  任务id
	 *            fid            --  friend id
	 * @return 
	 *            s              --  OK，正常
	 *                               notexsit，该任务不存在
	 *                               cantrequest，状态不为0，不能请求
	 *                               notowner,不是好友自己的任务，不能请求
	 *            wc             --  希望被分享的人数
	 */
	public function request( $params )
	{
		$uid = $params['u'];
		$fid = $params['fid'];
		$tid = $params['tid'];

		if( !$fid || $fid == '0' ){
			$ret['s'] = 'fiderror';
			return $ret;
		}
		if( !$ftask_obj ){
			$ret['s'] = 'notexsit';
			return $ret;
		}
		//$tu  = new TTUser( $uid );
		$ftu =  new TTUser($fid);
		$ftask_obj = $ftu->getbyid( $tid);

		if( $ftask_obj['s'] != '0' ){
			$ret['s'] = 'cantrequest';
			return $ret;
		}
		if( $ftask_obj['sc'] != '0' ){//不是好友自己的任务，不能请求
			$ret['s'] = 'notowner';
			return $ret;
		}

		$ftask_obj['wc'] += 1;
		$id = $ftu->puto($ftask_obj,TT::TASK_GROUP);
		$ret['tid'] = $id;		
		$ret['s'] = 'OK';
		$ret['wc'] = $ftask_obj['wc'];
		return $ret;
	}	

	/**
	 * 接受任务
	 *   
	 * @param $params
	 *  require   u       --  uid
	 *            tid     --  任务id
	 *  option    fid     --  freind id　接受好友分享的任务有此参数，否则不需要此参数
	 * @return 
	 *            s       --  OK，正常   
	 *                        repeat，重复领取任务
	 *                        notexsit，该任务不存在
	 *                        notowner，是领取到的分享的任务，并非每天产生的3个任务
	 *                        notshare，好友未共享该任务
	 *                        cantaccept，状态不为0，不能接受
	 *                        locked，不能同时进行多项任务
	 *                        
	 *            at      --  接受任务的时间
	 *            id      --  任务id，可能有变(接受好友的)
	 *            all,lock--  测试用，稳定后删除
	 */
	public function accept( $params )
	{
		$uid = $params['u'];
		$fid = $params['fid'];
		$tid = $params['tid'];

		//判断用户是否正在任务中
		$tu = new TTUser( $uid );

		$lock = $tu->getf( TT::TASK_STAT );
		$ret['lock_status'] = $lock;
		if( $lock == '0' ){//任务进行中
			$ret['s'] = "locked";
			return $ret;
		}


		if( $fid ){//接受好友的任务
			$ftu =  new TTUser($fid);
			$ftask_obj = $ftu->getbyid($tid);
			if( !$ftask_obj ){
				$ret['s'] = 'notexsit';
				return $ret;
			}
			/*		    
					    $today_start = strtotime (date( 'Y-m-d',time() ) );		
					    if( $ftask_obj['ct'] < $today_start ){
					    $ret['s'] = 'needfriendupdate';
					    return $ret;
					    }
			 */
			if( $ftask_obj['sc'] != '0' ){//不是好友自己的任务，是分享来的
				$ret['s'] = 'notowner';
				return $ret;
			}

			if( $ftask_obj['s'] != '3' ) {
				$ret['s'] = 'notshare';
				return $ret;
			}		    

			//遍历自己的任务列表，以免重复领取任务

			$tasks = $tu->get( TT::TASK_GROUP );
			foreach( $tasks as $task ){
				if( $task['sc'] == $tid ){
					$ret['s'] = 'repeat';
					return $ret;		        
				}
			}

			$task_obj = $ftask_obj;
			$ftask_obj['ac'] += 1;
			$ftu>puto( $ftask_obj ,TT::TASK_GROUP);
			//新任务需改为从tag生成		    
			unset($task_obj['id']);
			$task_obj['s'] = '1';
			$task_obj['at'] = time();
			$task_obj['sc'] = $tid;

			$id = $tu->puto( $task_obj,TT::TASK_GROUP);//返回新的任务id
			$ret['id'] = $id;
			$ret['at'] = $task_obj['at'];

		}
		else{//接受自己的任务
			$task_obj = $tu->getbyid($tid);
			if( !$task_obj ){
				$ret['s'] = 'notexsit';
				return $ret;
			}

			if( $task_obj['sc'] != '0' ){//不是自己产生的任务
				$ret['s'] = 'notowner';
				return $ret;
			}    
			if( $task_obj['s'] != '0' ){
				$ret['s'] = 'cantaccept';
				return $ret;
			}

			$task_obj['s'] = '1';
			$task_obj['at'] = time();
			$id = $tu->puto($task_obj,TT::TASK_GROUP);		    
			$ret['id'] = $id;
			$ret['at'] = $task_obj['at'];
		}
		//上锁
		$tu->putf( TT::TASK_STAT,'0' );
		$ret['s'] = 'OK';
		return $ret;
	}    

	/**
	 * 更新任务完成状态
	 *   
	 * @param $params
	 *  require   u       --  uid
	 *            tid     --  任务id 
	 *            cid     --  特殊 customer 在 task配置goal字段的序号
	 *            gid     --  即将销售的货物id
	 * @return 
	 *            s       --  OK，正常
	 *                        notexsit，该任务不存在
	 *                        cantupdate,未接受任务，不能更新状态
	 *                        goodsnotexsit，该货物不存在
	 */
	public function update( $params )
	{
		$uid = $params['u'];
		$gid = $params['gid'];
		$tid = $params['tid'];
		$cid = $params['cid'];
		$tu = new TTUser( $uid );

		$task_obj = $tu->getbyid($tid);
		if( !$task_obj ){
			$ret['s'] = 'notexsit';
			return $ret;
		}
		if( $task_obj['s'] != '1' ){
			$ret['s'] = 'cantupdate';
			return $ret;         
		}
		//对卖货物的任务，还需验证goods是否存在
		$goods = $tu->getbyid($gid);
		if( !$goods ){
			$ret['s'] = 'goodsnotexsit';
			return $ret;
		}		    
		//检验是否过期

		$task_obj['proc'][$cid][$goods['tag']] = 1;		
		$task_obj['ut'] = time();
		$id = $tu->puto( $task_obj,TT::TASK_GROUP);
		$ret['id'] = $id;
		$ret['ut'] = $task_obj['ut'];
		$ret['s'] = 'OK';
		return $ret;
	}



	/**
	 * 完成任务
	 *   
	 * @param $params
	 *  require   u       --  uid
	 *            tid     --  任务id
	 * @return 
	 *            s       --  OK，正常
	 *                        notexsit，该任务不存在
	 *                        cantfinish，未接受该任务，不能完成
	 */
	public function finish( $params )
	{
		$uid = $params['u'];
		$fid = $params['fid'];
		$tid = $params['tid'];
		$tu = new TTUser( $uid );
		$task_obj = $tu->getbyid($tid);
		if( !$task_obj ){
			$ret['s'] = 'notexsit';
			return $ret;
		}

		if( $task_obj['s'] != '1' ){
			$ret['s'] = 'cantfinish';
			return $ret;
		}

		$task_obj['s'] = '2';
		//	    $task_obj['at'] = time();
		$id = $tu->puto( $task_obj ,TT::TASK_GROUP);

		$tu->putf( TT::TASK_STAT,'1' );
		$ret['id'] = $id;
		$ret['s'] = 'OK';
		return $ret;
	}    

	/**
	 * 领取任务奖励
	 *   
	 * @param $params
	 *  require   u                 --    uid
	 *            tid               --    任务id
	 * @return 
	 *            s                 --　　 OK，正常
	 *                                    notexsit，该任务不存在
	 *                                    cantgetaward，未完成的任务，不能领取奖励
	 *            tid               --    task id
	 */
	public function get_award( $params )
	{
		$uid = $params['u'];
		$tid = $params['tid'];

		//判断用户是否正在任务中
		$tu = new TTUser( $uid );

		$task_obj = $tu->getbyid($tid);
		//        $ret['task_obj'] = $task_obj;
		if( !$task_obj ){
			$ret['s'] = 'notexsit';
			return $ret;
		}

		if( $task_obj['s'] != '2' ){
			$ret['s'] = 'cantgetaward';
			return $ret;
		}
		//两种奖励，分别根据$task_obj['ac'] (分享的奖励) 结算


		$today_start = strtotime (date( 'Y-m-d',time() ) );	
		if( $task_obj['ct'] < $today_start ){//如果是昨天的任务，领取奖励后此条记录删除
			$tu->remove($tid);
		}
		else{
			$task_obj['s'] = '4';
			$tu->puto($task_obj,TT::TASK_GROUP);
		}
		$ret['id'] = $tid;
		$ret['s'] = 'OK';
		return $ret;
	}
}
