<?php
/**
 * BaeImageService示例，通过该示例可熟悉图片变换操作（参数数组方式）
 */

	require_once('../configure.php');
	require_once('../sdk/BaeImageService.class.php');
	/***Image服务的配置信息***/
	$user = ServiceConf::$aksk['ak'];
	$pwd = ServiceConf::$aksk['sk'];
	$host = ServiceConf::$image_cfg['host'];
	/****1. 构造参数数组****/
	$params = array();
	$params[BaeImageConstant::TRANSFORM_HUE] = 50;
	$params[BaeImageConstant::TRANSFORM_ZOOMING] = array(
						BaeImageConstant::TRANSFORM_ZOOMING_TYPE_WIDTH, 100);//二维数组
	$params[BaeImageConstant::TRANSFORM_VERTICALFLIP] = 1;
	/****2. 执行图片变换操作****/
	$baeImageService = new BaeImageService($user, $pwd, $host);
	$url = 'http://hiphotos.baidu.com/baidu/pic/item/81b7ac86c57a211b66096e75.jpg';
	$retVal = $baeImageService->applyTransform($url, $params);
	
	/****3. 获取返回结果****/
	if($retVal !==false && isset($retVal['response_params']) && isset($retVal['response_params']['image_data'])){
		header("Content-type:image/jpg");
		$imageSrc = base64_decode($retVal['response_params']['image_data']);
		echo $imageSrc;
	}else{
		echo 'transform failed, error:' . $baeImageService->errmsg() . "\n";
	}
?>
