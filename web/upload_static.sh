#!/bin/bash


my_name=`basename $0`
my_loc=`pwd`/`dirname $0`

ldir=$my_loc/renren/static
rdir=/hotel



flash_dir=/home/hotel/work/mall/Venus/to-company
cp -rf $flash_dir/* $ldir/flash

date +'%Y%m%d:%H:%M:%S %s' >$ldir/flash/version.txt
find $ldir/flash -name '*.fla' | xargs rm 

#for s in 61.164.73.19 61.164.73.20 202.98.23.86
for s in 61.164.73.19  202.98.23.86
do
echo "$my_loc/ftpsync.pl -pv -n ftp://4399data:4399_PK45q@$s/$rdir $ldir "
$my_loc/ftpsync.pl -pv -n ftp://4399data:4399_PK45q@$s/$rdir $ldir &

done

sleep 50
cd renren && php  gen_Config.php && cd -
exit


