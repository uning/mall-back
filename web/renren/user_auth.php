<?php 
    include "config.php";
    $pid = $_POST['xn_sig_user'];
    $ar['pid']=$pid; 
    $ar['authat']=time();; 
    $sess = TTGenid::genid($ar);
 
