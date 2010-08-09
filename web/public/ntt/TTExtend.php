<?php

/**
 *object 
 */
class TTExtend extends TokyoTyrant
{
	static function getnamebyid($id)
	{
		$arr = explode(':',$id);
		$ret = end($arr);
		if($ret !=':o')
			return $ret;
		return prev($arr);
	}



	/**
	 * 反序列化结果，并对固定字段做处理
	 * 对json数据，反序列化
	 * 对字符串数据和int数据，处理key为最后一级的name
	 * @param $res
	 * @return unknown_type
	 */
	static function processmap(&$res)
	{
		$ret = array();
		foreach($res  as $k=>$v){
			if( substr_compare($k,':o',-2,2)==0){
				$ndata = json_decode($v,true);
				if($ndata){
					$ret[$k] = $ndata;
				}else{
					$ret[$k] = $v;//error occur?
					error_log("key=$k value=$v expect to a json");
				}
			}else{
				self::checkBinary($v);
				$key = self::getnamebyid($k);
				$ret[$key]=$v;
			}
		}
		return $ret;
	}


	static function processlist(&$res)
	{
		$ret = array();
		foreach($res  as $k=>$v){
			//if( substr_compare($k,':o',-2,2)==0){
			if( substr_compare($v,'{',0,1)==0){
				$ndata = json_decode($v,true);
				if($ndata){
					$ret[] = $ndata;
				}else{
					$ret[] = $v;//error occur?
					error_log("key=$k value=$v expect to a json");
				}
			}else{
				self::checkBinary($v);
				$key = self::getnamebyid($k);
				$ret[$key]=$v;
			}
		}
		return $ret;
	}

	
	static function processone(&$v,&$k)
	{
		if(substr_compare($k,':o',-2,2)==0){
			$ndata = json_decode($v,true);
			if($ndata){
				return $ndata;
			}
			error_log("key=$k value=$v expect to a json");
			return $v;//error occur?
		}
		self::checkBinary($v);
		return $v;//error occur?
	}

	/*
	 *保存一个json 结构
	 *keep 是否保留原有字段
	 */
	function  puto($data,$keep=false,$id=null)
	{
		if($id==null){
			$id  = $data['id'];
		}else{
			$data['id']=$id;
		}
		if($keep){
			$odata = $this->getbyid($id);
			foreach($data as $k=>$v){
				$odata[$k]=$v;
			}
			$this->put($id,json_encode($odata));
		}else{
			$this->put($id,json_encode($data));
		}
		return $id;
	}




	/**
	 * item_group 不能以:开始或结尾
	 * @param $data
	 * @return unknown_type
	 */
	function  mputo(&$datas)
	{
		foreach($datas as $d){
			$id = $d['id'];
			if(!$id)
				throw Exception('no id set in mputo');
			$puts[$id] = json_encode($d);
		}
		$this->put($puts);
	}




	/**
	 * 删除
	 * @param $uid
	 * @param $ids :one id or id array
	 * @return unknown_type
	 */
	function remove($ids)
	{
		$this->out($ids);
	}




	/**
	 * @param $id
	 * @return unknown_type
	 * @param $uid
	 * @param $item_group
	 * @return 
	 */
	function getbypre($pre,$list=false,$max=10000)
	{
		$ret = & $this->get($this->fwmKeys($pre,$max));
		if($list)
                  self::processlist($ret);
		return $ret;
		
	}


	/**
	 * 按id获取
	 * @param $uid
	 * @param id
	 * @param $t
	 * @return unknown_type
	 */
	function getbyid($id)
	{
		return self::processone($this->get($id),$id);
	}

	function getbyids($ids)
	{
		//return $this->get($ids);
		return self::processmap($this->get($ids));
	}

	/**
	 * 改变一个数字段的值
	 * @param $data
	 * @return the new value
	 */
	function  numch($k,$num=1)
	{
		return $this->add($k,$num);
	}

	/**
	 *返回扣除后数目,check 如果值不在max=0 min=0 之间，则不改变存储 的值
	 *return 改变后的值 
	 */
	function change($f,$num,$max=0,$min=0)
	{
		$total = $this->add($f,$num);
		if( $total<$min || ($max!=0 && $total >$max) ){
			$this->add($f,-$num);
		}
		return $total;
	}

	/**
	 * fixed the return binary data
	 */
	protected static function checkBinary(&$data) 
	{
		//if( is_binary($data)){
		if(preg_match('/((?![\x20-\x7E]).)/', $data)){
			//todo:now just int
			$data = unpack('I',$data);
			//print_r($data);
			$data = $data[1];
			return true;
		}
		return false;
	}




}

