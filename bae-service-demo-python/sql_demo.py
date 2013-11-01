#-*- coding:utf-8 -*-

def test_sql():
   import MySQLdb
   dbname = "cBAwGjJPshWEVBKCkfLG"

   ### 连接MySql服务
   mydb = MySQLdb.connect(
      host   = "sqld.duapp.com",
      port   = 4050,
      user   = "apikey",
      passwd = "secretkey",
      db = dbname)

   ### 执行sql命令，创建table test
   cursor = mydb.cursor()

   cmd = '''CREATE TABLE IF NOT EXISTS test (
         id int(4) auto_increment,
         name char(20) not null,
         age int(2),
         sex char(8) default 'man',
         primary key (id))'''

   cursor.execute(cmd)

   mydb.close()
   return 'create table test success!'

def app(environ, start_response):
    status = '200 OK'
    headers = [('Content-type', 'text/html')]
    start_response(status, headers)
    try:
        return test_sql()
    except:
        return 'handle exceptions'

from bae.core.wsgi import WSGIApplication
application = WSGIApplication(app)