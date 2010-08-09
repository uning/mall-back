<?php

/**
 *
 */
class TTLog{

    static private $_t;

	/**
	 * 保存单条物品，返回id
	 * @param $data
	 * @return unknown_type
	 */
	static public function  record($data)
	{
		$t = & self::$_t;
		if($t==null)
		 $t = TT::get_tt('log');
		$t->putCat(null,$data);
	}
}
