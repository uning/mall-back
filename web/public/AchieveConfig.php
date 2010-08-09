<?php
class AchieveConfig
{
	static $_config=array(1001=>array('type'=>'total_sale','aimNum'=>'1000','levelNeed'=>'1','rewardMoney'=>'1000','rewardRep'=>'100','tag'=>'1001',)
			,1002=>array('type'=>'total_sale','aimNum'=>'5000000','levelNeed'=>'20','rewardMoney'=>'10000','rewardRep'=>'1000','tag'=>'1002',)
			,1003=>array('type'=>'total_sale','aimNum'=>'25000000','levelNeed'=>'30','rewardMoney'=>'50000','rewardRep'=>'5000','tag'=>'1003',)
			,1004=>array('type'=>'total_sale','aimNum'=>'50000000','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','tag'=>'1004',)
			,1005=>array('type'=>'total_count','aimNum'=>'500','levelNeed'=>'1','rewardMoney'=>'1000','rewardRep'=>'100','tag'=>'1005',)
			,1006=>array('type'=>'total_count','aimNum'=>'1000000','levelNeed'=>'20','rewardMoney'=>'10000','rewardRep'=>'1000','tag'=>'1006',)
			,1007=>array('type'=>'total_count','aimNum'=>'2500000','levelNeed'=>'30','rewardMoney'=>'50000','rewardRep'=>'5000','tag'=>'1007',)
			,1008=>array('type'=>'total_count','aimNum'=>'5000000','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','tag'=>'1008',)
			,1015=>array('type'=>'invite_count','aimNum'=>'1','levelNeed'=>'1','rewardMoney'=>'1000','rewardRep'=>'100','tag'=>'1015',)
			,1016=>array('type'=>'invite_count','aimNum'=>'3','levelNeed'=>'1','rewardMoney'=>'10000','rewardRep'=>'1000','tag'=>'1016',)
			,1017=>array('type'=>'invite_count','aimNum'=>'5','levelNeed'=>'10','rewardMoney'=>'50000','rewardRep'=>'5000','tag'=>'1017',)
			,1018=>array('type'=>'invite_count','aimNum'=>'10','levelNeed'=>'20','rewardMoney'=>'100000','rewardRep'=>'10000','tag'=>'1018',)
			,1023=>array('type'=>'popu','aimNum'=>'200','levelNeed'=>'5','rewardMoney'=>'1000','rewardRep'=>'100','tag'=>'1023',)
			,1024=>array('type'=>'popu','aimNum'=>'350','levelNeed'=>'20','rewardMoney'=>'10000','rewardRep'=>'1000','tag'=>'1024',)
			,1025=>array('type'=>'popu','aimNum'=>'450','levelNeed'=>'30','rewardMoney'=>'50000','rewardRep'=>'5000','tag'=>'1025',)
			,1026=>array('type'=>'popu','aimNum'=>'600','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','tag'=>'1026',)
			,1027=>array('type'=>'gogoods_count','aimNum'=>'100','levelNeed'=>'1','rewardMoney'=>'10000','rewardRep'=>'1000','tag'=>'1027',)
			,1028=>array('type'=>'gogoods_count','aimNum'=>'3000','levelNeed'=>'30','rewardMoney'=>'50000','rewardRep'=>'5000','tag'=>'1028',)
			,1029=>array('type'=>'gogoods_count','aimNum'=>'6000','levelNeed'=>'50','rewardMoney'=>'50000','rewardRep'=>'5000','tag'=>'1029',)
			,1030=>array('type'=>'gogoods_count','aimNum'=>'10000','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','tag'=>'1030',)
			,1031=>array('type'=>'advert_count','aimNum'=>'10','levelNeed'=>'20','rewardMoney'=>'10000','rewardRep'=>'1000','tag'=>'1031',)
			,1032=>array('type'=>'advert_count','aimNum'=>'40','levelNeed'=>'30','rewardMoney'=>'50000','rewardRep'=>'5000','tag'=>'1032',)
			,1033=>array('type'=>'advert_count','aimNum'=>'80','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','tag'=>'1033',)
			,1034=>array('type'=>'advert_count','aimNum'=>'200','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','tag'=>'1034',)
			,);
	static function getAchieve ( $tag )
	{
		return self::$_config[$tag];
	}


}
