<?php
class TaskConfig
{
    static $_config=array(300=>array('level'=>'1','guestlooks'=>'4,4,4,4,4,4','goals'=>'10101,10103','money'=>'500','reputation'=>'50','tag'=>'300',)
,301=>array('level'=>'1','guestlooks'=>'4,4,4,4,4,4','goals'=>'10102,10104','money'=>'500','reputation'=>'50','tag'=>'301',)
,302=>array('level'=>'1','guestlooks'=>'5,5,5,5,5,5','goals'=>'10105,10106','money'=>'500','reputation'=>'50','tag'=>'302',)
,303=>array('level'=>'2','guestlooks'=>'5,5,5,5,5,5','goals'=>'10103,10107','money'=>'500','reputation'=>'50','tag'=>'303',)
,304=>array('level'=>'2','guestlooks'=>'6,6,6,6,6,6','goals'=>'10101,10108','money'=>'500','reputation'=>'50','tag'=>'304',)
,305=>array('level'=>'4','guestlooks'=>'6,6,6,6,6,6','goals'=>'10106,10109','money'=>'500','reputation'=>'50','tag'=>'305',)
,306=>array('level'=>'5','guestlooks'=>'7,7,7,7,7,7','goals'=>'10110,10111','money'=>'500','reputation'=>'50','tag'=>'306',)
,307=>array('level'=>'6','guestlooks'=>'7,7,7,7,7,7','goals'=>'10105,10110,10112','money'=>'1000','reputation'=>'100','tag'=>'307',)
,308=>array('level'=>'7','guestlooks'=>'8,8,8,8,8,8','goals'=>'10107,10109,10112','money'=>'1000','reputation'=>'100','tag'=>'308',)
,309=>array('level'=>'8','guestlooks'=>'8,8,8,8,8,8','goals'=>'10104,10107,10111,10112','money'=>'3000','reputation'=>'200','tag'=>'309',)
,310=>array('level'=>'9','guestlooks'=>'9,9,9,9,9,9','goals'=>'10101,10107,10109,10112','money'=>'3000','reputation'=>'200','tag'=>'310',)
,311=>array('level'=>'11','guestlooks'=>'9,9,9,9,9,9','goals'=>'10101,10102,10103,10104,10105,10107,10108','money'=>'3000','reputation'=>'200','tag'=>'311',)
,);
    static function getTask ( $tag )
    {
        return self::$_config[$tag];
    }
}