<?php
/**
 * BaeMemcache示例，通过示例可快速熟悉BaeMemcache服务的使用方法
 */
require_once '../configure.php';
require_once '../sdk/BaeMemcache.class.php';

	/***Cache配置信息***/
	$cacheid = ServiceConf::$cache_cfg['cacheid'];
	$host = ServiceConf::$cache_cfg['host'];
	$port = ServiceConf::$cache_cfg['port'];
	$user = ServiceConf::$aksk['ak'];
	$pwd = ServiceConf::$aksk['sk'];
	define("TIMEOUT", 2);
	$key = "foo1";
	$value = 1;
	$memcache = new BaeMemcache($cacheid, $host.':'.$port, $user, $pwd);
	function printOpResult($num, $flag){
        global $key, $value;
		if($flag){
			echo "Time ". $num . ":add $key => $value success!". "<br/>";
		}else{
			echo "Time ". $num . ":add operation failed!". "<br/>";
		}
	}
	
	/************************Add and Delete Begin*********************/
	//清空cache中关于$key的值
	$memcache->delete($key);
	//第一次向cache中增加一条key:value,并且永久有效
	$retVal = $memcache->add($key,$value);
	printOpResult(1, $retVal);
	
	//第二次向cache中增加一条key:value,操作会失败,因为key已经存在
	$retVal = $memcache->add($key,$value);
	printOpResult(2, $retVal);
	//删除cache中的key
	$memcache->delete($key);
	
	//第三次向cache中增加一条key:value，并设置失效时间为TIMEOUT
	$retVal = $memcache->add($key,$value,0, TIMEOUT);
	printOpResult(3, $retVal);
	
	//do somethig else
	sleep(3);
	
	//第四次向cache中增加一条key:value，成功
	$retVal = $memcache->add($key,$value);
	printOpResult(4, $retVal);
	/************************Add and Delete End*********************/
	
	/*******************Set Get and Replace Begin******************/
	//向cache中增加一条key:value,并且永久有效
	//当$key不存在时,功能相当于add()
	$retVal = $memcache->set($key,$value);
	printOpResult(5, $retVal);
	//修改已有的key所对应的值
	$retVal = $memcache->set($key,"abc");
	echo "The new value is ". $memcache->get($key) . "<br/>";
	//删除cache中的key
	$memcache->delete($key);
	
	//使用replace,当key不存在时,则返回false
	$retVal = $memcache->replace($key, $value);
	printOpResult(6, $retVal);
	//当key存在时,功能和set一样
	$retVal = $memcache->add($key,$value);
	printOpResult(7, $retVal);
	$retVal = $memcache->replace($key, "abc");
	echo "The new value is ". $memcache->get($key) . "<br/>";
	
	//删除cache中的key
	$memcache->delete($key);
	/*******************Set Get and Replace End******************/
	
	/*****************Increment and Decrement Begin***************/
	function caculator($isAdd, $key, $offset){
		global $memcache;
		if($isAdd === true){
			return $memcache->increment($key, $offset);
		}
		return $memcache->decrement($key, $offset);
	}
	$retVal = $memcache->add($key,$value);
	printOpResult(8, $retVal);
	echo "Increment: " . caculator(true, $key, 2). "<br/>";
	echo "Decrement: " . caculator(false, $key, 2). "<br/>";
	//删除cache中的key
	$memcache->delete($key);
	/*****************Increment and Decrement End***************/
?>
