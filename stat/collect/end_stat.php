<?php

$vars=print_r($dgr,true);
echo $vars;
$mail_body.=$vars;
store_varible($dgr);
$cmd = "mysql -u{$dbconfig['username']} -P{$dbconfig['port']}  -h{$dbconfig['host']} ";
if($dbconfig['password']){
	$cmd.=" -p'{$dbconfig['password']}' ";
}
$cmd .= $dbconfig['dbname'];
$cmd .=' -e "LOAD DATA INFILE \''.$uhfname.'\' INTO TABLE '.$table.'  FIELDS TERMINATED BY \',\' ESCAPED BY \'\\\\\\\' LINES TERMINATED BY \'\n\';"';         
echo $cmd;
$ret = system($cmd);
fclose($uhf);
$mail_body.= $cmd;
$mail_body.= "load ret:$ret\n";
include('mail.php');
$end_time = time();
$mail->Subject = "Mall renren stat $table ".$datestr;
$cost=$end_time - $now;
$mail_body.="\ncost time:$cost\n";
$mail->Body = $mail_body;
$mail->send();
