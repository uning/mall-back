<?php
require_once('../config.php');
$fbc = new Facebook(array(
  'appId'  => FB::$app_id,
  'secret' => FB::$secret,
  'cookie' => true,
));
$session = $fbc->getSession();
$writer = new  Zend_Log_Writer_Stream(LOG_ROOT.'user_pop.log');
Logger::registerLogger('user_pop',$writer,null,true);
Logger::debug(print_r($_POST,true));
Logger::debug(print_r($_GET,true));
Logger::debug(print_r($session,true));

