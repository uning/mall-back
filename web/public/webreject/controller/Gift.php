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
		$id = $ftu->getdid('',TT::GIFT_GROUP);
		$obj['gtag'] = $gift_obj['tag'];
		$obj['id']=$id;
		$obj['fid'] = $uid;
		if( $params['msg'] )
		    $obj['msg'] = $params['msg'];
		$ftu->puto( $obj );
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
	 *                  gid      -- gift id
	 *                  fid      -- 送礼人 id
	 * @return 
	 *  s   -- OK ,or other fail
	 */
	public function accept($params)
	{
		$uid = $params['u'];
        $fid = $params['fid'];
        $gid = $params['gid'];	
		$tu = new TTUser( $uid );
		$gift_obj = $tu->getbyid( $gid );
		if( !$gift_obj ){
		    $ret['s'] = 'notexsit';
            return $ret;			
		}
		$tag = $gift_obj['gtag'];
		$item = ItemConfig::getItem( $tag );
		if( !$item ){
		    $ret['s'] = 'itemnotexsit';
			return $ret;
		}
		$obj['pos'] = 's';
		$obj['tag'] = $tag;
		if( $item['group'] == 'o' ){
		    $tu->puto( $obj,TT::ITEM_GROUP );
		}
		if( $item['group'] == 'g' ){
		    $tu->puto( $obj,TT::GOODS_GROUP );
		}
		$tu->remove( $gid );
		$ret['s'] = 'OK';
/*		
		$gifts = $params['d'];
		$datas = $tu->get(TT::GIFT_GROUP,false);
		foreach($gifts as $d){
			$oid = $d['id'];
			if($datas[$oid]){
				$tag=$d['tag'];
				$conf = ItemConfig::getItem($tag);
				if(!$conf)
					continue;	
				$g=$conf['group'];
				if(!$g)
					$g = TT::ITEM_GROUP;
				$id = $tu->getdid('',$g);
				$id2id[$oid] = $id;
				$d['id']=$id;
				$tu->puto($d);
			}
			$rids[]=$oid;

		}
		$tu->remove($rids);
		$ret['s'] = 'OK';
		$ret['d'] = $id2id;
*/		
		return $ret;
	}
}