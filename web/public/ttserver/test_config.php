<?php
require_once 'TTG.php';
class TT extends TTG{
	public static $ttservers = array(
			'main'=> array(
				'type'=>'TTExtend',
				'procs'=>array(
					array(
						array('host'=>'127.0.0.1','port'=>'15000')
					     ),
					array(
						array('host'=>'127.0.0.1','port'=>'15001')
					     ),
					)
				),
			//========================================table===========================
			//id 增长
			'genid' => array(
					'type'=>'TokyoTyrantTable',
					'procs'=>
					array(
						array(
							array('host'=>'127.0.0.1','port'=>'16004')
						     ),
					     )
					),

			'log'=> array(
					'type'=>'TokyoTyrantTable',
					'procs'=>array(
						array(
							array('host'=>'127.0.0.1','port'=>'16002')
						     ),
						),
				     ),
			'gem' => array(
					'type'=>'TTable',
					'procs'=>
					array(
						array(
							array('host'=>'127.0.0.1','port'=>'16006')
						     ),
					     )
				       ),

			'link'=> array(
					'type'=>'TTable',
					'procs'=>array(
						array(
							array('host'=>'127.0.0.1','port'=>'16006')
						     ),
						),
				      ),
			'order'=> array(
					'type'=>'TTable',
					'procs'=>array(
						array(
							array('host'=>'127.0.0.1','port'=>'16008')
						     ),
						),
				       ),
			);
}
