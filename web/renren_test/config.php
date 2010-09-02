<?php
class RenrenConfig
{
   public static  $api_key = '8ad12ca6f37e4245807ffe1d06c764ec';
   public static  $secret  = 'e69c45adbc444428a454d72ff783c9e4';
   public static  $app_id  = '113391';
   public static  $canvas_url = "http://apps.renren.com/malltest/";
   public static  $callback_url = "http://dev.tingkun.com/work/mall/backend/web/renren_test/";
   public static  $canvas_name = "malltest";

   public static  $reciever_url = "/xd.html";
   
   public static  $resource_urlp = "http://192.168.1.50/work/mall/backend/web/renren_test/static/";
   //public static  $resource_urlp = "http://files5.qq494.cn/pig/hotel/";
   //public static  $iframe_urlp = "http://supermall.playcrab.com/inif/";
   public static $group_url = "http://group.renren.com/GetTribe.do?id=336701942";
   public static $pay_secure = 'a0901b';

}

$myloc=dirname(__FILE__);
define('FB_CURR',$myloc);
require_once  FB_CURR.'/../public/base.php'; 
require_once  FB_CURR.'/lang/zhtw.php'; 

