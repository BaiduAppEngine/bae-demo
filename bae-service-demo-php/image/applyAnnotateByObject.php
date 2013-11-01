<?php
/**
 * BaeImageService示例，通过该示例可熟悉图片添加文字水印操作（面向对象方式）
 */
	require_once('../configure.php');
	require_once('../sdk/BaeImageService.class.php');
	/***Image服务的配置***/
	$user = ServiceConf::$aksk['ak'];
	$pwd = ServiceConf::$aksk['sk'];
	$host = ServiceConf::$image_cfg['host'];
	
	/****1. 创建水印操作的对象BaeImageAnnotate****/
	$text = '百度云';//请进行UTF-8编码
	$baeImageAnnotate = new BaeImageAnnotate($text);
	
	/****2. 设置水印操作参数****/
	$baeImageAnnotate->setFont(BaeImageConstant::MICROHEI,25, '00BBCC');
	$baeImageAnnotate->setQuality(80);
	
	/****3. 执行添加水印操作****/
	//url,暂时只支持url形式
	$url = 'http://hiphotos.baidu.com/baidu/pic/item/81b7ac86c57a211b66096e75.jpg';
	//创建服务功能对象
	$baeImageService = new BaeImageService($user, $pwd, $host);
	$retVal = $baeImageService->applyAnnotateByObject($url, $baeImageAnnotate);
	
	/****4. 获取返回结果****/
	if($retVal !==false && isset($retVal['response_params']) && isset($retVal['response_params']['image_data'])){
		header("Content-type:image/jpg");
		$imageSrc = base64_decode($retVal['response_params']['image_data']);
		echo $imageSrc;
	}else{
		echo 'annotate failed, error:' . $baeImageService->errmsg() . "\n";
	}
?>
