<?php
/**
 * userid 分库
 * 利用id 前缀对key聚合查询
 * 底层为btree key-value数据库
 * 使用json-编码后存储object
 * 利用uid分库
 * id 规则：
 使用 : 分割域
 第一个域为uid，数据按uid分库
 最后一个域为数据类型（当数据为json时）
 group
 可以按小group进行查询
 id 为
 1223:o:dfjjdk:o  (对象的id)
 1223:@:g1:g2:name       (字段name)
 1223:@:g1:g2:name1    （返回时字段为 name1)
 注意，按前缀取，对于非json数据，返回id进行了处理，只保留最后的name，name字段，不要让name有重复 
 *
 */

//we store all data in a ttserver
class TTUDB{
	public   $name = 'main';//for tt
	public   $type = 'master';

	protected $_u;//user
	protected $_t;//tt connection

	protected $_cache;//use cache(id=>data)
	protected $_use_cache=false;//TODO:use cache

	function TTUDB($uid,$name='main',$only_read=false)
	{
		$this->_u = $uid;
		$this->type = $only_read?'slave':'master';
		$this->_t = TT::get_tt($this->name,$this->_u,$this->type);
	}


	/**
	 *  id 生成
	 *  name 如果有，则为固定id
	 *  subg id所在组
	 *  is_obj 是否为复杂结构
	 */
	function getdid($name=false,$subg='')//,$is_obj=false)
	{
		$id = $this->_u.':';
		$id .=$subg.':';
		if($name)
			$id.=$name; 
		else
			$id.=uniqid();
		//if($is_obj)
		//	$id.=':o'; 
		return $id;
	}

	/**
	 *  生成对象id，fixed
	 *
	 */
	function getoid($fixed=false,$item_group=''){
		return $this->getdid($fixed,$item_group,true);
	}




	/**
	 * 保存单条物品，返回id,
	 * @param $data
	 * @return unknown_type
	 * @param $data
	 * @param $uid
	 * @param $item_group
	 * @param $keep 是否保留未更新字段
	 * @param $t
	 * @return unknown_type
	 */
	public function  puto(&$data,$item_group='',$keep=true)
	{
		$id = &$data['id'];
		if(!$id){
			$id = $this->getdid('',$item_group);
			$this->_t->puto($data);
			return $id;
		}
		$this->_t->puto($data,$keep);
		return $id;
	}




	/**
	 * 保存多条物品，返回id (会覆盖原有数据)
	 * item_group 不能以:开始或结尾
	 * @param $data
	 * @return unknown_type
	 */
	public function  mputo(&$datas,$item_group='',$keep=true)
	{
		foreach($datas as $d){
			$id = $d['id'];
			if(!$id){
				$id = $this->getdid('',$item_group);
				$puts[$id]=json_encode($d);
			}
			else if($keep){
				$odata = $this->getbyid($id);
				foreach($d as $k=>$v){
					$odata[$k]=$v;
					$puts[$id] = json_encode($odata);
				}
			}else{
				$puts[$id]=$d;
			}
			$ret [] = $id;
		}
		$this->_t->put($puts);
		return $ret;
	}


	/**
	 * 更新单条物品，返回id,
	 * @param $data
	 * @return unknown_type
	 * @param $data
	 * @param $uid
	 * @param $item_group
	 * @param $keep 是否保留未更新字段
	 * @param $t
	 * @return unknown_type
	 */
	public function updateo(&$data,$keep=false)
	{
		$this->_t->puto($data,$keep);
	}






	/**
	 * 删除
	 * @param $uid
	 * @param $ids :one id or id array
	 * @return unknown_type
	 */
	public function remove($ids)
	{
		$this->_t->out($ids);
	}




	/**
	 * 按uid获取,获取某一类型
	 * @param $id
	 * @return unknown_type
	 * @param $uid
	 * @param $item_group
	 * @return 
	 */
	public function get($item_group='',$list=true,$max=100000)
	{
		$keyp = $this->_u.':'.$item_group.':';
		return  $this->_t->getbypre($keyp,$list,$max);
	}
	public function getAll($max=100000)
	{
		$keyp = $this->_u.':';
		return  $this->_t->getbypre($keyp,$list,$max);
	}



	/**
	 * 按id获取
	 * @param $uid
	 * @param id
	 * @param $t
	 * @return unknown_type
	 */
	public function getbyid($id)
	{
		return $this->_t->getbyid($id);
	}

	public function getbyids($ids)
	{
		return $this->_t->getbyids($ids);

	}


	/**
	 * 改变一个数字段的值
	 * @param $data
	 * @return the new value
	 */
	public function  numch($field,$num=1)
	{
		return $this->_t->numch($field,$num);
	}

	/**
	 * 返回扣除后数目,check 如果值不在max=0 min=0 之间，则不改变存储 的值
	 *return 改变后的值 
	 */
	public function change($f,$num,$max=0,$min=0)
	{
		return $this->_t->change($field,$num,$max,$min);
	}

	/**
	 * 保存字符串数据
	 */
	public function  putf($field,$value,$subg='')
	{

		$k = self::getdid($field,$subg);
		$this->_t->put($k,$value);
		return $k;
	}

	/**
	  保存字符串数据
	 */
	public function  mputf($arr,$subg='')
	{
		$pair=array();
		foreach($arr as $k=>$v){
			$id = $this->getdid($k,$subg);
			$pair[$id]=$v;
		}
		$this->_t->put($pair);
	}

	public function removef($fields=array(),$subg='')
	{
		foreach($fields as $f){
			$keys []= $this->getdid($f,$subg);
		}
		$this->_t->out($keys);
	}

	/**
	 * 获取确定命名的已知字段
	 * @param $uid
	 * @param $fields
	 * @param $t
	 * @return unknown_type
	 */
	public function getf($fields=array(),$subg='')
	{
		if(!$fields){
			return $this->get();
		}
		if(!is_array($fields)){
			$id = $this->getdid($fields,$subg);
			return $this->getbyid($id);
		}
		foreach($fields as $f){
			$ids []= $this->getdid($f,$subg);
		}		
		return $this->_t->processlist( $this->getbyids($ids));
	}

}
