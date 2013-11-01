<?php
/**
 * BaeImageService示例，通过该示例可熟悉生成二维码操作（参数数组方式）
 */
	require_once('../configure.php');
	require_once('../sdk/BaeImageService.class.php');
	/***Image服务的配置信息***/
	$user = ServiceConf::$aksk['ak'];
	$pwd = ServiceConf::$aksk['sk'];
	$host = ServiceConf::$image_cfg['host'];

	/****1. 构造二维码参数数组****/
	$params = array();
	$params[BaeImageConstant::QRCODE_SIZE] = 90;
	$params[BaeImageConstant::QRCODE_LEVEL] = 3;
	$params[BaeImageConstant::QRCODE_FOREGROUND] = '000000';
	
	/****2. 执行操作****/
	$baeImageService = new BaeImageService($user, $pwd, $host);
	$text = '百度云'; //请进行UTF-8编码
	$retVal = $baeImageService->applyQRCode($text, $params);
	
	/****3. 获取返回结果****/
	if($retVal !==false && isset($retVal['response_params']) && isset($retVal['response_params']['image_data'])){
		header("Content-type:image/jpg");
		$imageSrc = base64_decode($retVal['response_params']['image_data']);
		echo $imageSrc;
	}else{
		echo 'qr encoding failed, error:' . $baeImageService->errmsg() . "\n";
	}
?>
