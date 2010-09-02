<?php
class TaskOnce
{
	static function getConf($tag)
	{
		static $_conf=array(
'101'=>array('name'=>'扩建大楼','award'=>array('exp'=>200),) ,
'102'=>array('name'=>'让世界更美丽','award'=>array(),'items'=>array(array('tag'=>71103,'num'=>3)) ) ,
'103'=>array('name'=>'吸引顾客','award'=>array(),'items'=>array(array('tag'=>60101,'num'=>1)) ) ,
'104'=>array('name'=>'琳琅满目','award'=>array('money'=>20000,),) ,
'105'=>array('name'=>'老鼠来袭','award'=>array(),'items'=>array(array('tag'=>92703,'num'=>1)) ) ,
'106'=>array('name'=>'电梯恐惧症','award'=>array(),'items'=>array(array('tag'=>71108,'num'=>1)) ) ,
'107'=>array('name'=>'走累了。。。','award'=>array('money'=>35000,),) ,
'201'=>array('name'=>'声名鹊起','award'=>array('exp'=>380),) ,
'202'=>array('name'=>'助人为乐','award'=>array('exp'=>500),) ,
'203'=>array('name'=>'免费礼物','award'=>array(),'items'=>array(array('tag'=>10105,'num'=>1)) ) ,
'108'=>array('name'=>'课外活动','award'=>array(),'items'=>array(array('tag'=>92702,'num'=>1)) ) ,
'109'=>array('name'=>'推广员','award'=>array(),'items'=>array(array('tag'=>60103,'num'=>1)) ) ,
'204'=>array('name'=>'送货上门','award'=>array(),'items'=>array(array('tag'=>10115,'num'=>3)) ) ,
'205'=>array('name'=>'健身教练','award'=>array('exp'=>800),) ,
'206'=>array('name'=>'护花使者','award'=>array(),'items'=>array(array('tag'=>10109,'num'=>3)) ) ,
'110'=>array('name'=>'坚持不懈','award'=>array('money'=>60000,),) ,
'111'=>array('name'=>'小有所成','award'=>array(),) ,
'112'=>array('name'=>'保护环境','award'=>array(),) ,

				);
		//获取详细的配置	
		return $_conf[$tag];
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
       
		$tu->puto($to);
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
			//return $ret;
			$to['id']=$tid;
		}
		if($to['s']=='finished'){
			$ret['s']='finished';
			return $ret;
		}
       
		$data=$params['d'];
		$data['id']=$tid;
		$data['ut']=time();
		$tu->puto($data);
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
	 *            minus  (money=>11,gem=>122)
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
		//	$ret['s']='no';
		//	return $ret;
			$to['id']=$tid;
		}
		if($to['s']=='finished'){
			$ret['s']='finished';
			return $ret;
		}
		$conf = self::getConf($tag);
		
		$d = $conf['award'];
		if($d)
		foreach($d as $k=>$v){
			$tu->numch($k,$v);
			$award[$k]=$v;
		}
		$d = $params['minus'];
		if($d)
		foreach($d as $k=>$v){
			$tu->numch($k,-$v);
		}
		$items = $conf['items'];
		if($items){
			foreach($items as $item){
				$num  = $item['num'];
				unset( $item['num'] );
				$iconf = ItemConfig::getItem($item['tag']); 
				$g=$iconf['group'];
				if(!$g)
				  $g = 'o';
				if(!$num)
				 $num = 1;
				for($i=0;$i<$num; $i++){
					$item['pos'] = 's';
					$item['id'] = $tu->getoid(null,$g);
					$tu->puto( $item);
					$award['items'][]=$item;
				}
			}
		}
		$rids = $params['rids'];
		if($rids)
                    $tu->remove($rids);
		$ret['award'] = $award;
		$to['id']=$tid;
		$to['s']='finished';
		$to['ut']=time();
		$tu->puto($to);
		$ret['s'] = 'OK';
		return $ret;
	}    

}
