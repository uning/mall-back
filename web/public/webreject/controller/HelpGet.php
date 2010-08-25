<?php
class HelpGet
{

	static function getConf($tag){
		return array('tag'=>'60102','need_num'=>3,'icon'=>'','group'=>'g');
		$_config=array();
	}

	/**
	 * 获取help进度
	 * @param $params
	 *   require  u      --  玩家id
	 *            
	 * @return 
	 *            s      --  OK,other failed
	 d       array of help objs
	 */
	public function get( $params )
	{
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		$ret['s']='OK';
		$ret['d']=$tu->get_help();
		return $ret;
	}

	/**
	 * 开启help，前端调完后发feed出去
	 * @param $params
	 *   require  u      --  玩家id
	 *            tag    --  对应xml的id,物品
	 *            
	 * @return 
	 *            s      --  OK,other failed
	 no,不能open的物品
	 *            tag    --  
	 */

	public function open($params )
	{
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		$tag = $params['tag'];
		$conf = self::getConf($tag);
		if(!$conf){
			$ret['s']='no';	
			return $ret;
		}

		$id = $tu->getoid($tag,'ho');
		$obj = $tu->getbyid($id);	
		if($obj){
			$ret['s']='opened';
			return $ret;

		}
		$obj['ot']=time();
		$obj['id']=$id;

		$tu->puto($obj);
		$ret['s'] = 'OK';
		return $ret;
	}
	/**
	 * 领取
	 * @param $params
	 *   require  u      --  玩家id
	 *            tag    --  对应xml的id,物品
	 *            
	 * @return 
	 *            s      --  OK,other failed
	 no,不能open的物品
	 nopen,没有打开
	 cond,没有达到条件
	 award,已经领取
	 *            ids    --  新增物品id
	 */

	public function award( $params )
	{
		$uid = $params['u'];
		$tu = new TTUser( $uid );
		$tag = $params['tag'];
		$conf = self::getConf($tag);
		if(!$conf){
			$ret['s']='no';	
			return $ret;
		}

		$id = $tu->getoid($tag,'ho');
		$obj = $tu->getbyid($id);	
		if(!$obj){
			$ret['s']='nopen';
			return $ret;

		}
		if(count($obj['help'])<$conf['need_num']){
			$ret['s']='cond';
			return $ret;
		}
		if($obj['award']){
			$ret['s']='award';
			return $ret;
		}
		$obj['award']=$_SERVER['REQUEST_TIME'];
		$obj['id']=$id;
		$tu->puto($obj);
		$iid=$tu->getoid(null,$conf['group']);
		$item['id']=$iid;
		$item['pos'] = 's';
		$tu->puto($item);
		$ret['s'] = 'OK';
		$ret['id'] = $iid;
		return $ret;
	}

}
