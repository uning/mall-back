<?php

$myloc = dirname(__FILE__);
require_once($myloc.'/../../web/public/base.php');
require_once LIB_ROOT.'DBModel.php';

$gtt =TT::get_tt('genid',0,'slave'); 
$gttw =TT::get_tt('genid');
$now = time();
//$now -= 86400;
$datestr = date('Y-m-d',$now);
$weekday = date('N',$now);
$day_starttime = strtotime($datestr);
$day_endtime = $day_starttime + 86400;
echo "date:$datestr \nweekday:$weekday\n";

require_once '../../web/renren/renren.php';
$ren = new Renren();
$ren -> api_key ='a32cb73bea154d2c9d40703b66dc9142'; 
$ren -> secret = '023a6201a9b04955b1af79b1e9037c16';
$ren -> init();

$dbconfig=array(
		//'host' => '122.11.61.28',
		'host' => '127.0.0.1',
		'username' => 'admin',
		'password' => '123456',
		'port'     =>'3307',
		'charset'     =>'utf8',
		'dbname'   => 'mall_stat');
$cmd = "mysql -u{$dbconfig['username']} -P{$dbconfig['port']}  -h{$dbconfig['host']} ";
if($dbconfig['password']){
  $cmd.=" -p'{$dbconfig['password']}'";
}
echo "$cmd\n";
$db = ServerConfig::connect_mysql($dbconfig);


function getModel($name){
	global $db;
	$m = new DBModel($name,false);
	$m->setDb($db); 
	$m->useCache(false);
	return $m;
}

$g_dgm = getModel('daily_varibles');
function store_varible($pairs)
{
	if(!$pairs)
          return;
	global $g_dgm,$datestr,$db;
	$data['date']=$datestr;
	foreach($pairs as $k=>$v){
		if(!$v)
			$v = 0;
		$data['name']=$k;
		$data['value']=$v;
		try{
			$sql = "select * from daily_varibles where `date`='$datestr' and `name`='$k'";	
			$rdata = $db->fetchRow($sql);
			//echo "$sql\n";
			//print_r($rdata);
			if($rdata){
				$g_dgm->update($data,$rdata['id']);

			}else
				$g_dgm->insert($data);
		}catch(Exception  $e){
			echo "exception : ".$e->getMessage()."\n";
		}
	}
}
//$dgfs = $dgm->getTableFields('daily_general');
//print_r($dgfs);

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
