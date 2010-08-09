<?php

/**
 *
 */
class TTGenid {

	const IDX_F='@';
	static private $_t;

	/**
	 * 生成id，返回id
	 * @param $data 数组
	 * @return unknown_type
	 */
	static public function  genid($data,&$new=false)
	{
		$field = 'pid';
		if(is_array($data)){
			$key = $data[$field];

		}else{
			$key=$data;
			$data =array($field=>$key);
		} 
		$t = & self::$_t;
		if($t==null)
			$t = TT::get_tt('genid');
		$q=$t->getQuery();
		$q->addCond($field,TokyoTyrant::RDBQC_STREQ,$data[$field]);
		$res = $q->search();
		if($res){
			foreach($res as $k=>$v){
				$r = array_merge($v,$data);
				$r['id']=$k;
				$data['id'] = $t->put($k,$r);
				$new = false;
				return $r;
			}
		}
		$new = true;
		$data['id'] = $t->put(null,$data);
		return $data;
	}

	/**
	 */
	static public function  getid($data,$field='pid')
	{

		if(is_array($data)){
			$key = $data[$field];

		}else{
			$key=$data;
			$data =array($field=>$key);
		} 

		$t = & self::$_t;
		if($t==null)
			$t = TT::get_tt('genid');
		$q=$t->getQuery();
		$q->addCond($field,TokyoTyrant::RDBQC_STREQ,$data[$field]);
		$res = $q->search();
		if($res){
			foreach($res as $k=>$v){
				$r = array_merge($v,$data);
				$r['id']=$k;
				return $r;
			}
		}
		return null;
	}

	static public function update( $data,$id=null)
	{
		if($id==null)
			$id = $data['id'];
		if($id<1)
			return null;
		$t = & self::$_t;
		if($t==null)
			$t = TT::get_tt('genid');

		$odata=$t->get($id);


		if(!$odata)
			return null;
		$newdata = array_merge($odata,$data);
		$t->put($id,$newdata);
		return $newdata;
	}

}
