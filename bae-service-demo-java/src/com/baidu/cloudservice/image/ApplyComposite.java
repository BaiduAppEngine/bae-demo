package com.baidu.cloudservice.image;

import java.io.IOException;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import com.baidu.bae.api.factory.BaeFactory;
import com.baidu.bae.api.image.*;
import java.io.BufferedOutputStream;
import java.util.ArrayList;
import com.baidu.cloudservice.conf.Config;
public class ApplyComposite extends HttpServlet {

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
			//获取服务类对象
			String url = "http://hiphotos.baidu.com/baidu/pic/item/81b7ac86c57a211b66096e75.jpg";
			Image image1 = new Image();
			image1.setURL(url);
			Image image2 = new Image(url);
    		//创建图片合成功能类
			Composite com1 = new Composite(image1);
			Composite com2 = new Composite(image2);
			com1.setAnchor(ImageConstant.POS_TOP_LEFT);
			com1.setOpacity(0.7f);
          	com1.setPos(10,15);
			com2.setAnchor(ImageConstant.POS_TOP_CENTER);
			com2.setPos(10, 15);
			ArrayList<Composite> composites = new ArrayList<Composite>();
			composites.add(com1);
			composites.add(com2);
    		//调用图片合成服务
			byte[] bs = service.applyComposite(composites,580,160,0,80);
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
