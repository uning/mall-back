<?php
class TaskOnce
{
	static function getConf($tag)
	{
		//获取详细的配置	
		return array(
				'award'=>array('money'=>2,'exp'=>2,'items'=>array('tag'=>'71110','idp'=>'o')),
				'minus'=>array('money'=>1,'exp'=>1),
			    );
	}    

	/**
	 * 获取任务
	 * @param $params
	 *  require    u      --  userid
	 * @return
	 *   s                --  OK,others fail
	 *   t   tasks        --        任务
	 *                    tag       --  任务种类
	 *                    at        --  任务接受时间
	 *                    ut        --  更新时间
	 *                    s         --　任务状态
                                            accept
                                            finish
			      others fields
			    
	 */
	public function get( $params )
	{
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		$ret['t'] = $tu->get('to');
		$ret['s'] = 'OK';
		return $ret;
	}    
    

	/**
	 * 接受任务
	 *   
	 * @param $params
	 *  require   u       --  uid
	 *            tag     --  任务id
	 * @return 
	 *            s       --  OK，正常   
	 *			  finished,
	 *                        repeat，重复领取任务
	 *                        
	 *            at      --  接受任务的时间
	 */
	public function accept( $params )
	{
		$uid = $params['u'];
		$tag = $params['tag'];

		//判断用户是否正在任务中
		$tu = new TTUser( $uid );
		$tid = $tu->getoid($tag,'to');
		$to = $tu->getbyid($tid);
		if($to){
			$ret['s']='repeat';
			return $ret;
		}
		$to['id']=$tid;
		$to['tag']=$tag;
		$to['s']='accept';
		$to['at']=time();
       
		$tu->put($to);
		$ret['s'] = 'OK';
		return $ret;
	}    

	/**
	 * 更新任务完成状态
	 *   
	 * @param $params
	 *  require   u       --  uid
	 *            tag     --  任务id 
         *            d       --  map of data
	 * @return 
	 *            s       --  OK，正常
	 *                        no，该任务不存在
	 *                        finished,未接受任务，不能更新状态
	 */
	public function update( $params )
	{
		$uid = $params['u'];
		$tag = $params['tag'];

		//判断用户是否正在任务中
		$tu = new TTUser( $uid );
		$tid = $tu->getoid($tag,'to');
		$to = $tu->getbyid($tid);
		if(!$to){
			$ret['s']='no';
			return $ret;
		}
		if($to['s']=='finished'){
			$ret['s']='finished';
			return $ret;
		}
       
		$data=$params['d'];
		
		$data['id']=$tid;
		$data['ut']=time();
		$tu->put($data);
		$ret['s'] = 'OK';
		return $ret;
	}



	/**
	 * 完成任务
	 *   
	 * @param $params
	 *  require   u       --  uid
	 *            tag     --  任务id
	 *            rids     -- 消耗物品ids（只能是空闲物品）
	 * @return 
	 *            s       --  OK，正常
	 *            award    --  和新手等一致
	 *                        finished，该任务不存在
	 */
	public function finish( $params )
	{
		$uid = $params['u'];
		$tag = $params['tag'];

		//判断用户是否正在任务中
		$tu = new TTUser( $uid );
		$tid = $tu->getoid($tag,'to');
		$to = $tu->getbyid($tid);
		if(!$to){
			$ret['s']='no';
			return $ret;
		}
		if($to['s']=='finished'){
			$ret['s']='finished';
			return $ret;
		}
		$conf = self::getConf($tag);
		
		foreach($conf['award'] as $k=>$v){
			$tu->numch($k,$v);
			$award[$k]=$v;
		}
		foreach($conf['minus'] as $k=>$v){
			$tu->numch($k,-$v);
		}
		$items = $conf['items'];
		if($items){
			foreach($items as $item){
				$idp = $item['idp'];
				//					$item['id']=$uid.$idp;
				unset( $item['idp'] );
				$item['pos'] = 's';
				$item['id'] = $tu->getoid('',$idp);
				$tu->puto( $item);
				$award['items'][]=$item;
			}
		}
		$rids = $params['rids'];
		if($rids)
                    $tu->remove($rids);
		$ret['award'] = $award;
		$data['id']=$tid;
		$data['s']='finished';
		$data['ut']=time();
		$tu->put($data);
		$ret['s'] = 'OK';
		return $ret;
	}    

}
