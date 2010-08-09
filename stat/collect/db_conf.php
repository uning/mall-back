<?php
////get insert sql 
function get_insert_sql($table,&$fields,&$data)//,$ignore='ignore')
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
		return $ret;
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
///
function my_mysql_query($q,&$link=null)
{
	//mylog($q);
	$res=mysql_query($q,$link);
	if(!$res)
	{
		if (!mysql_ping($link)) {
			exit;
		}
		$res=mysql_query($q,$link);
	}
	return $res;
}

//////
function get_table_field($table,&$link)
{
	$result = mysql_query("SHOW COLUMNS FROM $table",$link);
	if (!$result) {
		echo 'Could not run query: ' . mysql_error($link);
		exit;
	}
	$ret=false;
	while ($row = mysql_fetch_assoc($result)) {
		$ret[]=$row['Field'];
	}
	return $ret;
}

