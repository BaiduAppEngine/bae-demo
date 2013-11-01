package com.baidu.cloudservice.image;

import java.io.*;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import com.baidu.bae.api.factory.BaeFactory;
import com.baidu.bae.api.image.*;
import java.io.IOException;
import java.io.BufferedOutputStream;
import com.baidu.inf.iis.bcs.BaiduBCS;
import com.baidu.inf.iis.bcs.BaiduBCS;
import com.baidu.inf.iis.bcs.auth.BCSCredentials;
import com.baidu.inf.iis.bcs.auth.BCSSignCondition;
import com.baidu.inf.iis.bcs.http.HttpMethodName;
import com.baidu.inf.iis.bcs.model.BucketSummary;
import com.baidu.inf.iis.bcs.model.ObjectMetadata;
import com.baidu.inf.iis.bcs.model.Resource;
import com.baidu.inf.iis.bcs.request.CreateBucketRequest;
import com.baidu.inf.iis.bcs.request.GenerateUrlRequest;
import com.baidu.inf.iis.bcs.request.GetObjectRequest;
import com.baidu.inf.iis.bcs.request.ListBucketRequest;
import com.baidu.inf.iis.bcs.request.ListObjectRequest;
import com.baidu.inf.iis.bcs.request.PutObjectRequest;
import com.baidu.inf.iis.bcs.request.PutSuperfileRequest;
import com.baidu.inf.iis.bcs.response.BaiduBCSResponse;
import com.baidu.cloudservice.conf.Config;
public class UploadToBCS extends HttpServlet {
  
  	private String bcshost = Config.BCSHOST;
	private String username = Config.USER;
	private String passwd = Config.PWD;
	private String bucket = Config.BUCKET;
  	private String object = "/" + "foo.jpg";

	@Override
	protected void doGet(HttpServletRequest req, HttpServletResponse resp)
			throws ServletException, IOException {
        try {
          	
			/**Image服务配置信息*/
			String username = Config.USER;
			String passwd = Config.PWD;
			String imagehost = Config.IMAGEHOST;
			//获取服务类对象
			BaeImageService service = BaeFactory.getBaeImageService(username, passwd, imagehost);
			String url = "http://hiphotos.baidu.com/baidu/pic/item/81b7ac86c57a211b66096e75.jpg";
			// 创建图片对象
			Image image = new Image();
			image.setURL(url);
			// 创建变换功能类对象
			Transform transform = new Transform();
			transform.setRotation(145);
			// 调用图片变换服务
			byte[] bs = service.applyTransform(image, transform);
			String fileurl = uploadToBCS(bs, resp);
          	resp.getWriter().println("url:" + fileurl);
		} catch (Exception e) {
			e.printStackTrace(resp.getWriter());
		}
	}
  	
  	private String uploadToBCS(byte[] bs, HttpServletResponse resp) throws Exception{
    	BCSCredentials credentials = new BCSCredentials(username, passwd);
		BaiduBCS baiduBCS = new BaiduBCS(credentials, bcshost);
		baiduBCS.setDefaultEncoding("UTF-8"); // Default UTF-8
		ObjectMetadata objectMetadata = new ObjectMetadata();
		objectMetadata.setContentType("image/jpg");
		objectMetadata.setContentLength(bs.length);
      	InputStream input = new ByteArrayInputStream(bs);
		PutObjectRequest request = new PutObjectRequest(this.bucket, this.object, input, objectMetadata);
		ObjectMetadata result = baiduBCS.putObject(request).getResult();
      	GenerateUrlRequest generateUrlRequest = new GenerateUrlRequest(HttpMethodName.GET, bucket, object);
		return baiduBCS.generateUrl(generateUrlRequest);
    }
}
