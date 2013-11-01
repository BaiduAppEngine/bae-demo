<?php
/**
 * Redis示例，通过该示例可熟悉BAE平台Redis服务的基本使用
 */
require_once("../configure.php");
	/*连接数据库所需要的五要素（可从数据库详情页中查到相应信息）*/
	$dbname = ServiceConf::$redis_cfg['dbname'];;
	$host = ServiceConf::$redis_cfg['host'];
	$port = ServiceConf::$redis_cfg['port'];;
	$user = ServiceConf::$aksk['ak'];;
	$pwd = ServiceConf::$aksk['sk'];;
	 
	try {
	    /***** 建立连接后，在进行集合操作前，需要先进行auth验证*****/
	    $redis = new Redis();
	    $ret = $redis->connect($host, $port);
	    if ($ret === false) {
			die($redis->getLastError());
	    }
	 
	    $ret = $redis->auth($user . "-" . $pwd . "-" . $dbname);
	    if ($ret === false) {
			die($redis->getLastError());
	    }
	 
	    /***** 接下来就可以对该库进行操作了，具体操作方法请参考phpredis官方文档*****/
      	//删除所有redis数据库中的键值
	    $redis->flushdb();
      	stringOperations($redis);
        listOperations($redis);
        setOperations($redis);
	    $ret = $redis->set("key", "value");
	    if ($ret === false) {
			die($redis->getLastError());
	    } 
	    echo "OK";
	 
	} catch (RedisException $e) {
	    die("Uncaught exception " . $e->getMessage());
	}
	
	function stringOperations($redis){
      	echo "----------------------String Operations-------------------------<br/>";
      	//简单的key-value设置
      	$redis->set("name", "bae");
      	echo "name | " . $redis->get("name");
      	echo "<br/>";
      	//不支持append操作
      	//multiple set/get，一次设置/获取多组key-value
      	$redis->mSet(array('logintimes'=>'100','logouttimes'=>'80'));
      	$values = $redis->mGet(array("name","logintimes","logouttimes"));
      	var_dump($values);
      	echo "<br/>";
      
      	//increment/decrement操作
      	echo "increment/decrement operation:<br/>";
      	$redis->incrBy("logouttimes", 10);
      	$redis->decrBy("logintimes", 10);
     	echo "logintimes \t logouttimes <br/>";
      	echo $redis->get("logintimes") . "\t\t" . $redis->get("logouttimes");
      	echo "<br/>";
      
      	//delete operations
      	echo "delete name:bae";
      	$redis->del("name");
      	echo "<br/>";
	}
	
	function listOperations($redis){
      	echo "----------------------List Operations-------------------------<br/>";
      	//向名为listdemo的链表中插入数据
      	$redis->lPush("listdemo", "Engine");
      	$redis->lPush("listdemo", "Application");
      	$redis->lPush("listdemo", "Baidu");
      
      	//获取链表中的指定部分
      	$values = $redis->lRange("listdemo",0, 2);
      	var_dump($values);
      	echo "<br/>";
      	
      	//去除链表尾部的值
      	$redis->rPop("listdemo");
      	
      	//修改链表中的值
      	$redis->lSet("listdemo", 1, "Redis");
      	$values = $redis->lRange("listdemo",0,1);
      	var_dump($values);
      	echo "<br/>";
      	//删除链表
      	$redis->del("listdemo");
      	echo "<br/>";
    
    }
	
	function setOperations($redis){
      	echo "----------------------Set Operations-------------------------<br/>";
      	//向名为setdemo1的集合中插入数据
      	$redis->sAdd("setdemo1", "1");
      	$redis->sAdd("setdemo1", "2");
      	$redis->sAdd("setdemo1", "3");
      	$redis->sAdd("setdemo1", "4");
      	$redis->sAdd("setdemo1", "5");
      	$redis->sAdd("setdemo1", "6");
      
      	//向名为setdemo2的集合中插入数据
      	$redis->sAdd("setdemo2", "4");
      	$redis->sAdd("setdemo2", "8");
      	$redis->sAdd("setdemo2", "5");
      	$redis->sAdd("setdemo2", "7");
      	$redis->sAdd("setdemo2", "2");
      	$redis->sAdd("setdemo2", "2");//集合忽略重复的值
      	
      	//找出setdemo1相对于setdemo2的差集
      	$values = $redis->sDiff("setdemo1", "setdemo2");
      	var_dump($values);
      
      	/*不支持//集合排序*/
    }
 
?>
