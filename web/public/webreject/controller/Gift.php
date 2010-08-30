<?php
class Gift{
	/**
	 * 发送一个礼物
	 * @param $params
	 *  require u  -- user id
	 *  require fid  -- friend id
	 *  require gid  -- gift id
	 *  require msg  -- msg
	 *  optional  gtag  -- gift tag
	 *  optional  fname  -- myname
	 *  optional  fpic  -- myname
	 *
	 * @return 
	 *  s   -- OK ,or other fail
	 *  d  -- the man obj
	 step=>(vtime,dtime)
	 */
	public function send($params)
	{
		$uid = $params['u'];
		$fid = $params['fid'];
		$gid = $params['gid'];
		if(!$uid || !$gid || !$fid){
			$ret['s'] = 'param';
			return $ret;
		}
		$tu = new TTUser($uid);
		$gift_obj = $tu->getbyid($gid);
		if(!$gift_obj){
			$ret['s'] = 'nothave';
			return $ret;
		}
		$item = ItemConfig::getItem( $gift_obj['tag'] );
		if( !$item || $item['can_gift'] != 'true' ){
		    $ret['s'] = 'cantgift';
		    return $ret;
		}
		$tu->remove($gid);
		$ftu = new TTUser($fid);
		$id = $ftu->getdid(null,TT::GIFT_GROUP);
		$obj['gtag'] = $gift_obj['tag'];
		$obj['id']=$id;
		$obj['fid'] = $uid;
		if( $params['msg'] )
		    $obj['msg'] = $params['msg'];
		$ftu->puto( $obj );
		$stat = array();
		$stat['tag'] = $gift_obj['tag'];
		TTLog::record(array('m'=>__METHOD__,'tm'=> $_SERVER['REQUEST_TIME'],'p'=>json_encode($stat)));
		$ret['s'] = 'OK';
		return $ret;
	}


	/**
	 * 查看礼物
	 * @param $params
	 *  require u  -- user id
	 * @return 
	 *  s   -- OK ,or other fail
	 *  d  -- the man obj
	 array(array('id',u,fid,gid,fname,fpic))
	 */
	public function view($params)
	{
		$uid = $params['u'];
		$tu = new TTUser($uid);
		$ret['d'] = $tu->get(TT::GIFT_GROUP);
		$ret['s'] = 'OK';
		return $ret;
	}

	/**
	 * 收取礼物
	 * @param $params
	 *  require         u        -- user id
	 *                  gids      -- gift ids
	 * @return 
	 *  s       -- OK ,or other fail
	 *  id2id   -- 新旧ids映射
	 */
	public function accept($params)
	{
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		$gifts = $params['gids'];
		foreach($gifts as $gid){
			$d      = $tu->getbyid($gid);
			$oid = $d['id'];
			if($d){
				$tag=$d['gtag'];
				if($tag)
					$d['tag'] = $tag;
				else
                                    $tag = $d['tag'];
				$conf = ItemConfig::getItem($tag);
				if(!$conf)
					continue;	
				$g=$conf['group'];
				if(!$g)
					$g = TT::ITEM_GROUP;
				$id = $tu->getdid(null,$g);
				$id2id[$gid] = $id;
				$d['id']=$id;
				$tu->puto($d);
			}else
				$id2id[$gid] = null;

			$rids[]=$oid;

		}
		$tu->remove($rids);
		$ret['s'] = 'OK';
		$ret['id2id'] = $id2id;
		return $ret;
	}
}
