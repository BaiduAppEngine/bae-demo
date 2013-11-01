<?php
/**
 *BaeLog示例， 利用BaeLog打印“轨迹”、“通知”、“调试”、“警告”和 “致命”日志。
 */
require_once "../configure.php";
require_once "../sdk/BaeLog.class.php";
 

	$user = ServiceConf::$aksk['ak'];
	$pwd = ServiceConf::$aksk['sk'];
	$level = ServiceConf::$log_cfg['level'];
	$logger=BaeLog::getInstance(array('user'=>$user, 'passwd'=> $pwd));
	$logger->setLogLevel(16);
	//打印一条轨迹日志
	$logger ->Trace("this is for trace log print ");
	//打印一条通知日志
	$logger ->Notice("this is for notice log print ");
	//打印一条调试日志
	$logger ->Debug("this is for debug log print ");
	//打印一条警告日志
	$logger ->Warning("this is for warning log print ");
	//打印一条致命日志
	$logger ->Fatal("this is for fatal log print ");
	echo 'Log OK, Please Check Your Log Service For Detail Infomation';
?>
