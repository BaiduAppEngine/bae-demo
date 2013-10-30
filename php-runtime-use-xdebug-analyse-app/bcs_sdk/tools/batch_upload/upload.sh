#!/bin/bash


##############################################
#User config part
thread_num=16
php_path=/home/bae/bae/php/bin/php

##################################################
log_path=./log
log_bak_path=$log_path/bak/`date -d +0day +%Y%m%d%H%M%S`

##################################################
#valid thread_num
if [ ! $thread_num -gt 0 ]; then
	echo -ne "Please check your thread_num config.\n"
	exit
fi
#make log dir and backup old log
mkdir -p $log_path
if [ -f $log_path/upload_log_0 ]; then
	mkdir -p $log_bak_path
	mv $log_path/upload_log* $log_bak_path
	echo -ne "\nOld log file back up in $log_bak_path\n\n"
fi
if [ -f $log_path/check_log ]; then
	mkdir -p $log_bak_path
	mv $log_path/check_log $log_bak_path
fi
echo -ne "\nNew log file is stored in $log_path\n\n"

##################################################
#run php scripts
for i in $( seq 0 $((--thread_num)) ) ;
do `$php_path -n batch_upload_dir.php $thread_num $i > $log_path/upload_log_$i &` ; 
done;
