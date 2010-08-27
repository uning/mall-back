<?php

/**
 *
 */
class TTLog{

    static private $_t;

	/**
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
