<?php
require_once 'base.php';
$dir = WEB_ROOT."tests/request/";
echo "<script type='text/javascript'>
   function showhide(id)
   {
     var dd = document.getElementById(id)
     if(dd.style.display=='none')
        dd.style.display='block';
     else
       dd.style.display='none';
  }
</script>";

echo "in $dir:<br/>";

echo "<input type=button value=刷新 onclick='location.reload()'>";
// open a known directory, and proceed to read its contents
$filenames=array();
if (is_dir($dir)) {
	$files = scandir($dir);
	$output = array();
	foreach($files as $key=>$file){
		if($file!="." && $file!=".." ){
			$filepath = $dir.$file;
			$time =    date("Y-m-d H:i:s", filemtime($filepath));
			$filenames[$time.$file]=array('file'=>$file,'time'=>$time);
		}

	}
	krsort($filenames);
	$i=0;
	foreach($filenames as $key=>$fileo){
		$time = $fileo['time'];
		$file = $fileo['file'];
		$i++;
		$filepath = $dir.$file;
		$out = "<p><a href='javascript:void(0);' onclick=showhide('$file');><b>$file</b> ".$time."</a></p>"."\n";
		$out.= "<div style='display:none' id='".$file."'><pre>".file_get_contents($filepath)."</pre></div>";
		echo  $out;
		if($i>10)
		break;
	}
}


