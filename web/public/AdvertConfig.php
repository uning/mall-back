<?php
class AdvertConfig
{
    static $_config=array(1=>array('name'=>'广告1','popularity'=>'15','maxpopular'=>'30','gem'=>'1','allTime'=>'86400','onlyGem'=>'TRUE','needLevel'=>'1','tag'=>'1',)
,2=>array('name'=>'广告2','popularity'=>'45','maxpopular'=>'60','gem'=>'3','allTime'=>'86400','onlyGem'=>'TRUE','needLevel'=>'1','tag'=>'2',)
,3=>array('name'=>'广告3','popularity'=>'90','maxpopular'=>'90','gem'=>'2','allTime'=>'43200','onlyGem'=>'TRUE','needLevel'=>'1','tag'=>'3',)
,4=>array('name'=>'广告4','popularity'=>'120','maxpopular'=>'120','gem'=>'4','allTime'=>'43200','onlyGem'=>'TRUE','needLevel'=>'1','tag'=>'4',)
,);
    static function getAdvert ( $tag )
    {
        return self::$_config[$tag];
    }
}