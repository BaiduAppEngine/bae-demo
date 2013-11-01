#!/bin/bash


##############################################
#User config part
php_path=/home/bae/bae/php/bin/php

##################################################
log_path=./log
check_log=check_log

################################################

echo -ne "\nCheck log store in $log_path/$check_log\n"
echo -ne "\nPlease wait ..."
$php_path -n batch_upload_check.php $log_path > $log_path/$check_log
echo -ne "\n\nFinished\n"
