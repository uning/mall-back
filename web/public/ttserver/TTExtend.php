<?php
if(!class_exists('TokyoTyrant')){
	class TokyoTyrant{
	}
}
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
		if(!$res)
                     return $res;
		$ret = array();
		foreach($res  as $k=>$v){
			$isobj=self::checkObj($v);
			$arr = explode(':',$k);
			$name = end($arr);
			$group = $arr[1];
			if($group){
				if($isobj)
					$ret[$group][]=$v;
				else
					$ret[$group][$name]=$v;
			}else{
				$ret[$name]=$v;
			}
		}
		return $ret;
	}


	static function processlist(&$res)
	{
		if(!$res)
                     return $res;
		$ret = array();
		foreach($res  as $k=>$v){
			$isobj = self::checkObj($v);
			$ret[]=$v;
		}
		return $ret;
	}


	static function processrawmap(&$res)
	{
		if(!$res)
                     return $res;
		$ret = array();
		foreach($res  as $k=>$v){
			$isobj = self::checkObj($v);
			$ret[$k]=$v;
		}
		return $ret;
	}

	static function processone(&$v,&$id)
	{
		self::checkObj($v);
		return $v;
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
	 * @return 
	 */
	function remove($ids)
	{
		try{
			$this->out($ids);
		}catch(Exception $e){
			
		}
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
			return self::processlist($ret);
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

	function getbyids($ids,$raw=false)
	{
		$ret =& $this->get($ids);
		if($raw)
		   return self::processrawmap($ret);
		return self::processmap($ret);
	}

	/**
	 * 改变一个数字段的值
	 * @param $data
	 * @return the new value
	 */
	function  numch($k,$num=1)
	{
		$ret = $this->add($k,(int)$num);
		if($ret===''|| $ret===NULL){
			$this->out($k);
			$ret = $this->add($k,(int)$num);
//			echo "errr $ret ------------  $k\n";
		}else if(!$ret){
			//var_dump($ret);
	         	//echo "$k\n $num";
		}
		return $ret;
	}

	/**
	 *返回扣除后数目,check 如果值不在max=0 min=0 之间，则不改变存储 的值
	 *return 改变后的值 
	 */
	function change($f,$num,$max=0,$min=0)
	{
		$total = $this->numch($f,$num);
		if( $total<$min || ($max!=0 && $total >$max) ){
			$this->numch($f,-$num);
		}
		return $total;
	}

	/**
	 * fixed the return binary data
	 */
	static function checkObj(&$data) 
	{
		if(!$data)
			return false;
		if(preg_match('/((?![\x20-\x7E]).)/', $data)){
			//todo:now just int
			$data = unpack('I',$data);
			$data = $data[1];
			return false;
		}
		if(substr_compare($data,'{',0,1)==0){
			$ndata = json_decode($data,true);
			if($ndata){
				$data =  $ndata;
				return true;
			}
			return false;
		}
	}


	//bianli
	static function getpre($k1,$k2)
	{
		$ret = '';
		for($i=0;;$i++){
			$a = $k2[$i];
			if($k1[$i]!==$a)
				break;      
			$ret .= $a;
		} 	
		return $ret;
	}

	/**
	 * 遍历代码
	 * TODO:just a trick  code , that we can get 
	 * if kpre not change in a call ,we can do nothing
	 *$kpre = '';
	 *while($d=TT::next_recs($t,$kpre,$count)
	 *{

	 *}          
	 *
	 **/
}

