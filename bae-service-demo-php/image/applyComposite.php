<?php
/**
 * BaeImageService示例，通过该示例可熟悉图片合成操作（参数数组方式）
 */
	require_once('../configure.php');
	require_once('../sdk/BaeImageService.class.php');
	/***Image服务的配置***/
	$user = ServiceConf::$aksk['ak'];
	$pwd = ServiceConf::$aksk['sk'];
	$host = ServiceConf::$image_cfg['host'];
	
	/****1. 构造图片合成参数数组****/
	//构造参数数组
	$params = array();
	$url = 'http://hiphotos.baidu.com/baidu/pic/item/81b7ac86c57a211b66096e75.jpg';        
	$params[0] = array(BaeImageConstant::COMPOSITE_BAEIMAGESOURCE => array($url),
						BaeImageConstant::COMPOSITE_ANCHOR => BaeImageConstant::TOP_LEFT,
	                    BaeImageConstant::COMPOSITE_POS => array(10,15));
	$params[1] = array(BaeImageConstant::COMPOSITE_BAEIMAGESOURCE => array($url),
						BaeImageConstant::COMPOSITE_ANCHOR => BaeImageConstant::TOP_CENTER,
	                    BaeImageConstant::COMPOSITE_POS => array(10,15));
		
	/****2. 执行图片合成操作****/
	//创建服务功能对象
	$baeImageService = new BaeImageService($user, $pwd, $host);
	$retVal = $baeImageService->applyComposite($params, 580,160);
	
	/****3. 获取返回结果****/
	if($retVal !==false && isset($retVal['response_params']) && isset($retVal['response_params']['image_data'])){
		header("Content-type:image/jpg");
		$imageSrc = base64_decode($retVal['response_params']['image_data']);
		echo $imageSrc;
	}else{
		echo 'composite failed, error:' . $baeImageService->errmsg() . "\n";
	}
?>
