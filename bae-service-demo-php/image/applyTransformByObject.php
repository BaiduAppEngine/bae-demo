<?php
/**
 * BaeImageService示例，通过该示例可熟悉图片变换操作（面向对象方式）
 */

	require_once('../configure.php');
	require_once('../sdk/BaeImageService.class.php');
	/***Image服务的配置信息***/
	$user = ServiceConf::$aksk['ak'];
	$pwd = ServiceConf::$aksk['sk'];
	$host = ServiceConf::$image_cfg['host'];
	/****1. 创建变换操作的对象BaeImageTransform****/
	$baeImageTransform = new BaeImageTransform();
	
	/****2. 设置变换参数****/
	$baeImageTransform->setHue(50);
	$baeImageTransform->setQuality(80);
	
	/****3. 执行变换操作****/
	//url,暂时只支持url形式
	$url = 'http://hiphotos.baidu.com/baidu/pic/item/81b7ac86c57a211b66096e75.jpg';
	//创建服务功能对象
	$baeImageService = new BaeImageService($user, $pwd, $host);
	$retVal = $baeImageService->applyTransformByObject($url, $baeImageTransform);
	
	/****4. 获取返回结果****/
	if($retVal !==false && isset($retVal['response_params']) && isset($retVal['response_params']['image_data'])){
		header("Content-type:image/jpg");
		$imageSrc = base64_decode($retVal['response_params']['image_data']);
		echo $imageSrc;
	}else{
		echo 'transform failed, error:' . $baeImageService->errmsg() . "\n";
	}
?>
