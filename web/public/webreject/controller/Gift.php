<?php
class Gift{
	/**
	 * 发送一个礼物
	 * @param $params
	 *  require u  -- user id
	 *  require fid  -- friend id
	 *  require gtag  -- gift id
	 *  require gid  -- gift id
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
		if(!$uid || !$gid || $fid){
			$ret['s'] = 'param';
			return $ret;
		}
		$tu = new TTUser($uid);
		$r = $tu->getbyid($gid);
		if(!$r){
			$ret['s'] = 'nothave';
			return $ret;
		}
		$tu->remove($gid);
		$params['gtag']=$r['tag'];
		$ftu = new TTUser($fid);
		$id = $ftu->getdid('',TT::GIFT_GROUP);
		$params['id']=$id;
		$ftu->puto($params);
		$ret['s'] = 'OK';
		return $ret;
	}

	/**
	 * 获得一个礼物
	 * @param $params
	 *  require u  -- user id
	 *  require fid  -- friend id
	 *  require gtag  -- gift type
	 *  optional  fname  -- friendname
	 *  optional  fpic  -- friendname
	 *  optional  gid  -- gift 对应rcord id
	 *
	 * @return 
	 *  s   -- OK ,or other fail
	 *  d  -- the man obj
	 step=>(vtime,dtime)
	 */
	public function get($params)
	{
		$uid = $params['u'];
		$fid = $params['fid'];
		$gid = $params['gtag'];
		if(!$uid || !$gid || !$fid){
			$ret['s'] = 'param';
			return $ret;
		}
		$tu = new TTUser($uid);
		$id = $tu->getdid('',TT::GIFT_GROUP);
		$params['id']=$id;
		$tu->puto($params);
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
	 *  require u  -- user id
	 *  require d  -- 礼物数据(数组)
	 array(array('id',tag,otherdata));//前端按记录准备好的数据
	 id   礼物对应record id

	 * @return 
	 *  s   -- OK ,or other fail
	 *  d  -- map of old id to new ids; 

	 */
	public function accept($params)
	{
		$uid = $params['u'];
		$tu = new TTUser($uid);
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
		return $ret;
	}
	
	

		/**
		 * 赠送礼物，礼物只能是装饰品？
		 * @param $params
		 *   required         fid       -- 受礼人id
		 *                    msg       -- 送礼人的留言
		 *   option           gid       -- 礼物id，赠送已有物品时传此参数，
		 *                    gtag      -- 新买物品的xml id，赠送新买物品时传此参数
		 * @return 
		 *                    s         --  OK
		 *                    rid       --  记录id
		 */

		public function send_gift ( $fid,$msg,$gid,$gtag )
		{
			if( !$fid ){
				$ret['s'] = 'friendnull';
				return $ret;
			}

			if( !$gid ){//赠送已有物品
				//验证送礼人是否有此物品
				$gift_obj = $this->getbyid( $gid );
				if( !$gift_obj ){
					$ret['s'] = 'giftnotexsit';
					return $ret;
				}         
				//从送礼人的物品列表中除去此礼物
				$this->remove( $gift_obj['id'] );
				$tag = $gift_obj['tag'];
			}

			if( !$gtag ){//赠送新买物品
				$item = ItemConfig::getItem( $gtag );
				if( !$item ){
					$ret['s'] = 'itemnotexsit';
					return $ret;
				}
				if( !$item['can_gift'] ){
					$ret['s'] = 'cantgift';
					return $ret;
				}
				$buy_ret = self::buyItem( $gtag );
				if( $buy_ret['s'] != 'OK' )
					return $buy_ret;
				//新买物品的作为受礼人的记录存储
				$tag = $gtag;
			}
			$data['user_id'] = $fid;
			$data['donor_id'] = $this->_u;
			$data['message'] = $msg;
			$data['tag'] = $tag;
			$data['done'] = 0;//未被好友领取
			//        $data['gift_at'] = date( TM_FORMAT,$now );        

			$fu = new TTUser( $fid );
			$fu->puto( $data,TT::GIFT_GROUP );

			$ret['s'] = 'OK';
			return $ret;
		}


		/**
		 * 接受礼物赠送
		 * @param $params
		 *   require          fid             --   送礼人id
		 *                    gid             --   礼物id
		 * @return 
		 *                    s               --   OK
		 *                    donor_id        --   gift object
		 *                    message         --   留言
		 *                    tag             --   礼物种类
		 */

		public function accept_gift ( $fid,$gid,$msg )
		{
			$gift_obj = $this->getbyid( $gid );
			if( !$gift_obj ){
				$ret['s'] = 'notexsit';
				return $ret;
			}
			if( !$gift_obj['user_id'] != $this->_u ){
				$ret['s'] = 'notforyou';
				return $ret;
			}
			if( !$gift_obj['done'] != '0' ){
				$ret['s'] = 'again';
				return $ret;
			}

			$gift_obj['done'] = '1';
			$this->remove( $gift_obj['id'] );

			//接受的装饰器放在仓库？
			$item_obj ['tag'] = $gift_obj['tag'];
			$this->puto( $item_obj,TT::ITEM_GROUP );

			$ret['s'] = 'OK';
			$ret['g'] = $gift_obj;
			return $ret;
		}
}
