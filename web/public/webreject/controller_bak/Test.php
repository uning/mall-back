<?php
require_once(dirname(__FILE__).'/../../base.php');
require_once(LIB_ROOT.'cassandra/config.php');
CassandraConn::add_node('localhost', 9160);

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
	
	public function all($p)
	{
	    $ret['s'] = 'ok';
	    $t = new CassandraCF('mall','UserItem',true);
	    $id = $p['u'];
//	    $t->put($p,$id);
//	    $t->put_super($p,$id);
	    $ret['d'] = $t->get($id);
	    return $ret;
	}
}