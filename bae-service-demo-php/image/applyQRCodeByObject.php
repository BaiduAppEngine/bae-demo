<?php
/**
 * BaeImageService示例，通过该示例可熟悉生成二维码操作（面向对象方式）
 */

	require_once('../configure.php');
	require_once('../sdk/BaeImageService.class.php');
	/***Image服务的配置信息***/
	$user = ServiceConf::$aksk['ak'];
	$pwd = ServiceConf::$aksk['sk'];
	$host = ServiceConf::$image_cfg['host'];
	/****1. 创二维码操作的对象BaeImageQRCode****/
	$baeImageQRCode = new BaeImageQRCode();
	
	/****2. 设置二维码参数****/
	$baeImageQRCode->setText('Hello World');
	$baeImageQRCode->setLevel(3);
	$baeImageQRCode->setVersion(10);
	
	/****3. 执行二维码操作****/
	//创建服务功能对象
	$baeImageService = new BaeImageService($user, $pwd, $host);
	$retVal = $baeImageService->applyQRCodeByObject($baeImageQRCode);
	
	/****4. 获取返回结果****/
	if($retVal !==false && isset($retVal['response_params']) && isset($retVal['response_params']['image_data'])){
		header("Content-type:image/jpg");
		$imageSrc = base64_decode($retVal['response_params']['image_data']);
		echo $imageSrc;
	}else{
		echo 'qr encoding failed, error:' . $baeImageService->errmsg() . "\n";
	}
?>
