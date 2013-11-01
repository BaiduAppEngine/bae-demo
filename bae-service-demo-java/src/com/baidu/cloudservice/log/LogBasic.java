package com.baidu.cloudservice.log;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.apache.log4j.Logger;

import java.io.IOException;

/**
 * Log示例，通过该示例可熟悉BAE平台Log的使用
 */
public class LogBasic extends HttpServlet { 
 
	@Override
	protected void doGet(HttpServletRequest req, HttpServletResponse resp)
			throws ServletException, IOException {
        try {
			Logger logger = Logger.getLogger("java");
			//打印一条Trace语句
			logger.trace("This is a TRACE log");
			//打印一条Notice语句
			logger.info("This is a NOTICE log");
			//打印一条Debug语句
			logger.debug("This is a DEBUG log");
			//打印一条Warning语句
			logger.warn("This is a WARNING log");
			//打印一条Trace语句
			logger.fatal("This is a FATAL log");
			resp.getWriter().println("Log OK, Please Check Log Service For Detail Infomation");
        }catch(Exception e){
        	e.printStackTrace(resp.getWriter());
        }
	}

}
