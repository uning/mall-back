<?php
class AchieveConfig
{
    static $_config=array(1001=>array('name'=>'��͵�','group'=>'total_sale','aimNum'=>'100000','levelNeed'=>'1','rewardMoney'=>'1000','rewardRep'=>'100','rewardIconNum'=>'','tag'=>'1001',)
,1002=>array('name'=>'ʱװ��','group'=>'total_sale','aimNum'=>'5000000','levelNeed'=>'20','rewardMoney'=>'10000','rewardRep'=>'1000','rewardIconNum'=>'','tag'=>'1002',)
,1003=>array('name'=>'����㳡','group'=>'total_sale','aimNum'=>'25000000','levelNeed'=>'30','rewardMoney'=>'50000','rewardRep'=>'5000','rewardIconNum'=>'','tag'=>'1003',)
,1004=>array('name'=>'�鱦��','group'=>'total_sale','aimNum'=>'50000000','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','rewardIconNum'=>'','tag'=>'1004',)
,1005=>array('name'=>'�ӻ���','group'=>'total_count','aimNum'=>'50000','levelNeed'=>'1','rewardMoney'=>'1000','rewardRep'=>'100','rewardIconNum'=>'','tag'=>'1005',)
,1006=>array('name'=>'������','group'=>'total_count','aimNum'=>'1000000','levelNeed'=>'20','rewardMoney'=>'10000','rewardRep'=>'1000','rewardIconNum'=>'','tag'=>'1006',)
,1007=>array('name'=>'����','group'=>'total_count','aimNum'=>'2500000','levelNeed'=>'30','rewardMoney'=>'50000','rewardRep'=>'5000','rewardIconNum'=>'','tag'=>'1007',)
,1008=>array('name'=>'��ҵ��','group'=>'total_count','aimNum'=>'5000000','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','rewardIconNum'=>'','tag'=>'1008',)
,1015=>array('name'=>'����ϲ��','group'=>'friend_count','aimNum'=>'5','levelNeed'=>'1','rewardMoney'=>'1000','rewardRep'=>'100','rewardIconNum'=>'','tag'=>'1015',)
,1016=>array('name'=>'���ܹ�ע','group'=>'friend_count','aimNum'=>'20','levelNeed'=>'5','rewardMoney'=>'10000','rewardRep'=>'1000','rewardIconNum'=>'','tag'=>'1016',)
,1017=>array('name'=>'����ͷ��','group'=>'friend_count','aimNum'=>'50','levelNeed'=>'10','rewardMoney'=>'50000','rewardRep'=>'5000','rewardIconNum'=>'','tag'=>'1017',)
,1018=>array('name'=>'һ����Ӧ','group'=>'friend_count','aimNum'=>'100','levelNeed'=>'20','rewardMoney'=>'100000','rewardRep'=>'10000','rewardIconNum'=>'','tag'=>'1018',)
,1023=>array('name'=>'��������','group'=>'max_popu','aimNum'=>'200','levelNeed'=>'5','rewardMoney'=>'1000','rewardRep'=>'100','rewardIconNum'=>'','tag'=>'1023',)
,1024=>array('name'=>'��ɽ�˺�','group'=>'max_popu','aimNum'=>'350','levelNeed'=>'20','rewardMoney'=>'10000','rewardRep'=>'1000','rewardIconNum'=>'','tag'=>'1024',)
,1025=>array('name'=>'��������','group'=>'max_popu','aimNum'=>'450','levelNeed'=>'30','rewardMoney'=>'50000','rewardRep'=>'5000','rewardIconNum'=>'','tag'=>'1025',)
,1026=>array('name'=>'¢�Ͼ�ͷ','group'=>'max_popu','aimNum'=>'600','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','rewardIconNum'=>'','tag'=>'1026',)
,1027=>array('name'=>'���˹�','group'=>'gogoods_count','aimNum'=>'100','levelNeed'=>'1','rewardMoney'=>'10000','rewardRep'=>'1000','rewardIconNum'=>'','tag'=>'1027',)
,1028=>array('name'=>'�����','group'=>'gogoods_count','aimNum'=>'3000','levelNeed'=>'30','rewardMoney'=>'50000','rewardRep'=>'5000','rewardIconNum'=>'','tag'=>'1028',)
,1029=>array('name'=>'��ݹ�˾','group'=>'gogoods_count','aimNum'=>'6000','levelNeed'=>'50','rewardMoney'=>'50000','rewardRep'=>'5000','rewardIconNum'=>'','tag'=>'1029',)
,1030=>array('name'=>'��ɢ��ͷ','group'=>'gogoods_count','aimNum'=>'10000','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','rewardIconNum'=>'','tag'=>'1030',)
,1031=>array('name'=>'������','group'=>'advert_count','aimNum'=>'10','levelNeed'=>'20','rewardMoney'=>'10000','rewardRep'=>'1000','rewardIconNum'=>'','tag'=>'1031',)
,1032=>array('name'=>'����רԱ','group'=>'advert_count','aimNum'=>'40','levelNeed'=>'30','rewardMoney'=>'50000','rewardRep'=>'5000','rewardIconNum'=>'','tag'=>'1032',)
,1033=>array('name'=>'�����ܼ�','group'=>'advert_count','aimNum'=>'80','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','rewardIconNum'=>'','tag'=>'1033',)
,1034=>array('name'=>'��������','group'=>'advert_count','aimNum'=>'200','levelNeed'=>'50','rewardMoney'=>'100000','rewardRep'=>'10000','rewardIconNum'=>'','tag'=>'1034',)
,);
    static function getAchieve ( $tag )
    {
        return self::$_config[$tag];
    }
}