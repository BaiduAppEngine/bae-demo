var BaeImage = require('../module/baev3-image');
var BaeImageTransform = BaeImage.Transform;
var BaeImageQRCode = BaeImage.QRCode;
var BaeImageAnnotate = BaeImage.Annotate;
var BaeImageConstant = BaeImage.Constant;
var BaeImageComposite = BaeImage.Composite;

var BaeImageService = BaeImage.Service;

var imageURL0 = 'http://hiphotos.baidu.com/baidu/pic/item/81b7ac86c57a211b66096e75.jpg';
var imageURL1 = 'http://hiphotos.baidu.com/baidu/pic/item/81b7ac86c57a211b66096e75.jpg';

var hostname = 'image.duapp.com'
var ak = 'yourak';
var sk = 'yoursk';

var option = {
  host: hostname,
  ak: ak,
  sk: sk
}

var imageService = new BaeImageService(ak, sk, hostname);

function testTransform(req, res) {
   var imageTrans = new BaeImageTransform();

   // 设置变换属性
   imageTrans.setZooming({zoomingType: BaeImageConstant.TRANSFORM_ZOOMING_TYPE_HEIGHT,
    size: 100});
   imageTrans.setRotation(180);
   imageTrans.setSharpness(10);
   imageTrans.setSharpness(10);
   
   // 生成图片
   imageService.applyTransformByObject(imageURL0, imageTrans, function(err, result) {
    if (err) {
      console.log(err);
      res.end('transform error');
      return;
    }

    var data = new Buffer(result['response_params'].image_data, 'base64');
    res.writeHead(200, {'Content-Type': 'image/jpg' });
    res.end(data);
  });
}

function testQRCode(req, res) {

  var imageQRCode = new BaeImageQRCode();
  
  // var text = (new Buffer('welcome to bae')).toString('base64')
  var text = 'welcome to bae';
  imageQRCode.setText(text);
  imageQRCode.setVersion(2);
  imageQRCode.setSize(50);
  imageQRCode.setLevel(3);
  imageQRCode.setMargin(3);
  imageQRCode.setForeground('000000');
  imageQRCode.setBackground('FFFFFF');

  imageService.applyQRCodeByObject(imageQRCode, function(err, result) {
     if (err) {
      console.log(err);
      res.end('transform error');
      return;
    }

    var data = new Buffer(result['response_params'].image_data, 'base64');
    res.writeHead(200, {'Content-Type': 'image/jpg' });
    res.end(data);
  });
}

function testAnnotate(req, res) {
  var imageAnnotate = new BaeImageAnnotate();

  imageAnnotate.setText('welcome to bae');
  imageAnnotate.setFont({
    name: 4,
    color: '0F0F0F',
    size: 20
  });
  imageAnnotate.setPosition({
    x_offset: 1,
    y_offset: 1
  });
  imageAnnotate.setOutputCode(0);
  imageAnnotate.setQuality(70);

  imageService.applyAnnotateByObject(imageURL0, imageAnnotate, function(err, result) {
     if (err) {
      console.log(err);
      res.end('annotate error');
      return;
    }

    var data = new Buffer(result['response_params'].image_data, 'base64');
    res.writeHead(200, {'Content-Type': 'image/jpg' });
    res.end(data);
  });
}

function testVCode(req, res) {
   var option = {
    len: 4,
    pattern: 2
  }

  imageService.generateVCode(option, function(err, result) {
    if (err) {
      console.log(err);
      res.end('generate VCode error');
      return;
    }
    res.writeHead(200, {'Content-Type': 'text/html' });
    res.end('<img src="' + result['response_params'].imgurl + '"/>'); 
  });
}

function testComposite(req, res) {
  var imageComposite0 = new BaeImageComposite();
  var imageComposite1 = new BaeImageComposite();

  imageComposite0.setImageSource(imageURL0);
  imageComposite0.setPosition({x_offset: 10,
    y_offset: 15});
  imageComposite0.setOpacity(0.5);
  imageComposite0.setAnchor(BaeImageConstant.TOP_CENTER);

  imageComposite1.setImageSource(imageURL1);
  imageComposite1.setPosition({x_offset: 10,
    y_offset: 15});
  imageComposite1.setOpacity(0.5);
  imageComposite1.setAnchor(BaeImageConstant.TOP_LEFT);

  var option = {
    imageComposites: [imageComposite0, imageComposite1],
     canvas: {
      width: 580,
      height:160
    },
    outputCode: 0,
    quality: 100,

  }

  imageService.applyCompositeByObject(option, function(err, result) {
     if (err) {
      console.log(err);
      res.end('composite error');
      return;
    }

    var data = new Buffer(result['response_params'].image_data, 'base64');
    res.writeHead(200, {'Content-Type': 'image/jpg' });
    res.end(data);
  });
}

module.exports = {
  annotate: testAnnotate,
  vcode: testVCode,
  composite: testComposite,
  qrcode: testQRCode,
  transform: testTransform
}