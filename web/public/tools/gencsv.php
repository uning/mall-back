<?php
	$fromId  =0;
	$db = TT::LinkTT();
	$time = date('Ymd');
	file_put_contents('stat\collect\data',"");
	$handler = fopen('ex.csv','a');
	for ($index = $fromId; ; ++$index){
		$obj = $db->get($index);
		if($obj)
		{
			if($obj['date'] >= $time) {break;} 
			$str = $obj['date'].",".$obj['type'].','.$obj['clickTime']."\n";
			fwrite($handler,$str);
			$db->remove($index);
		}
		else{break;}
	}
	fclose($handler);
	
	
		
		
	