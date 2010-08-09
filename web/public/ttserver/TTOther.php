<?php

/**
 *
 */
class TTOther extends TTObject{


	public function TTOther($only_read=false)
	{
		$this->type = $only_read?'slave':'master';
                $this->name = 'other';
	}

}
