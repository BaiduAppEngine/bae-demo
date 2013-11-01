<%@ page language="java" import="java.util.*,java.net.URL" pageEncoding="UTF-8"%>
<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <base href="<%=basePath%>">
    
    <title>Hello World</title>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
    <META http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<!--
	<link rel="stylesheet" type="text/css" href="styles.css">
	-->
  </head>
  
  <body>
    <h1 align="center">BAE云服务SDK示例</h1>
    <table border="1" align="center" width="800" height="50" cellpadding="10">
    <tr>
    	<td >
    		<a href="static/cache.html"><h2>cache</h2></a><br>
    	</td>
      	<td>
    		<a href="static/log.html"><h2>log</h2></a><br>
    	</td>
    </tr>
      
    <tr>
    	<td>
    		<a href="static/mysql.html"><h2>mysql</h2></a><br>
    	</td>
      	<td>
    		<a href="static/mongodb.html"><h2>mongodb</h2></a><br>
    	</td>
    </tr>
      
    <tr>
    	<td>
    		<a href="static/redis.html"><h2>redis</h2></a><br>
    	</td>
      	<td>
    		<a href="static/image.html"><h2>image</h2></a><br>
    	</td>
    </tr>
    </table>
    
    
    
    
    
    
	
    
  </body>
</html>
