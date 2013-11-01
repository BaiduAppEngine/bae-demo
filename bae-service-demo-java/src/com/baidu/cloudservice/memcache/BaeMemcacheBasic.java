package com.baidu.cloudservice.memcache;

import java.io.IOException;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import com.baidu.bae.api.factory.BaeFactory;
import com.baidu.bae.api.memcache.BaeCache;
import java.io.BufferedOutputStream;
import java.util.Map;
import java.util.Collection;
import java.io.PrintWriter;
import com.baidu.cloudservice.conf.Config;
/**
 * BaeMemcache示例，通过示例可快速熟悉BaeMemcache服务的使用方法,注意使用该demo必须在管理界面启用cache服务
 */
public class BaeMemcacheBasic extends HttpServlet {
	private static final int TIMEOUT = 2000;
  	private String key = "foo1";
  	private int value = 1;
  	private boolean retVal;
  	private String output="";

	/**Cache配置信息*/
	private String cacheid = Config.CACHEID;
  	private String username= Config.USER;
  	private String passwd= Config.PWD;
	private String addr = Config.CACHEHOST + ":" + Config.CACHEPORT;
  	private BaeCache memcache = BaeFactory.getBaeCache(cacheid, addr, username, passwd);
  	private String printOpResult(int num, boolean flag){
      	String retStr = "";
    	if(flag == true){
			retStr = "Time "+ num + ":add key => value success!";
		}else{
			retStr = "Time " + num + ":add operation failed!";
		}
      	return retStr;
   	}
  	private long caculator(boolean isAdd, String key, int offset){
		if(isAdd == true){
			return memcache.incr(key, offset);
		}
		return memcache.decr(key, offset);
	}
  
	@Override
	protected void doGet(HttpServletRequest req, HttpServletResponse resp)
			throws ServletException, IOException {
        try {
			/************************Add and Delete Begin*********************/
			//清空cache中关于key的值
			memcache.delete(key);
			//第一次向cache中增加一条key:value,并且永久有效
			retVal = memcache.add(key,value);
			output = printOpResult(1, retVal);
          	resp.getWriter().println(output);
          
			//第二次向cache中增加一条key:value,操作会失败,因为key已经存在
			retVal = memcache.add(key,value);
			output = printOpResult(2, retVal);
          	resp.getWriter().println(output);
			//删除cache中的key
			memcache.delete(key);
	
			//第三次向cache中增加一条key:value，并设置失效时间为TIMEOUT
			retVal = memcache.add(key,value,TIMEOUT);
			output = printOpResult(3, retVal);
          	resp.getWriter().println(output);
	
			//do somethig else
			Thread.sleep(3000);
	
			//第四次向cache中增加一条key:value，成功
			retVal = memcache.add(key,value);
			output = printOpResult(4, retVal);
            resp.getWriter().println(output);
			/************************Add and Delete End*********************/
			/*******************Set Get and Replace Begin******************/
			//向cache中增加一条key:value,并且永久有效
			//当key不存在时,功能相当于add()
			retVal = memcache.set(key,value);
			output = printOpResult(5, retVal);
          	resp.getWriter().println(output);
			//修改已有的key所对应的值
			retVal = memcache.set(key,"abc");
			resp.getWriter().println("The new value is "+ memcache.get(key));
			//删除cache中的key
			memcache.delete(key);
	
			//使用replace,当key不存在时,则返回false
			retVal = memcache.replace(key, value);
			output = printOpResult(6, retVal);
          	resp.getWriter().println(output);
			//当key存在时,功能和set一样
			retVal = memcache.add(key,value);
			output = printOpResult(7, retVal);
          	resp.getWriter().println(output);
			retVal = memcache.replace(key, "abc");
			resp.getWriter().println("The new value is "+ memcache.get(key));
	
			//删除cache中的key
			memcache.delete(key);
			/*******************Set Get and Replace End******************/
          
          	/*****************Increment and Decrement Begin***************/

			retVal = memcache.add(key,value);
			output = printOpResult(8, retVal);
			resp.getWriter().println("Increment: " + caculator(true, key, 2));
			resp.getWriter().println("Decrement: " + caculator(false, key, 2));
			//删除cache中的key
			memcache.delete(key);
			/*****************Increment and Decrement End***************/	
		} catch (Exception e) {
			e.printStackTrace(resp.getWriter());
		}
	}

}
