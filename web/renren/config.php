<?php
class RenrenConfig
{
   public static  $api_key = 'a32cb73bea154d2c9d40703b66dc9142';
   public static  $secret  = '023a6201a9b04955b1af79b1e9037c16';
   public static  $app_id  = '110107';
   public static  $canvas_url = "http://apps.renren.com/livemall/";
   public static  $callback_url = "http://rrmall.playcrab.com/";
   public static  $canvas_name = "livemall";

   public static  $reciever_url = "/xd.html";
   
   //public static  $resource_urlp = "http://rrmall.playcrab.com/static/";
   public static  $resource_urlp = "http://files5.qq494.cn/pig/hotel/";
   //public static  $iframe_urlp = "http://supermall.playcrab.com/inif/";
   public static $group_url = "http://group.renren.com/GetTribe.do?id=301354649";
   public static $pay_secure = 'a0901b';

}

$myloc=dirname(__FILE__);
define('FB_CURR',$myloc);
require_once  FB_CURR.'/../public/base.php'; 
require_once  FB_CURR.'/lang/zhtw.php'; 

