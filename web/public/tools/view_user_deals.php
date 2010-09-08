<?php
$myloc = dirname(__FILE__);
require_once('../base.php');
echo "<pre>\n";

$u=$argv[1];
if(!$u){
	$u=$_REQUEST['u'];
}
if(!$u){
	$pid = $argv[2];
	if(!$pid){
		$pid=$_REQUEST['pid'];
	}
	if($pid){
		$data = TTGenid::getbypid($pid);
		print_r($data);
		$u = $data['id'];
	}
}
$op=$argv[2];
if(!$op)
   $op = $_REQUEST['op'];
$t=$argv[3];
if(!$t)
   $t = $_REQUEST['t'];
$tm=$argv[4];
if(!$tm)
   $t = $_REQUEST['tm'];


$now = time();
$gemt = TT::GemTT();
$q = $gemt->getQuery();
if($u) 
  $q->addCond('u', TokyoTyrant::RDBQC_NUMEQ,$u);
if($op) 
  $q->addCond('op', TokyoTyrant::RDBQC_STREQ,$op);
if($t) 
  $q->addCond('t', TokyoTyrant::RDBQC_STREQ,$t);
if($tm) 
  $q->addCond('tm', TokyoTyrant::RDBQC_NUMGE,$tm);
$q->setLimit(100);
print_r($q->search());
