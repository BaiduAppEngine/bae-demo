var mysql = require('mysql');

var username = 'yourak';
var password = 'yoursk';
var db_host = 'sqld.duapp.com';
var db_port = 4050;
var db_name = 'yourdbname';
var option = {
  host: db_host,
  port: db_port,
  user: username,
  password: password,
  database: db_name
}

function testSql(req, res) {
  var TEST_TABLE = 'baeSql';
  var client = mysql.createConnection(option);

  client.connect(function(err){
      if (err) {
        res.end('connect error');
        console.log(err);
        return;
      }
      res.write('connected success\n');
      createTalble(client);

  });
  client.on('error',function(err) {
      if (err.errno != 'ECONNRESET') {
        throw err;
      } else {
        //do nothing
      }
  });

  function createTalble(client) {
    client.query(
      'CREATE TABLE '+ TEST_TABLE +
      '(id INT(11) AUTO_INCREMENT, '+
      'title VARCHAR(255), '+
      'text TEXT, '+
      'PRIMARY KEY (id));', function(err, results) {
        if (err && err.number != client.ERROR_TABLE_EXISTS_ERROR) {
          console.log(err);

          return;
        }
        res.write("create table success \n");
        insertData(client);
      }
    );
  } 

  function insertData(client) {
    client.query(
      'INSERT INTO '+ TEST_TABLE +
      ' SET title = ?, text = ?',
      ['baidu', 'welcome to BAE'],
      function(err, results) {
        if (err) {
          res.end('insertData error');
          console.log(err);
          return;
        }
        res.write('insert success \n');
        queryData(client);
      }
    );
  }

  function queryData (clinet) {
    client.query(
      'SELECT * FROM '+TEST_TABLE,
      function (err, results, fields) {
        if (err) {
          res.end('query error');
          console.log(err);
          return;
        }
        // res.end('results: ' + JSON.stringify(results) + '\n');
        res.write('query success \n');
        res.end('results length: ' + results.length);
        client.end();
      }
    );
  } 
}

module.exports = testSql