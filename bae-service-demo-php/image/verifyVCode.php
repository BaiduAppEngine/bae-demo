<?php
/**
 * BaeImageService示例，通过该示例可熟悉校验验证码操作
 */
	require_once('../configure.php');
	require_once('../sdk/BaeImageService.class.php');
	/***Image服务的配置信息***/
	$user = ServiceConf::$aksk['ak'];
	$pwd = ServiceConf::$aksk['sk'];
	$host = ServiceConf::$image_cfg['host'];

	/****1. 从URL中获取input和secret字段****/
	$input = $_REQUEST['input'];
	$secret = $_REQUEST['secret'];
	
	/****2. 创建验证码参数数组****/
	$params = array();
	//输入验证码文本信息
	$params[BaeImageConstant::VCODE_INPUT] = $input;
	//输入验证码密文
	$params[BaeImageConstant::VCODE_SECRET] = $secret;
	
	/****3. 执行校验****/
	$baeImageService = new BaeImageService($user, $pwd, $host);
	$retVal = $baeImageService->verifyVCode($params);
	
	/****4. 返回校验结果****/
	if($retVal !==false && isset($retVal['response_params']) && isset($retVal['response_params']['status'])){
		$status = $retVal['response_params']['status'];
		$reason = $retVal['response_params']['str_reason'];
		echo "status:" . $status . "\n";
		echo "reason:" . $reason . "\n";
	}else{
		echo 'verify vcode failed, error:' . $baeImageService->errmsg() . "\n";
	}
?>
