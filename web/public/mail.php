<?php
require_once(LIB_ROOT.'/phpmailer/class.phpmailer.php');

$mail             = new PHPMailer();
$mail->IsSMTP(true);
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port for the GMAIL server

$mail->Username   = "monitor@playcrab.com";  // GMAIL username
$mail->Password   = "5W5772";            // GMAIL password

$mail->AddReplyTo("system@playcrab.com","System");

$mail->From       ="monitor@playcrab.com";
$mail->FromName   = "mall feed back";

$mail->Subject    =  '';

$mail->Body       = '';
$mail->AltBody    = "请使用支持html的客户端查看邮件"; // optional, comment out and test
$mail->WordWrap   = 50; // set word wrap

$mail->AddAddress("all@playcrab.com", "all");
//$mail->AddAddress("tingkun@playcrab.com", "tingkun");
$mail->IsHTML(true);
