#!/bin/bash
date=`date +"%Y-%m-%d"`
work_dir=/home/hotel/work/mall/backend/stat
log=$work_dir/log
cd $work_dir/collect/ && /home/hotel/work/sys/php/bin/php  user_history.php >>$log/log.$date 2>>$log/err.$date &
cd $work_dir/collect/ && /home/hotel/work/sys/php/bin/php  log_history.php   >>$log/log.$date 2>>$log/err.$date &
