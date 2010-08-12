<?php

require_once 'base.php';
$upload_path = dirname(__FILE__).'/upload/';
file_put_contents('feedback.txt.bak',print_r($_POST,true).print_r($_FILES,true));
if($_POST['message']){
	include 'mail.php';
	$mail->Subject = 'feedback:'.$_POST['userdbid'].'('.$_POST['userpid'].')';
	$body.="<pre>\n";
	$body .="\n{$_POST['message']}\n";
	foreach($_POST as $k=>$v){
		if($k!='message')
			$body .="$k:$v\n";
	}
	//$_SERVER['PHP_SELF'];

	$body.="</pre>\n";
	$mail->Body=$body;
	
	foreach($_FILES as $f=>$v){
		if($v['tmp_name']){
			$file = $upload_path.uniqid().'.jpg';
			move_uploaded_file($v['tmp_name'],$file);
			@$mail->AddAttachment($file,"$f.jpg");
			//@$mail->AddAttachment($v['tmp_name'],"$f.jp);
		}
	}
	//@$mail->AddAttachment($upload_path.'0.jpg',"att.jpg");
	echo 'OK';
	@ob_end_flush();
	$mail->send();
	return;
}
