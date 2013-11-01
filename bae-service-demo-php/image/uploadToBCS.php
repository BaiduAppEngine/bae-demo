<?php
/**
 * 使用BCS云存储，保存处理后的图片(注意：需要用户开启云存储服务)
 */
	require_once('../configure.php');
	require_once('../sdk/BaeImageService.class.php');
	require_once('../sdk/bcs/bcs.class.php');
	/***Image服务的配置信息***/
	$user = ServiceConf::$aksk['ak'];
	$pwd= ServiceConf::$aksk['sk'];
	$host = ServiceConf::$image_cfg['host'];
 
	/****1. 执行image服务****/
	$baeImageTransform = new BaeImageTransform();
	$baeImageTransform->setHue(50);
	$baeImageTransform->setQuality(80);
	
	$url = 'http://hiphotos.baidu.com/baidu/pic/item/81b7ac86c57a211b66096e75.jpg';
	//创建服务功能对象
	$baeImageService = new BaeImageService($user, $pwd, $host);
	$retVal = $baeImageService->applyTransformByObject($url, $baeImageTransform);
	
	/****2. 将结果保存到云存储****/
	if($retVal !==false && isset($retVal['response_params']) && isset($retVal['response_params']['image_data'])){
		$inputStream = base64_decode($retVal['response_params']['image_data']);
		upload($inputStream);
	}else{
		die('transform failed, error:' . $baeImageService->errmsg() . "\n");
	}

	function upload($inputStream){
		global $user, $pwd;
		$host = ServiceConf::$bcs_cfg['host'];
		$bucket = ServiceConf::$bcs_cfg['bucket'];
		$baiduBCS = new BaiduBCS($user, $pwd, $host);
	
		//object name
		$filename = 'foo.jpg';//填入您要保存的名称
		$object = '/' . $filename; //object必须以‘/’开头
	
		//将图片存入云存储
		$response = $baiduBCS->create_object_by_content($bucket, $object, $inputStream);
		if(!$response->isOK()){
			die('Create object failed.');
		}
	
		//得到已存入云存储图片的url
		$url = $baiduBCS->generate_get_object_url($bucket,$object);
		if($url === false){
			die('Generate GET object url failed.');	
		}
	  	echo $url;
	}

?> 
