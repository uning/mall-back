<?php
require_once 'TTG.php';
class TT extends TTG{
	public static $ttservers = array(
			'main'=> array(
				'type'=>'TTExtend',
				'procs'=>array(
					array(
						array('host'=>'122.11.61.27','port'=>'15000')
					     ),
					)
				),
			//前台用数据
			'data'=> array(
				'type'=>'TTExtend',
				'procs'=>array(
					array(
						array('host'=>'122.11.61.27','port'=>'15002')
					     ),
					),
				),
			//邀请，送礼等存储数据
			'other'=> array(
				'type'=>'TTExtend',
				'procs'=>array(
					array(
						array('host'=>'122.11.61.27','port'=>'15004')
					     ),
					),
				),
			//页面端暂存数据
			'web'=> array(
					'type'=>'TTExtend',
					'procs'=>array(
						array(
							array('host'=>'122.11.61.27','port'=>'16000')
						     ),
						),
				     ),

			//========================================table===========================
			//id 增长
			'genid' => array(
					'type'=>'TokyoTyrantTable',
					'procs'=>
					array(
						array(
							array('host'=>'122.11.61.27','port'=>'16004')
						     ),
					     )
					),

			'log'=> array(
					'type'=>'TokyoTyrantTable',
					'procs'=>array(
						array(
							array('host'=>'122.11.61.27','port'=>'16002')
						     ),
						),
				     ),
			'stat' => array(
					'type'=>'TTable',
					'procs'=>
					array(
						array(
							array('host'=>'122.11.61.27','port'=>'16000')
						     ),
					     )
				       ),

			'link'=> array(
					'type'=>'TTable',
					'procs'=>array(
						array(
							array('host'=>'122.11.61.27','port'=>'16006')
						     ),
						),
				      ),
			'order'=> array(
					'type'=>'TTable',
					'procs'=>array(
						array(
							array('host'=>'122.11.61.27','port'=>'16008')
						     ),
						),
				       ),
			);
}
