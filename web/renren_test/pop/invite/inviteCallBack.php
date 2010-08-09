<?php
    echo "<pre>";
    print_r( $_REQUEST );
    echo "</pre>";
    return;
	foreach($_REQUEST as  $str){
		echo $str.'<br>';
	}
?>