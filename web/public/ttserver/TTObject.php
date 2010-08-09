<?php

/**
 * 
 * 利用id 前缀对key聚合查询
 * 底层为btree key-value数据库
 * 使用json-编码后存储object
 * 利用uid分库
 * @author uning
 *
 */
class TTObject{
	public   $name = 'main';//for tt
	public   $type = 'master';


	/**
	 * true  oldid,
	 * false newid
	 * @param $data
	 * @param $uid
	 * @param $item_group
	 * @param $t
	 * @return unknown_type
	 */
	protected function checkid(&$data,&$uid=null,&$item_group='',&$t=null)
	{
		$id =&$data['id'];
		if($id && !$uid){
			$idata = explode(':',$id,4);
			if($idata[0]>0){
				$uid = $idata[0];
			}
		}
		if($item_group==null){
			$item_group = $this->default_group;
		}

		if(!$uid){
			throw new Exception("no uid set");
		}
		if($t==null){
			$t = TT::get_tt($this->name,$uid,$this->type);
		}
		if($id){
			return true;
		}
		$id = uniqid($uid.':'.$item_group.':');
		return false;

	}
	/**
	 * 保存单条物品，返回id
	 * @param $data
	 * @return unknown_type
	 */
	public function  put(&$data,$uid=null,$item_group='',&$t=null)
	{
		$update = $this->checkid($data,$uid,$item_group,$t);
		$id  = &$data['id'];
		if($update){
			$odata = json_decode($t->get($id),true);
			foreach($data as $k=>$v){
				$odata[$k]=$v;
			}
			$t->put($id,json_encode($odata));
		}else{
			$t->put($id,json_encode($data));
		}
		return $id;
	}

	/**
	 * 保存多条物品，返回ids
	 * todo:modify 
	 * @param $data
	 * @return unknown_type
	 */
	public function  mput(&$datas,$uid=null,$item_group='',&$t=null)
	{
		foreach($datas as $d){
			$ret[]= $this->put($d,$uid,$item_group,$t);
		}
		return $ret;
	}


	public function update(&$data,&$t=null)
	{
		$id = $data['id'];
		if($t==null){
			$idata = explode(':',$id,4);
			if($idata[0]>0){
				$uid = $idata[0];
				$t=TT::get_tt($this->name,$uid,$this->type);
			}else{
				throw new Exception("no uid set");
			}
		}
		$odata = json_decode($t->get($id),true);
		foreach($data as $k=>$v){
			$odata[$k]=$v;
		}
		$t->put($id,json_encode($odata));
		return $id;
	}


	public function mupdate(&$datas,&$t=null)
	{
		foreach($datas as $d){
			$ret[]= $this->update($d,$uid,$item_group,$t);
		}
		return $ret;
	}

	/**
	 * 删除
	 * @param $uid
	 * @param $ids
	 * @return unknown_type
	 */
	public function remove($uid,$ids,&$t=null)
	{
		if($t==null)
			$t = TT::get_tt($this->name,$uid,$this->type);
		$t->out($ids);
	}


	/**
	 * 按uid获取,获取某一类型
	 * @param $id
	 * @return unknown_type
	 * @param $uid
	 * @param $item_group
	 * @return unknown_type
	 */
	public function get($uid,$item_group='',&$t=null)
	{
		if($t==null)
			$t = TT::get_tt($this->name,$uid,$this->type);
		$keyp = $uid.':';
		if($item_group){
			$keyp .= $item_group.':';
		}
		$keys = $t->fwmKeys($keyp,100000);

		$res = $t->get($keys);
		if(!is_array($res))
			return $res;
		$ret = array();
		foreach($res as $k=>$v){
			$ret[]= json_decode($v,true);
		}
		return $ret;
	}

	/**
	 * 
	 * @param $uid
	 * @param $id
	 * @param $t
	 * @return unknown_type
	 */
	public function getbyid($id,$uid=null,&$t=null)
	{
		if($t){
			return json_decode($t->get($id),true);
		}
		if(!$t && !$uid){
			$idata = explode(':',$id,4);
			if($idata[0]>0){
				$uid = $idata[0];
			}
		}
		if(!$uid){
			throw new Exception("no uid set");
		}
		if($t==null){
			$t = TT::get_tt($this->name,$uid,$this->type);
		}
		return json_decode($t->get($id),true);
	}

	public function getbyids($ids,$uid=null,&$t=null)
	{
                $id = $ids[0];
		if(!$t && !$uid){
			$idata = explode(':',$id,4);
			if($idata[0]>0){
				$uid = $idata[0];
			}
		}
		if(!$uid){
			throw new Exception("no uid set");
		}
		if($t==null){
			$t = TT::get_tt($this->name,$uid,$this->type);
		}
		$ret = $t->get($ids);
                $res =array();
                foreach($ret as $k=>$v){
                    $res[$k]=json_decode($v,true);
                }
                return $res;
	}

}

