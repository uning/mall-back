#!/bin/bash
date=`date +"%Y-%m-%d"`
work_dir=/home/hotel/work/mall/backend/stat
log=$work_dir/log
cd $work/collect/ && php  user_history.php >>$log/runlog.$date 2>>$log/runerr.$date
cd $work/collect/ && php  log_history.php   >>$log/runlog.$date 2>>$log/runerr.$date
