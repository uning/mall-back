<?php
$mypath    = dirname(__FILE__);
$confile   = $mypath."/storage-conf.xml";
$output_dir = $mypath."/../web/public/webreject/model";

/*
$fsrcs= <<<EOD
<?php

class ModelFactory {

	public static function getModel($name,$db=null)
	{
		if(!class_exists($name))
		{
			$file=MODEL_ROOT.$name.'.php';
			if(!file_exists($file)){
				//Logger::error(__METHOD__."Use basic $file, file do not exists");
				return new DBModel($name);
			}
			else
			require_once $file;
		}
		$ret =new $name;
		if($db)
		$ret->setDb($db);
		return $ret;
	}
	public static function UserAccount($db=null){return self::getModel('UserAccount',$db);}
EOD;


function genAunFunction($name)
{
$str = <<<EOD
	public static function $name(){return self::getModel('$name');}
	
EOD;
return $str;
}
*/
function genClass($cfname,$isSupper,$coltype,$subColtype)
{
 $str = <<<EOD
 <?php
 class $cfname extends CassandraCF{
    function $cfname()
    {
       parent::CassandraCF('mall','$cfname','$isSupper','$coltype','$subColtype');
    }
 }
EOD;
 return $str;
}



$xml =  simplexml_load_file($confile);



foreach($xml->Keyspaces->Keyspace as $ks){
	$ksname =  $ks['Name'];
	echo "$ksname \n";
	if($ksname=='mall'){
		foreach($ks->ColumnFamily as $col){
			$cfname = $col['Name'];
			$isSupper =$col['ColumnType'];
			if($isSupper){
				//$isSupper = true;
			}
			$coltype = $col['CompareWith'];
			$subtype = $col['CompareSubcolumnsWith'];
			echo  "$cfname\n";
			$cfile = $output_dir."/$cfname.php";
			
			$fsrcs .= genAunFunction($cfname);
			if(file_exists($cfile)){
				echo "warning : $cfile exists skipped\n";
				//continue;
				
			}
			
			$cont= genClass($cfname,$isSupper,$coltype,$subtype);
			file_put_contents($cfile,$cont);
			
			
		}
	}
}

$cfile = $output_dir."/ModelFactory.php";
$fsrcs .="}";
file_put_contents($cfile,$fsrcs);


