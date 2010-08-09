<?php
class RenrenConfig
{

	public static $api_key = "975b170745ce4b4d9fe138df194a5535";
	public static $secret = "411cd6a100584d50b8d93bd603f26ceb";
	public static $app_id = 75552;
	public static $canvas_url = "http://apps.renren.com/happyhotel/mall/";
	public static $reciever_url = "xd.html";

	public static $callback_url = "http://s1.coolplusplus.com/bg/renren/mall/";
	public static $resource_urlp = "http://rrmall.playcrab.com/work/mall/backend/web/renren/static/";
	public static $ajax_urlp = "/mall/ajax/"; //this can be all the same??move to backend
	public static $pay_secure = 'a0901b';
	public static $group_url = "http://group.renren.com/GetTribe.do?id=301354649";

}

$myloc=dirname(__FILE__);
define('FB_CURR',$myloc);
require_once  FB_CURR.'/../public/base.php'; 
require_once  FB_CURR.'/lang/zhtw.php'; 

