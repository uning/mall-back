<?php

//for some aux function
class BaseController{


	/**
	 * 分页获取列表
	 * @param $model
	 * @param $p
	 * @return 
	 */
	protected function getdata(&$model,&$p)
	{
		$u = $params['u'];   
		$ids   = $params['ids'];
		if($ids){
			return  $model->getcols($uid,$ids );
		}
		$fromid = $params['fromid'];
		$num = $params['num'];
		if(!$num)
			$num = 1000;
		$rev = $params['rev'];
		return  $model->get($uid,null,$rev,$num,$fromid);   

	}

	//with return 
	protected function get(&$model,&$p)
	{
		$ret['s'] = 'OK';
		$ret['r'] = $this->getdata($model,$p);
		return $ret;
	}


}

