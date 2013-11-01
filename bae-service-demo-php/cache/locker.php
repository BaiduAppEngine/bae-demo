<?php
/**
 * BaeMemcache示例，使用Memcache服务实现了加锁功能
 */
require_once '../configure.php';
require_once '../sdk/BaeMemcache.class.php';

	/***Cache配置信息***/
	$cacheid = ServiceConf::$cache_cfg['cacheid'];
	$host = ServiceConf::$cache_cfg['host'];
	$port = ServiceConf::$cache_cfg['port'];
	$user = ServiceConf::$aksk['ak'];
	$pwd = ServiceConf::$aksk['sk'];
	define("LOCK_TIMEOUT", 5);
	$key = "foo";
	$memcache = new BaeMemcache($cacheid, $host.':'.$port, $user, $pwd);
	$memcache->delete($key);
	$value = 1;
	$lock = $memcache->add($key, $value, 0, LOCK_TIMEOUT);
	if($lock){
		echo "There is no lock on this key:$key, do whatever you want <br/>";
		echo $memcache->increment($key). "<br/>";
		echo "...increment operation finished! Release lock on the key:$key <br/>";
		$memcache->delete($key);
	}
	else{
		echo "The key:$key is currently locked, please wait or do something else.<br/>";
	}
?>
	
