package com.baidu.cloudservice.mongodb;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.util.*;
import java.net.*;
import com.mongodb.*;
import java.io.PrintWriter;
import com.baidu.cloudservice.conf.Config;
/**
 * MongoDB示例，通过该示例可熟悉BAE平台MongoDB的使用（CRUD）
 */
public class MongoDBBasic extends HttpServlet { 
 
	@Override
	protected void doGet(HttpServletRequest req, HttpServletResponse resp)
			throws ServletException, IOException {
        try {
          	/*****连接数据库所需要的五要素（可从数据库详情页中查到相应信息）*****/
			String databaseName = Config.MONGONAME;
			String host = Config.MONGOHOST;
			String port = Config.MONGOPORT;
			String username = Config.USER;
			String password = Config.PWD;
			String serverName = host + ":" + port;
          
			/****接着连接并选择数据库名为databaseName的服务器******/
          	MongoClient mongoClient = new MongoClient(new ServerAddress(serverName),
						Arrays.asList(MongoCredential.createMongoCRCredential(username, databaseName, password.toCharArray())),
						new MongoClientOptions.Builder().cursorFinalizerEnabled(false).build());
			DB mongoDB = mongoClient.getDB(databaseName);
			mongoDB.authenticate(username, password.toCharArray());
          	/*至此连接已完全建立，就可对当前数据库进行相应的操作了*/
			/**
			 * 接下来就可以使用mongo数据库语句进行数据库操作,详细操作方法请参考java-mongodb官方文档
			 */
			//集合并不需要预先创建
			DBCollection mongoCollection = mongoDB.getCollection("test_mongo");
          
          	//插入数据
          	DBObject data1 = new BasicDBObject();
          	data1.put("no", 2007);
          	data1.put("name", "this is a test message");
			mongoCollection.insert(data1);
          
          	DBObject data2 = new BasicDBObject();
          	data2.put("no", 2008);
          	data2.put("name", "this is another test message");
			mongoCollection.insert(data2);
          
          	DBObject data3 = new BasicDBObject();
          	data3.put("no", 2009);
          	data3.put("name", "xxxxxxxxxxxxxxxxxxxxxx");
			mongoCollection.insert(data3);
          
          	//删除数据
			mongoCollection.remove(data2);

			//更新数据
          	DBObject data4 = new BasicDBObject(); 
          	data4.put("name", "yyyyyyyyyyyyyyyyyyyyyyy");
          	data4.put("no", 2009);
			mongoCollection.update(data3, data4);

			//检索数据
			DBCursor mongoCursor = mongoCollection.find();
          	Iterator<DBObject> it = mongoCursor.iterator();
          	DBObject item;
          	resp.getWriter().print("no\t name");
          	resp.getWriter().print("\n------------------------------\n");
			while(it.hasNext()) {
				item = it.next();
              	resp.getWriter().println(item.get("no") + "\t" + item.get("name"));
            }

          	//删除集合
			mongoCollection.drop();
			
		} catch (Exception e) {
			e.printStackTrace(resp.getWriter());
		}
	}

}
