<?php
/**
 * BaeImageService示例，通过该示例可熟悉图片添加文字水印操作（参数数组方式）
 */
	require_once('../configure.php');
	require_once('../sdk/BaeImageService.class.php');
	/***Image服务的配置信息***/
	$user = ServiceConf::$aksk['ak'];
	$pwd = ServiceConf::$aksk['sk'];
	$host = ServiceConf::$image_cfg['host'];

	/****1. 构造水印参数数组****/
	$text =  '百度云'; //请进行UTF-8编码
	$params = array();
	$params[BaeImageConstant::ANNOTATE_OPACITY] = 0.5;
	$params[BaeImageConstant::ANNOTATE_QUALITY] = 60;
	$params[BaeImageConstant::ANNOTATE_FONT] = array(
						BaeImageConstant::KAI, 10, '00BBCC');//二维数组
	$params[BaeImageConstant::ANNOTATE_OUTPUTCODE] = BaeImageConstant::JPG;
	
	/****2. 执行添加水印操作****/
	//创建服务功能对象
	$baeImageService = new BaeImageService($user, $pwd, $host);
	$url = 'http://hiphotos.baidu.com/baidu/pic/item/81b7ac86c57a211b66096e75.jpg';
	//执行操作
	$retVal = $baeImageService->applyAnnotate($url, $text, $params);
	
	/****3. 获取返回结果****/
	if($retVal !==false && isset($retVal['response_params']) && isset($retVal['response_params']['image_data'])){
		header("Content-type:image/jpg");
		$imageSrc = base64_decode($retVal['response_params']['image_data']);
		echo $imageSrc;
	}else{
		echo 'annotate failed, error:' . $baeImageService->errmsg() . "\n";
	}
?>
