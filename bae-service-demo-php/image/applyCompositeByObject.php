<?php
/**
 * BaeImageService示例，通过该示例可熟悉图片合成操作（面向对象方式）
 */
	require_once('../configure.php');
	require_once('../sdk/BaeImageService.class.php');
	/***Image服务的配置信息***/
	$user = ServiceConf::$aksk['ak'];
	$pwd = ServiceConf::$aksk['sk'];
	$host = ServiceConf::$image_cfg['host'];

	/****1. 创建图片合成操作的对象BaeImageComposite，并设置图片参数****/
	$url ='http://hiphotos.baidu.com/baidu/pic/item/81b7ac86c57a211b66096e75.jpg';
	$baeImageComposite1 = new BaeImageComposite($url);
	$baeImageComposite1->setAnchor(BaeImageConstant::TOP_LEFT);
	$baeImageComposite1->setPos(10,15);
	$baeImageComposite2 = new BaeImageComposite($url);
	$baeImageComposite2->setAnchor(BaeImageConstant::TOP_CENTER);
	$baeImageComposite2->setPos(10,15);
	
	/****2. 执行图片合成操作****/
	//创建服务功能对象
	$baeImageService = new BaeImageService($user, $pwd, $host);
	$retVal = $baeImageService->applyCompositeByObject(array($baeImageComposite1,
	$baeImageComposite2),580,160,0,80);
	
	/****3. 获取返回结果****/
	if($retVal !==false && isset($retVal['response_params']) && 
	isset($retVal['response_params']['image_data'])){
		header("Content-type:image/jpg");
		$imageSrc = base64_decode($retVal['response_params']['image_data']);
		echo $imageSrc;
	}else{
		echo 'composite failed, error:' . $baeImageService->errmsg() . "\n";
	}
?>
