package com.baidu.cloudservice.memcache;

import java.io.IOException;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import com.baidu.bae.api.factory.BaeFactory;
import com.baidu.bae.api.memcache.BaeCache;
import java.io.IOException;
import java.io.BufferedOutputStream;
import java.util.Map;
import java.util.Collection;
import java.io.PrintWriter;
import com.baidu.cloudservice.conf.Config;
/**
 * BaeMemcache示例，使用Memcache服务实现了加锁功能,注意使用该demo必须在管理界面启用cache服务
 */
public class BaeMemcacheLocker extends HttpServlet {
	private static final int LOCK_TIMEOUT = 2000;
  	private String key = "foo";
  	private int value = 1;
	/**Cache配置信息*/
	private String cacheid = Config.CACHEID;
  	private String username= Config.USER;
  	private String passwd= Config.PWD;
	private String addr = Config.CACHEHOST + ":" + Config.CACHEPORT;
  	private BaeCache memcache = BaeFactory.getBaeCache(cacheid, addr, username, passwd);
 
	@Override
	protected void doGet(HttpServletRequest req, HttpServletResponse resp)
			throws ServletException, IOException {
        try {
				memcache.delete(key);
				boolean lock = memcache.add(key, value,LOCK_TIMEOUT);
				if(lock == true){
					resp.getWriter().println("There is no lock on this key:" + key + ", do whatever you want");
					resp.getWriter().println(memcache.incr(key));
					resp.getWriter().println("...increment operation finished! Release lock on the key:" + key);
					memcache.delete(key);
				}
				else{
					resp.getWriter().println("The key:" + key + " is currently locked, please wait or do something else");
				}	
		} catch (Exception e) {
			e.printStackTrace(resp.getWriter());
		}
	}

}
