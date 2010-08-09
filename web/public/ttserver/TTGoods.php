<?php

/**
 *
 */
class TTGoods extends TTObject{


	public function TTGoods($only_read=false)
	{
		$this->type = $only_read?'slave':'master';
                $this->name = 'goods';
	}
}
