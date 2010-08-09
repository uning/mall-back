<?php



function record_time(&$start,$usage="",$unit=0)
{
    $end  = microtime(true);
    $cost=$end-$start;
    $cost=ceil(1000000*$cost);
    if($unit>0){
        $cost = ceil($cost/$unit);
    }
    if($usage)
        echo "$usage use time $cost us\n";
    $start = $end;
}

function dodebug($str)
{
	echo "$str=";eval('echo  ' .$str.';');echo "\n";
}

record_time($st);
//require_once('TTt.php');
require_once('TT.php');

$tw = TT::TTWeb();
$key = '12323';
$data['id']=$key;
$data['vv']=array('fdfd','dd');
$str='$id=$tw->puto($data)';
echo "$str=";record_time($st);eval('print_r(  ' .$str.');');record_time($st,$str);echo "\n";
$str='$id=$tw->getbyid($key)';
echo "$str=";record_time($st);eval('print_r(  ' .$str.');');record_time($st,$str);echo "\n";
$data['newf']='dsfsdfds';

$str='$id=$tw->puto($data)';
echo "$str=";record_time($st);eval('print_r(  ' .$str.');');record_time($st,$str);echo "\n";
$str='$id=$tw->getbyid($key)';
echo "$str=";record_time($st);eval('print_r(  ' .$str.');');record_time($st,$str);echo "\n";
return;
class Test{
	static function test_TTExtend(){
		$t = TT::get_tt('web');
		$oid = 'keyid1:o';
	        $data['id']=$oid;
		$data['name']='name';
                $data['add']=array('dff'=>'add',2=>3445);	
		
		$str='$id=$t->puto($data)';
		echo "$str=";eval('print_r(  ' .$str.');');echo "\n";
		$str='$t->getbyid($id)';
		echo "$str=";eval('print_r(  ' .$str.');');echo "\n";
		$data =array();
	        $data['id']=$oid;
		$data['name']='new name';
		$data['col']='add new name';
		$str='$id=$t->puto($data,true)';
		echo "$str=";eval('print_r(  ' .$str.');');echo "\n";
		$str='$t->getbyid($id)';
		echo "$str=";eval('print_r(  ' .$str.');');echo "\n";
		$str='$id=$t->puto($data,false)';
		echo "$str=";eval('print_r(  ' .$str.');');echo "\n";
		$str='$t->getbyid($id)';
		echo "$str=";eval('print_r(  ' .$str.');');echo "\n";

		$str='$t->numch("add",1)';
		echo "$str=";eval('print_r(  ' .$str.');');echo "\n";
		$str='$t->numch("add",1)';
		echo "$str=";eval('print_r(  ' .$str.');');echo "\n";
		$ids =array('add',':d:df:new',$oid);
		$str='$t->getbyids($ids)';
		echo "$str=";eval('print_r(  ' .$str.');');echo "\n";
		$t->put(':d:df:new','ddffffff');
		$str='$t->get("new")';
		echo "$str=";eval('print_r(  ' .$str.');');echo "\n";
		$str='$t->get("new")';
		echo "$str=";eval('print_r(  ' .$str.');');echo "\n";
	}
	static function testTTUDB()
	{
		$u = 1001;
		$tn = new TTUDB($u);

		$group = 'g1';
                $now = time();
		record_time($st);
		$data_with_noid =array('me'=>'im $data_with_noid','oth'=>array('dffd','dfd'));
                $data_with_noid['tm']=$now;
		$tn->puto($data_with_noid,'group');
		print_r($data_with_noid);
		record_time($st,'print_r($data_with_noid);');
		
		print_r($tn->get());
		record_time($st,'$tn->get()');
		
		print_r($tn->getbyid($data_with_noid['id']));
		record_time($st,'$tn->getbyid($data_with_noid["id"])');

		print_r($tn->getbyids(array($data_with_noid['id'])));
		record_time($st,'$tn->getbyids(array($data_with_noid["id"]))');

                $data_with_id['id']=$data_with_noid['id'];
                $data_with_id['new']='im new field';
                $data_with_id['me']='im new field';
               
                print_r($data_with_id);
		$tn->puto($data_with_id,'g');
                print_r($data_with_id);
                record_time($st,'$tn->puto($data_with_id,$u,\'g\',$t)');
              

		print_r($tn->getbyid($data_with_noid['id']));
		record_time($st,'$tn->getbyid($data_with_noid["id"],$u)');
                $data_with_id=array();
                $data_with_id['id']=$data_with_noid['id'];
                $data_with_id['new']='im new field nokeep';
                $data_with_id['me']='im new field nokeep';
		$tn->puto($data_with_id,'g',false);
                print_r($data_with_id);
                record_time($st,'$tn->puto($d:wata_with_id,$u,\'g\',$t,false)');


               //for field
		$tn->numch("money");
		$tn->numch("gem");
		record_time($st,'$tn->numch("money")');
		record_time($st,'$tn->getf("money")='.print_r($tn->getf($u,"money"),true));
		record_time($st,'$tn->getf(array("money"))='.print_r($tn->getf(array("money")),true));
		record_time($st,'$tn->getf()='.print_r($tn->getf(),true));

                $pair['k1']='v1';
                $pair['k2']='v2';
                $pair['k3']='v3';
                $tn->mputf($pair);
                $tn->putf('putf','i am insert by putf');
                record_time($st,' put pair');
		record_time($st,'$tn->numch("money")');
		record_time($st,'$tn->getf("money")='.print_r($tn->getf("money")));
		record_time($st,'$tn->getf(array("money"))='.print_r($tn->getf(array("money"))));
		record_time($st,'$tn->getf(array("putf","money"))='.print_r($tn->getf(array("money",'putf'))));
		record_time($st,'$tn->getf($u)='.print_r($tn->getf($u)));
                  
                
                $data=array('me'=>'im in a array');
		$data['index'] = count($datas);
                $datas[]=$data;
		$data['index'] = count($datas);
                $datas[]=$data;
		$data['index'] = count($datas);
                $datas[]=$data;
		record_time($st,'$tn->mputo($datas,$u)='.print_r($tn->mputo($datas,'new_group')));
		record_time($st,'$tn->getf($u,new_group)='.print_r($tn->get('new_group')));
		
		for( $i=1;$i<11;$i++ ){
			$achieve = array();
			$achieve['tag'] = $i;
			$achieve['is_done'] = '0';
			$achieve['finish_num'] = '0';

			$tn->puto( $achieve,TT::ACHIVE_GROUP );
			$ret['ac'][]=$achieve;
		}
		$str = '$tn->get( TT::ACHIVE_GROUP )';
		echo "$str=";$record_time($st);eval('print_r(  ' .$str.');');record_time($st,$str);echo "\n";
		$str = '$tn->numch("newdd")';
		echo "$str=";eval('print_r(  ' .$str.');');echo "\n";
		$str = '$tn->getAll()';
		echo "$str=";eval('print_r(  ' .$str.');');echo "\n";
		

	}
	static function testGenId()
	{

		echo "test genid\n";
		$data['pid']='uning';
		print_r($data = TTGenid::genid($data));
		$data['newdatapid']='uning1';
		print_r(TTGenid::update($data));
		print_r(TTGenid::genid($data));
		$data['newdatapid']='uning1 update iii';
		$data['i newdatapid']='uning1 update iii';
		print_r(TTGenid::update($data));
		print_r(TTGenid::genid(array('pid'=>'uning')));
	}
		
	static function testTTable()
	{
		$tt  = TT::LogTT();
		$data =  array( 'pid'=>'test','name'=>'tingkun','ad'=>array('new table','df'=>'df'));     
		$str = '$id = $tt->put($data)';
		echo "$str=";record_time($st);eval('print_r(  ' .$str.');');record_time($st,$str);echo "\n";
		$str = '$id = $tt->get($id)';
		echo "$str=";record_time($st);eval('print_r(  ' .$str.');');record_time($st,$str);echo "\n";
		$str = '$id = $tt->getbyidx("pid","test")';
		echo "$str=";record_time($st);eval('print_r(  ' .$str.');');record_time($st,$str);echo "\n";
				
	}

	public function  test_tt()
	{
		$tu = new TTUser(1);
		$tt = TT::get_tt('main',1);
		$id = $tu->getdid(TT::SHOP_NUM);
		$str = '$tu->numch(TT::SHOP_NUM,$shop_num)';
		echo "$str=";record_time($st);eval('print_r(  ' .$str.');');record_time($st,$str);echo "\n";
		$shop_num=10;
		$str ='$tu->numch(TT::SHOP_NUM,$shop_num)';
		echo "$str=";record_time($st);eval('print_r(  ' .$str.');');record_time($st,$str);echo "\n";
		$shop_num=-10;
		$str ='$tu->numch(TT::SHOP_NUM,$shop_num)';
		echo "$str=";record_time($st);eval('print_r(  ' .$str.');');record_time($st,$str);echo "\n";

		$tu->putf('strint','0000');
		$str = '$tu->getf("strint")';
		echo "$str=";record_time($st);eval('print_r(  ' .$str.');');record_time($st,$str);echo "\n";
		$shop_num=-10;
		$str = '$tu->numch("strint",$shop_num)';
		echo "$str=";record_time($st);eval('print_r(  ' .$str.');');record_time($st,$str);echo "\n";
		$shop_num=10;
		$str = '$tu->numch("strint",$shop_num)';
		echo "$str=";record_time($st);eval('print_r(  ' .$str.');');record_time($st,$str);echo "\n";



		$tt->put("strint",'a');
		$str = '$tt->get("strint")';
		echo "$str=";record_time($st);eval('print_r(  ' .$str.');');record_time($st,$str);echo "\n";
		$str = '$tt->numch("strint",100)';
		echo "$str=";record_time($st);eval('print_r(  ' .$str.');');record_time($st,$str);echo "\n";
		$str = '$tt->numch("strint",100)';
		echo "$str=";record_time($st);eval('print_r(  ' .$str.');');record_time($st,$str);echo "\n";
                

	}


};
//Test::test_tt();
//testFeed();

/*
Test::testTTable();
exit;
Test::testTTUDB();
//Test::test_TTExtend();
exit;
*/
$u = 10;
$tu = new TTUser($u);
$f = 'gem';
$f = 'money';
$num = 1000;
$ret[]=$tu->change($f,-$num);
$ret[]=$tu->change($f,100);
$ret[]=$tu->change($f,100);
$ret[]=$tu->change($f,100);
$ret[]=$tu->change($f,0);
$ret[]=$tu->change($f,-100);
$ret[]= $tu->getf($f);
print_r($ret);
//*/
//Test::testTTDB();

exit;
//**
