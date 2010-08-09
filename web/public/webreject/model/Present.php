<?php
class Present extends DBModel
{
	public function Present($table_name='presents')
	{
		parent::DBModel($table_name);
		$this->useCache(false);
	}
	
	public function findByIDs( $uid,$fid,$xid )
	{
	    $sql = "SELECT * FROM {$this->table_name} where user_id = $uid and donor_id = $fid and item_id=$xid and done=0";
	    return $this->_db->fetchRow( $sql );
	}
}
