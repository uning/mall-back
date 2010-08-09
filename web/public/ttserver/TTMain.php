<?php

/**
 *
 */
class TTMain extends TTObject{


	public function TTMain($only_read=false)
	{
		$this->type = $only_read?'slave':'master';
                $this->name = 'main';
	}

}
