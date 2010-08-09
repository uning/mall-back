<?php

$myloc = dirname(__FILE__);
require_once($myloc.'/../../web/public/base.php');
require_once LIB_ROOT.'DBModel.php';
$gtt =TT::get_tt('genid');//,0,'slave'); 
$dbconfig=array(
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'port'     =>'3307',
		'charset'     =>'utf8',
		'dbname'   => 'mall_stat');
$db = ServerConfig::connect_mysql($dbconfig);

function getModel($name){
	global $db;
	$m = new DBModel($name,false);
	$m->setDb($db); 
	return $m;
}

$dgm = getModel('daily_general');
$dgfs = $dgm->getTableFields('daily_general');
print_r($dgfs);
$now = time();
$date = date('Y-m-d',$now);

function get_insert_sql($table,&$fields,&$data,$ignore='',$dup=' ON DUPLICATE KEY UPDATE')//update
{
		$ret="insert $ignore into $table set  ";
		$comma=false;
		foreach($fields as $k)
		{
			$v=&$data[$k];
			if($comma){
				$ret.=',';
			}
			$ret.="`$k`='".mysql_escape_string ($v)."'";
			$comma=true;
		}
		return $ret . " $dup";
}

function get_update_sql($table,&$fields,&$data,$cond="1")
{
    $vaulest='';
    foreach($fields as $k)
    {
        $v=$data[$k];
        if($v!==""){
            if($vaulest!=''){
                $vaulest.=',';
            }   
            $vaulest.=' `'.$k."`='".mysql_escape_string($v)."'";
        }   
    }   
    $sql='update '.$table.' set'.$vaulest;
    if($cond!="")
        $sql.=' where '.$cond;
    return $sql;
}  
