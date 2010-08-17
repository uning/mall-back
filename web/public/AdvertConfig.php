<?php
class AdvertConfig
{
	static $_config=array(1=>array('popularity'=>'15','maxpopular'=>'30','allTime'=>'86400','onlyGem'=>'TRUE','needLevel'=>'1','tag'=>'1',
				1=>array('gem',1),
				10=>array('gem',9),
				50=>array('gem',40),
				100=>array('gem',70)
				)
			,2=>array('popularity'=>'45','maxpopular'=>'60','gem'=>'3','allTime'=>'86400','onlyGem'=>'TRUE','needLevel'=>'1','tag'=>'2',
				1=>array('gem',3),
				10=>array('gem',27),
				50=>array('gem',120),
				100=>array('gem',180)
				)
			,3=>array('popularity'=>'90','maxpopular'=>'90','gem'=>'2','allTime'=>'43200','onlyGem'=>'TRUE','needLevel'=>'1','tag'=>'3',
				1=>array('gem',3),
				10=>array('gem',27),
				50=>array('gem',120),
				100=>array('gem',180)
				)
			,4=>array('popularity'=>'120','maxpopular'=>'120','gem'=>'4','allTime'=>'43200','onlyGem'=>'TRUE','needLevel'=>'1','tag'=>'4',
				1=>array('gem',3),
				10=>array('gem',27),
				50=>array('gem',120),
				100=>array('gem',180)
				)
			,5=>array('popularity'=>'120','maxpopular'=>'120','gem'=>'4','allTime'=>'43200','onlyGem'=>'TRUE','needLevel'=>'1','tag'=>'4',
					1=>array('gem',3),
					10=>array('gem',27),
					50=>array('gem',120),
					100=>array('gem',180)
				 )
			,6=>array('popularity'=>'120','maxpopular'=>'120','gem'=>'4','allTime'=>'43200','onlyGem'=>'TRUE','needLevel'=>'1','tag'=>'4',
					1=>array('gem',3),
					10=>array('gem',27),
					50=>array('gem',120),
					100=>array('gem',180)
				 )
			);
	static function getAdvert ( $tag )
	{
		return self::$_config[$tag];
	}
}
