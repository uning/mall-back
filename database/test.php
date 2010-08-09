<?php
    /*
    require_once dirname(__FILE__)."/ItemConfig.php";
	$item = ItemConfig::getItem( 10102 );
	print_r( $item );
	*/
	require_once dirname(__FILE__)."/UpgradeConfig.php";
	/*
	$level = UpgradeConfig::getLevel(1000);
	print_r( $level );
	*/
	$all = UpgradeConfig::getUpgradeNeed( 1000 );
	print_r( $all );