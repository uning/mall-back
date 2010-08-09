<?php
class Invitation extends DBModel
{
	public function Invitation($table_name='invitations')
	{
		parent::DBModel($table_name);
		$this->useCache(false);
	}
	
    public  function invite($user_id, $to_ids )
	{
		if($to_ids && count($to_ids)> 0){
			 if(!$this->_db)
			   return;
			$strs = array();
			foreach($to_ids as $key=>$id){
				$strs[] = "(".$user_id.",".$id.")";
			}		 
			$stmt = $this->_db->query(
				"insert into {$this->table_name}(user_id,to_platform_id) values ".join($strs,',') 
			);
			return $stmt->rowCount();
		
		}
	   return 0;
   }	
}
