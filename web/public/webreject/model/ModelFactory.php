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
	public static function Invitation($db=null){return self::getModel('Invitation',$db);}	
	public static function Order($db=null){return self::getModel('Order',$db);}
	public static function Present($db=null){return self::getModel('Present',$db);}
}
