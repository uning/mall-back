<?php
require_once 'base.php';
echo ZEND_ROOT."\n";
require_once 'Zend/Mail.php';
require_once 'Zend/Mail/Transport/Smtp.php';
$config = array('auth' => 'login','username' =>'monitor@playcrab.com','password' => '5W5772','ssl' => 'tls','port' => '587');

$transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
$mail = new Zend_Mail('UTF-8');
//$mail->setBodyText('This is the text of the mail.');
$mail->setFrom('system@playcrab.com', 'Mall FeedBack');
$mail->addTo('tingkun@playcrab.com', 'Shen');
//$mail->setSubject('Zend Mail!');
//$mail->send($transport); 
