<?php
$xmlfile = '../../../Venus/src/resource/simplifiedchinese/xml/tasks.xml';
$xml =  simplexml_load_file($xmlfile);
foreach($xml->tasks->task as $t)
{
	$id = $t['id'];
	$name = $t['name'];
	echo "'$id'=>array('name'=>'$name','award'=>array(";
	$money=$t['money'];
	if($money)
		echo "'money'=>$money,";
	$money=$t['exp'];
	if($money)
		echo "'exp'=>$money";
	
	echo "),";
	$award = $t['award'];
	if($award){
		$awardnum=$t['awardnum'];
		if(!$awardnum)
			$awardnum = 1;
		echo "'items'=>array(array('tag'=>$award,'num'=>$awardnum)) "; 
	}
	echo ") ,\n";
}

