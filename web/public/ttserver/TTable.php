<?php

/**
 *
 */
class TTable extends TokyoTyrantTable {
	public  $needSV = false;

	protected function before_save(&$data)
	{
		if(!$this->needSV || $data == null)
			return;
		foreach($data as $k=>$v){
			if(is_array($v)||is_object($v)){
				$data[$k] = json_encode($v);
			}
		}
	}

	protected function after_get(&$data)
	{
		if(!$this->needSV || $data == null)
			return;
		foreach($data as $k=>$v){
			if($v && (substr_compare($v,'{',0,1,false)== 0
			       ||substr_compare($v,'[',0,1,false)== 0)
			){

				$nd = json_decode($v,true);
				if($nd)
					$data[$k]=$nd;
			}
		}
	}
	/**
	 * 保存单条记录,返回id
	 * @param $data
	 * @return unknown_type
	 */
	public function  put($data)
	{
		$this->before_save($data);
		$id = $data['id'];
		if($id){
			$odata = $this->get($id);
			foreach($data as $k=>$v){
				$odata[$k]=$v;
			}
			parent::put($id,$odata);
		}else{
			$id = parent::genUid();
			$data['id']=$id;
			parent::putCat($id,$data);
		}
		return $id;
	}
	
	

	/**
	 * 保存多个物品，返回id
	 * @param $data
	 * @return unknown_type
	 */
	public function  mput($datas)
	{
		foreach($datas as $k=>$data){
			$id[$k] = $this->put($data,$uid,$t);
		}
		return $id;
	}


	/**
	 * 按id获取
	 * @param $id
	 * @return unknown_type
	 */
	public function get($id)
	{
		$r = parent::get($id);
		$this->after_get($r);
		return $r;
	}


	/**
	 * 删除
	 * @param $uid
	 * @param $ids
	 * @return unknown_type
	 */
	public function remove($ids)
	{
		$this->out($ids);
	}



	/**
	 *按索引查询
	 *
	 */
	public function getbyidx($idxname,$idxvalue)
	{
		$q=$this->getQuery();
		$q->setLimit(10000);
		$q->addCond($idxname,TokyoTyrant::RDBQC_STREQ,$idxvalue);
		$res = $q->search();
		return $res;
	}
	
	/**
	 *按索引查询
	 *
	 */
	public function getbyuidx($idxname,$idxvalue)
	{
		$q=$this->getQuery();
		$q->setLimit(1);
		$q->addCond($idxname,TokyoTyrant::RDBQC_STREQ,$idxvalue);
		$res = $q->search();
		foreach($res as $k=>$v){
		  $this->after_get($v);
		  $v['id']=$k;
		 
		  return $v;
		}
		return $res;
	}
}
