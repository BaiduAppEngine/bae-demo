package com.baidu.cloudservice.image;

import java.io.IOException;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import com.baidu.bae.api.factory.BaeFactory;
import com.baidu.bae.api.memcache.BaeCache;
import com.baidu.bae.api.image.*;
import java.io.IOException;
import java.io.BufferedOutputStream;
import java.util.Map;
import java.util.Collection;
import java.io.PrintWriter;
import com.baidu.cloudservice.conf.Config;
public class GenerateVCode extends HttpServlet {

	@Override
	protected void doGet(HttpServletRequest req, HttpServletResponse resp)
			throws ServletException, IOException {
        try {
			/**Image服务配置信息*/
			String username = Config.USER;
			String passwd = Config.PWD;
			String host = Config.IMAGEHOST;
			//获取服务类对象
			BaeImageService service = BaeFactory.getBaeImageService(username, passwd, host);
			VCode vc = new VCode();
			vc.setLen(5);
			Map<String,String> data = service.generateVCode(vc);

			/**Cache配置信息*/
			String cacheid = Config.CACHEID;
			String addr = Config.CACHEHOST + ":" + Config.CACHEPORT;
			BaeCache memcache = BaeFactory.getBaeCache(cacheid, addr, username, passwd);
          	memcache.delete("imgurl");
          	memcache.delete("secret");
          	memcache.add("imgurl", data.get("imgurl"));
          	memcache.add("secret", data.get("secret"));
			resp.getWriter().println("imgurl:" + data.get("imgurl"));
			resp.getWriter().println("secret:" + data.get("secret"));
          	resp.sendRedirect("/static/verify.jsp");
		} catch (Exception e) {
			e.printStackTrace(resp.getWriter());
		}
	}
}

