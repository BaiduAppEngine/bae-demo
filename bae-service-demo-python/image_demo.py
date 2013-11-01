#-*- coding:utf-8 -*-

from bae_image.image import BaeImage

def test_img_transform():
    img = BaeImage("apikey", "secretkey", "image.duapp.com")

    ### 设置待处理图片
    img.setSource("http://www.baidu.com/img/baidu_sylogo1.gif")

    ### 设置目标图片尺寸
    img.setZooming(BaeImage.ZOOMING_TYPE_PIXELS, 100000)

    ### 设置裁剪参数
    img.setCropping(0, 0, 2000, 2000)

    ### 设置旋转角度
    img.setRotation(10)

    ### 设置灰度级别
    img.setHue(100)

    ### 设置图片格式
    img.setTranscoding('gif')

    ### 执行图片处理
    ret = img.process()

    ### 返回图片base64 encoded binary data
    body = ret['response_params']['image_data']

    import base64
    return base64.b64decode(body)

def test_img_annotate():
    img = BaeImage("apikey", "secretkey", "image.duapp.com")

    ### 设置待处理图片
    img.setSource("http://www.baidu.com/img/baidu_sylogo1.gif")

    ### 设置水印文字
    img.setAnnotateText("hello bae")

    ### 设置字体信息
    img.setAnnotateFont(3, 25, '0000aa')

    ### 执行图片处理
    ret = img.process()

    ### 返回图片base64 encoded binary data
    body = ret['response_params']['image_data']

    import base64
    return base64.b64decode(body)

def test_img_qrcode():
    img = BaeImage("apikey", "secretkey", "image.duapp.com")

    ### 设置二维码文本
    img.setQRCodeText('bae')

    ### 设置背景颜色
    img.setQRCodeBackground('ababab')

    ### 执行图片处理
    ret = img.process()

    ### 返回图片base64 encoded binary data
    body = ret['response_params']['image_data']

    import base64
    return base64.b64decode(body)


def test_img_composite():
    img = BaeImage("apikey", "secretkey", "image.duapp.com")

    ### 设置待处理图片0
    img.setSource("http://www.baidu.com/img/baidu_sylogo1.gif")

    ### 设置待处理图片1
    img.setCompositeSource("http://www.baidu.com/img/baidu_sylogo1.gif")

    ### 设置图片0的锚点
    img.setCompositeAnchor(0, 1)

    ### 设置图片1的透明度
    img.setCompositeOpacity(0.3, 1)

    ### 设置合成后画布的长宽
    img.setCompositeCanvas(50, 50)

    ### 执行图片处理
    ret = img.process()

    ### 返回图片base64 encoded binary data
    body = ret['response_params']['image_data']

    import base64
    return base64.b64decode(body)

def test_vcode():
    img = BaeImage("apikey", "secretkey", "image.duapp.com")

    ### 生成一个验证码，返回值中可获取密文vcode_str和验证码图片链接imgurl
    gret = img.generateVCode(5, 3)

    ### 验证输入是否匹配，返回值中可获取验证结果status和验证信息str_reason
    vret = img.verifyVCode("abcde", "secret")

    return str(gret) + str(vret)

def app(env, start_response):
    status = "200 OK"
    headers = [('Content-type', 'text/html')]
    start_response(status, headers)
    body = []
    try:
        body.append("image transform result: [%s]"%test_img_transform()[0:64])
        body.append("image annotate result: [%s]"%test_img_annotate()[0:64])
        body.append("image qrcode result: [%s]"%test_img_qrcode()[0:64])
        body.append("image composite result: [%s]"%test_img_composite()[0:64])
        body.append("image vcode result: [%s]"%test_vcode())
        return '<br>'.join(body)
    except:
        return 'handle exceptions'

from bae.core.wsgi import WSGIApplication
application = WSGIApplication(app)
