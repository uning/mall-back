<?php
class TTUser extends TTUDB
{

	protected $_data;


	public function TTUDB($u,$name='main',$only_read=false)
	{
		parent::TTUDB($u,$name,$only_read);
	}


	public function getdata($f=array(TT::GEM_STAT,TT::MONEY_STAT,TT::EXP_STAT,'musicon','audioon','looks','sex'))
	{
		if($f==null || is_array($f)){
			$this->_data = $this->getf($f);
			return $this->_data;
		}
		if( isset($this->_data[$f]))
			return $this->_data[$f];
		$this->_data[$f] =  $this->getf($f);
		return $this->_data[$f] ;
	}

	public function initAccount()
	{   
		$now = time();
		$this->numch(TT::MONEY_STAT,10000);
		$this->numch(TT::GEM_STAT,1);
		$this->numch(TT::EXP_STAT,0);
		$this->numch(TT::POPU,0);
		$this->numch(TT::GARAGE_STAT,1);
		$this->numch('lastawardtime',0);
		$this->numch('continued',0);
		$arr=array(	TT::CAPACITY_STAT=>"3,2",
				'it'=>$now);
		$this->mputf($arr);

		//初始化一辆车
		$car_obj['tag'] = 92701;
		$car_obj['t'] = 0;
		$car_obj['pos']['x'] = 0;
		$car_obj['pos']['y'] = 0;
		$this->puto( $car_obj,TT::CAR_GROUP );

		//初始化一个两格的店面
		$gid = $this->getoid('init',TT::GOODS_GROUP);
		$shop_obj['tag'] = 60002;
		$shop_obj['pos']['x'] = 0;
		$shop_obj['pos']['y'] = 50;
		$shop_obj['goods'][$gid]=$now;
		$shop_id = $this->puto( $shop_obj,TT::SHOP_GROUP );

		//初始化一箱酒
		$goods_obj['id'] = $gid;
		$goods_obj['tag'] = 10103;
		$goods = ItemConfig::getItem( $goods_obj['tag'] );
		$goods_obj['num'] = $goods['unitcout'];
		$goods_obj['stag'] = $shop_obj['tag'];
		$goods_obj['stime'] = $now - 100;
		$goods_obj['pos']['x'] = 0;
		$goods_obj['pos']['y'] = $shop_id;
		$this->puto( $goods_obj,TT::GOODS_GROUP );

		//初始化库存的rose，coffee等各5箱，共15箱
		for( $i=10106;$i<10109;$i++ ){
			$inventory_obj['tag'] = $i;
			$inventory_obj['pos'] = 's';
			$item = ItemConfig::getItem( $i );
			$inventory_obj['num'] = $item['unitcout'];
			for( $n=0;$n<5;$n++ ){
				if( isset( $inventory_obj['id'] ) ){
					unset( $inventory_obj['id'] );
				}
				$this->puto( $inventory_obj,TT::GOODS_GROUP );
			}
		}

		//初始化楼体
		$bb_obj['tag'] = 99505;
		$bb_obj['pos']['x'] = 0;
		$bb_obj['pos']['y'] = "bb"; 
		$this->puto( $bb_obj,TT::ITEM_GROUP );
		//屋顶
		$root_obj['tag'] = 99204;
		$root_obj['pos']['x'] = 0;
		$root_obj['pos']['y'] = "roo"; 
		$this->puto( $root_obj,TT::ITEM_GROUP );

		//两个电梯
		$ver_obj['tag'] = 99001;
		$ver_obj['pos']['x'] = 0;
		for( $i=0;$i<2;$i++ ){
			if( isset($ver_obj['id'] ) )
				unset( $ver_obj['id'] );
			$ver_obj['pos']['y'] = "ver".$i;
			$this->puto( $ver_obj,TT::ITEM_GROUP );
		}		
		//两个地板装饰物
		$fl_obj['tag'] = 96003;
		$fl_obj['pos']['x'] = 0;
		for( $i=0;$i<2;$i++ ){
			if( isset($fl_obj['id'] ) )
				unset( $fl_obj['id'] );
			$fl_obj['pos']['y'] = 50-$i;
			$this->puto( $fl_obj,TT::ITEM_GROUP );
		} 		

		//初始化四个出售牌装饰物
		$item_obj['tag'] = 71105;
		$item_obj['pos']['y'] = 50;
		for( $x=0;$x<=6;$x+=2 ){
			if( $item_obj['id'] ){
				unset( $item_obj['id'] );
			}
			$item_obj['pos']['x'] = $x;
			$this->puto( $item_obj,TT::ITEM_GROUP );
		}
	}
	/**
	 * 
	 * @param $num
	 * @return unknown_type
	 */
	public function chGem( $num)
	{
		return $this->change(TT::GEM_STAT,$num);
	}    
	public function addExp( $num)
	{
		return $this->numch(TT::EXP_STAT,$num);
	}    

	/**
	 * tools 
	 * @param $num
	 * @return unknown_type
	 */
	public function chMoney( $num )
	{
		return $this->change(TT::MONEY_STAT,$num);
	}



	/**
	 * 转换
	 * @param $exp
	 * @return unknown_type
	 */
	public function getLevel($exp=0)
	{
		if(!$exp){
			$exp = $this->getf(TT::EXP_STAT);
		}
		return UpgradeConfig::getLevel( $exp ) ;
	}


	/**
	 * 购买物品：
	 * 检查购买等级，成就，任务，金钱，数量等限制
	 * 打折，限量
	 * 扣除玩家货币
	 * 增加经验
	 * 放入仓库？
	 * @param $item
	 * @return array
	 */
	public function buyItem($tag,$num = 1,$usegem = false)
	{
		$item = ItemConfig::getItem( $tag );
		if(!$item || !isset($item['tag'])){
			$ret['s'] = 'notfind';
			return $ret;
		}
		if( $item['can_buy'] != 'true'  &&  $item['can_buy'] != '1' && $item['group'] != 'g' ){
			$ret['s'] = 'notbuy';
			return $ret;
		}

		//TT::MONEY_STAT,TT::GEM_STAT
		$fds = array(TT::EXP_STAT);
		if($item['buy_need_achive']>0){
			$bna = TT::ACHIVEPRE_STAT.$item['buy_need_achive'];
			$fds[] = $bna;
		}
		if($item['buy_need_task']>0){
			$bnt = TT::TASKPRE_STAT.$item['buy_need_task'];
			$fds[] = $bnt;
		}
		$data = &$this->getdata($fds);		
		//等级检查
		if($item['buy_need_level']>$this->getLevel($data[TT::EXP_STAT])){
			$ret['s'] = 'level';
			return $ret;
		}

		//成就
		if($bna && !$data[$bna]){
			$ret['s'] = 'achive';
			$ret['id'] = $item['buy_need_achiveid'];
			return $ret;
		}
		//任务
		if($bnt && !$data[$bnt]){
			$ret['s'] = 'task';
			$ret['id'] = $item['buy_need_task'];
			return $ret;
		}
		//总数限制
		$total_buy = $item['buy_totalnum'];

		$tusys = new TTUser(0);
		$statid = 'salenum_'.$item['tag'];
		if($total_buy>0){
			$saled = $tusys->numch($statid,0);
			$lnum = $total_buy - $saled;
			if( $lnum<1 ){
				$ret['s'] = 'buynumlimit';
				$ret['maxnum'] = $total_buy;
				return $ret;
			}
		}
		//价格计算
		$disc = 1;
		if($item['discount']>0 && $disc < 1){//打折
			$disc = $item['discount'];
		}

		$currency = TT::MONEY_STAT;
//		if( $usegem || $item['onlygem'] ){
		if( $usegem || $item['onlygem'] == 'true' ){//前端把excel中此字段为空的加上false,但未及时通知后端，导致此处逻辑错误
			$currency = TT::GEM_STAT;
		}

		$cnum = floor($disc*$item[$currency]*$num);
		$rnum = $this->change($currency,-$cnum);
		if($rnum<0){
			if($currency=='money'){
				require_once 'GoodsController.php';
				GoodsController::checkout($params);
			}
		}
		$rnum = $this->change($currency,-$cnum);
		if($rnum<0){//钱不够
			$ret['s']   = $currency;
			$ret['num'] = $rnum +$cnum;//买物品失败，剩余金币或宝石
			return $ret;
		}

		$ret['s'] = 'OK';
		$ret[$currency] = $rnum;
		$saled = $tusys->numch($statid,$num);

		if($total_buy)
			$ret['saled_num'] = $saled;
		return $ret;
	}

	/**
	 * 卖出
	 * @param $data (对应数据库的一条记录)
	 * @param $num
	 * @return 
	 */
	public function saleItem(&$data,$num = 1)
	{
		$tag = $data['tag'];
		$item = ItemConfig::getItem( $tag );
		if(!$item || !isset($item['tag'])){
			$ret['s'] = 'notfind';
			return $ret;
		}
		if( $item['sellable'] != 'true' ){
			$ret['s'] = 'notsale';
			return $ret;
		}
		if( $item['onlygem'] == 'true' ){//对用宝石购买的物品，按1:10000换成金币再3折
		    $num = $item['gem']*3000;
		}
		else{
		    $num = $item['money']*0.3;
		}
		$rnum = $this->numch( TT::MONEY_STAT,$num );
		$tusys = new TTUser(0);
		$statid = 'usalenum_'.$item['tag'];
		$saled = $tusys->numch($statid,$num);//记录系统回购每种商品总数
		$ret['s'] = 'OK';
		$ret['money'] = $rnum;
   		return $ret;
	}


	/**
	 * 获取所有帮助开启物品进度
	 * @param
             tag 

	 * @return  one or a array of help open objects
	 */
	public function get_help($tag='')
	{
		if($tag){
		 	$oid = $this->getoid($tag,'ho');
			return $this->getbyid($oid);
		} 
		return $this->get('ho');
	}

	/**
	 * 更新帮助获取物品
	 * @param
             tag 
             fid

	 * @return  one or a array of help open objects
	 */
	public function update_help($tag,$fid)
	{
		$oid = $this->getoid($tag,'ho');
		$obj = $this->getbyid($oid);
		$obj['help'][$fid]=time();
		$obj['id'] = $oid;
		$this->puto($obj);
	}


}
