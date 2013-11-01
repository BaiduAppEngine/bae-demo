
package com.baidu.cloudservice.image;

import java.io.IOException;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import com.baidu.bae.api.factory.BaeFactory;
import com.baidu.bae.api.image.*;
import java.io.BufferedOutputStream;
import com.baidu.cloudservice.conf.Config;
public class ApplyAnnotate extends HttpServlet {

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
			//创建图片对象
			Image image = new Image(url);
			//创建文字水印功能对象
          	String text = "#Hello World!#";
			Annotate annotate = new Annotate(text);
			annotate.setFont(0,20,"000000");
          	//annotate.setPos(450,480);
			annotate.setOpacity(0.3f);
			annotate.setQuality(80);
    		//调用文字水印服务
			byte[] bs = service.applyAnnotate(image, annotate);
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
