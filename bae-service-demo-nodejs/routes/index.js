var testCache = require('../lib/cache');
var testMongo = require('../lib/mongo');
var testRedis = require('../lib/redis');
var testSql = require('../lib/sql');
var testImage = require('../lib/image');
var util = require('util');

module.exports = function(app){
  
  // 获取环境变量
  app.get('/env', function(req, res) {
    res.end(util.inspect(process.env));
  })

 // memcache测试
  app.get('/cache', function(req, res){
    testCache(req, res);
  });

  // mongo数据库测试
  app.get('/mongo', function(req, res) {
    testMongo(req, res);
  });

  // redis数据库测试
  app.get('/redis', function(req, res) {
    testRedis(req, res);
  });

  // sql数据库测试
  app.get('/sql', function(req, res) {
    testSql(req, res);
  });
  
  // image服务：图像变换
  app.get('/image/transform', function(req, res) {
    testImage.transform(req, res);
  });
  
  // image服务：二维码生成
  app.get('/image/qrcode', function(req, res) {
    testImage.qrcode(req, res);
  });

  // image服务: 文字水印
  app.get('/image/annotate', function(req, res) {
    testImage.annotate(req, res);
  });

  // image服务: 验证码生成
  app.get('/image/vcode', function(req, res) {
    testImage.vcode(req, res);
  });

  // image服务: 图像合成
  app.get('/image/composite', function(req, res) {
    testImage.composite(req, res);
  });
}

