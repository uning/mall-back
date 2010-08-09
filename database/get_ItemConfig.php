<?php
    $f = fopen( "ItemConfig.php",'r');
    $jason = file_get_contents("ItemConfig.php");
    print_r( $jason );
    $content = unserialize( $jason );
    print_r( $content );
    
    
