<?php 
    include "config.php";
    $pid = $_POST['xn_sig_user'];
    $ar['pid']=$pid; 
    $ar['ut']=time();; 
    $sess = TTGenid::genid($ar);
