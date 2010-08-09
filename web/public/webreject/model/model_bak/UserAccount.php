<?php
//require_once LIB_ROOT.'facebook/facebook.php';
require_once LIB_ROOT.'DBModel.php';

class UserAccount extends DBModel
{
	public function UserAccount($table_name='user_accounts')
	{
		parent::DBModel($table_name);
		$this->useCache(false);
	}
}
