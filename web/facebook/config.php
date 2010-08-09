<?php

class FB
{
   public static  $api_key = 'dfb8a2519c7c25a09a7a22137c40d7cc';
   public static  $secret  = '60d180ac578ce34093b3ce2d1d450f84';
   public static  $app_id  = '137622979600122';
   public static  $canvas_url = "http://apps.facebook.com/dailymall/";
   public static  $callback_url = "http://supermall.playcrab.com/";
   public static  $canvas_name = "dailymall";
   public static  $reciever_url = "/xd_receiver.htm";
   
   //public static  $resource_urlp = "http://m1.hotel.6waves.com/mall/static/";
   //public static  $resource_urlp = "static/";
   //public static  $resource_urlp = "http://192.168.1.50/work/mall/backend/web/facebook/static/";
   public static  $resource_urlp = "/work/mall/backend/web/facebook/static/";
   //public static  $iframe_urlp = "http://supermall.playcrab.com/inif/";
   public static  $ajax_urlp = "/ajax/"; //this can be all the same��move to backend

   //public static   $stat_url='stat.php';
   
  
  

  
};
$myloc=dirname(__FILE__);
define('FB_CURR',$myloc);
require_once  FB_CURR.'/../public/base.php'; 
//require_once  LIB_ROOT.'newfb/src/facebook.php';
//require_once  LIB_ROOT.'facebook/facebook.php'; 
require_once(FB_CURR.'/lang/zhtw.php');

