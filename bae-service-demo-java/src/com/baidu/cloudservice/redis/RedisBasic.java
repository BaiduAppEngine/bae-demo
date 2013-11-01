package com.baidu.cloudservice.redis;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.util.*;
import java.net.*;
import redis.clients.jedis.*;
import java.io.PrintWriter;
import com.baidu.cloudservice.conf.Config;
/**
 * Redis示例，通过该示例可熟悉BAE平台Redis的使用
 */
public class RedisBasic extends HttpServlet { 
 
	@Override
	protected void doGet(HttpServletRequest req, HttpServletResponse resp)
			throws ServletException, IOException {
        try {
          	/*****连接数据库所需要的五要素（可从数据库详情页中查到相应信息）*****/
			String databaseName = Config.REDISNAME;
			String host =  Config.REDISHOST;
			String portStr =  Config.REDISPORT;
          	int port = Integer.parseInt(portStr);
			String username =  Config.USER;
			String password =  Config.PWD;
          
			/******接着连接并选择数据库名为databaseName的服务器******/
          	Jedis jedis = new Jedis(host,port);
			jedis.connect();
			jedis.auth(username + "-" + password + "-" + databaseName);
          	/*至此连接已完全建立，就可对当前数据库进行相应的操作了*/
			/**
			 * 接下来就可以使用redis数据库语句进行数据库操作,详细操作方法请参考java-redis官方文档
			 */
			PrintWriter out = resp.getWriter();
          	//删除所有redis数据库中的key-value
          	jedis.flushDB();
			stringOperations(jedis, out);
          	listOperations(jedis, out);
          	setOperations(jedis, out);
			
		} catch (Exception e) {
			e.printStackTrace(resp.getWriter());
		}
	}
  	private void stringOperations(Jedis jedis, PrintWriter out){
  		out.println("----------------------String Operations-------------------------");
      	//简单的key-value设置
      	jedis.set("name", "bae");
      	out.println("name | " + jedis.get("name"));
      
      	//不支持append操作
      
      	//multiple set/get，一次设置/获取多组key-value
      	jedis.mset("logintimes", "100","logouttimes", "80");
      	List<String> values = jedis.mget("name","logintimes","logouttimes");
      	Iterator<String> it = values.iterator();
      	out.println("mset/mget operation:");
      	out.println("name \t logintimes \t logouttimes ");
      	while(it.hasNext()){
			String item = it.next();
          	out.print(item + "\t\t");
      	}
      	out.println("");
      
      	//increment/decrement操作
      	out.println("increment/decrement operation:");
      	jedis.incrBy("logouttimes", 10);
      	jedis.decrBy("logintimes", 10);
      	out.println("logintimes \t logouttimes ");
      	out.println(jedis.get("logintimes") + "\t\t" + jedis.get("logouttimes"));
      
      	//delete operations
      	out.println("delete name:baeredis");
      	jedis.del("name");
       
  	}
  	
  	private void listOperations(Jedis jedis, PrintWriter out){
  		out.println("----------------------List Operations-------------------------");
      	//向名为listdemo的链表中插入数据
      	jedis.lpush("listdemo", "Engine");
      	jedis.lpush("listdemo", "Application");
      	jedis.lpush("listdemo", "Baidu");
      
      	//获取链表中的指定部分
      	List<String> values = jedis.lrange("listdemo",0, 2);
      	Iterator<String> it = values.iterator();
      	String item;
      	while(it.hasNext()){
			item = it.next();
          	out.print(item + "\t\t");
      	}
      	out.println("");
      	
      	//去除链表尾部的值
      	jedis.rpop("listdemo");
      	
      	//修改链表中的值
      	jedis.lset("listdemo", 1, "Redis");
      	values = jedis.lrange("listdemo",0,1);
      	it = values.iterator();
      	while(it.hasNext()){
			item = it.next();
          	out.print(item + "\t\t");
      	}
      	out.println("");
      
      	//删除链表
      	jedis.del("listdemo");
    
  	}
  	
  	private void setOperations(Jedis jedis, PrintWriter out){
  		out.println("----------------------Set Operations-------------------------");
      	//向名为setdemo1的集合中插入数据
      	jedis.sadd("setdemo1", "1");
      	jedis.sadd("setdemo1", "2");
      	jedis.sadd("setdemo1", "3");
      	jedis.sadd("setdemo1", "4");
      	jedis.sadd("setdemo1", "5");
      	jedis.sadd("setdemo1", "6");
      
      	//向名为setdemo2的集合中插入数据
      	jedis.sadd("setdemo2", "4");
      	jedis.sadd("setdemo2", "8");
      	jedis.sadd("setdemo2", "5");
      	jedis.sadd("setdemo2", "7");
      	jedis.sadd("setdemo2", "2");
      	jedis.sadd("setdemo2", "2");//集合忽略重复的值
      	
      	//找出setdemo1相对于setdemo2的差集
      	Set<String> values = jedis.sdiff("setdemo1", "setdemo2");
      	Iterator<String> it = values.iterator();
      	String item;
      	while(it.hasNext()){
			item = it.next();
          	out.print(item + "\t\t");
      	}
      	out.println("");
      
      	//不支持sort操作
  	}

}
