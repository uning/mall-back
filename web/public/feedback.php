<?php

require_once 'base.php';
$upload_path = dirname(__FILE__).'/upload/';
//file_put_contents('feedback.txt.bak',print_r($_POST,true).print_r($_FILES,true));
if($_POST['message']){
	include 'mail.php';
	$user = json_decode($_POST['user'],true);

	if($_POST['type']==1){
	$title="Sug";
	}else{
	$title="Bug";
	}
	$mail->Subject = "mall feedback:$title";
	$body.="<pre>\n";
	$body .="\n{$_POST['message']}\n----------------------------\n\n";
	$body.='<a href="http://msg.renren.com/SendMessage.do?id='.$user['pid'].'">send xn message</a><br/>';
	$body.='<a href="http://dev.tingkun.com/work/mall/backend/web/renren/update_friends.php?xn_sig_user='.$user['pid'].'">update friend</a><br/>';
	$body.='http://msg.renren.com/SendMessage.do?id='.$user['pid']."\n";
	foreach($user as $k=>$v){
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
