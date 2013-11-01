
var routes = require('./routes')
  , http = require('http')
  , path = require('path')
  , express = require('express')

var app = express();

app.configure(function(){
  app.set('port', 18080);
  app.set('views', __dirname + '/views');
  app.set('view engine', 'jade');
  app.use(express.favicon());
  app.use(express.bodyParser());
  app.use(express.methodOverride());
  app.use(express.cookieParser());
  app.use(express.static(path.join(__dirname, 'public')));
});

app.configure('development', function(){
  app.use(express.errorHandler());
});

routes(app);

var server = http.createServer(app);
server.listen(app.get('port'));