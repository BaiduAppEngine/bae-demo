var BaeMemcache = require('../module/baev3-cache');
var cacheId = 'yourcacheId';
var host = 'cache.duapp.com:20243';
var ak = 'yourak';
var sk = 'yoursk';

function testCache(req, res) {
  var client = new BaeMemcache(cacheId, host, ak, sk);

  client.set('baidu', 'welcome to bae', function(err){
    if (err) {
      console.log(err);
      res.end('set error');
      return;
    }
    client.get('baidu', function(err, result) {
      if (err) {
        console.log(err);
        res.end('get error');
        return;
      }
      res.end('get value: ' + result);
    });
  });
}

module.exports = testCache;