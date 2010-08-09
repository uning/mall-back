<?php
class Order extends DBModel
{
	public function Order($table_name='orders')
	{
		parent::DBModel($table_name);
		$this->useCache(false);
	}
}
