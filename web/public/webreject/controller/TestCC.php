<?php
require_once(dirname(__FILE__).'/../../base.php');
require_once(LIB_ROOT.'cassandra/config.php');


class Test{
	public function put($p){

		$ret['s']='OK';
		$t = new CassandraCF('mall','UserItem',true);
		// if(is_array($p)){
		$id=$p['u'];
		 

		 

		$ret['id']=$t->put_super($p['d'],$id);

		return $ret;
	}
	 
	public function get($p){
		$ret['s']='OK';
		$t = new CassandraCF('mall','UserItem',true);
		$id=$p['u'];
		$ret['d'] = $t->get($id);
		return $ret;
	}
}
//return;

$p = array('u'=>'2','d'=>array('id'=>'testid','first'=>'Zeng','last'=>'tingkun','addr'=>array('city'=>'beijing','zip'=>'123456')));
print_r($p);
$t =new Test();
record_time($start);
print_r($t->put($p));
record_time($start,'$t->put($p)');
print_r($t->get($p));
record_time($start,'$t->get($p)');
