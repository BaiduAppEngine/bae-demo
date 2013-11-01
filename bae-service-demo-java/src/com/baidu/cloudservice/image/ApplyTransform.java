package com.baidu.cloudservice.image;

import java.io.IOException;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import com.baidu.bae.api.factory.BaeFactory;
import com.baidu.bae.api.image.*;
import java.io.IOException;
import java.io.BufferedOutputStream;
import com.baidu.cloudservice.conf.Config;

public class ApplyTransform extends HttpServlet {

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
			String url = "http://hiphotos.baidu.com/baidu/pic/item/81b7ac86c57a211b66096e75.jpg";
			// 创建图片对象
			Image image = new Image();
			image.setURL(url);
			// 创建变换功能类对象
			Transform transform = new Transform();
			transform.setRotation(145);
			// 调用图片变换服务
			byte[] bs = service.applyTransform(image, transform);
			BufferedOutputStream bos = new BufferedOutputStream(
			resp.getOutputStream());
			bos.write(bs, 0, bs.length);
			if (bos != null)
				bos.close();
			bos.flush();
		} catch (Exception e) {
			e.printStackTrace(resp.getWriter());
		}
	}
}
