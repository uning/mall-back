#!/bin/bash
date=`date +"%Y-%m-%d"`
work_dir=/home/hotel/work/mall/backend/stat
log=$work_dir/log
cd $work_dir/collect/ && /home/hotel/work/sys/php/bin/php  user_history.php >>$log/out.user_history 2>>$log/err.user_history &
cd $work_dir/collect/ && /home/hotel/work/sys/php/bin/php  log_history.php   >>$log/out.log_history 2>>$log/err.log_history &
