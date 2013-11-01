<?php
/**
 * BaeImageService示例，通过该示例可熟悉生成验证码操作
 */
	require_once('../configure.php');
	require_once('../sdk/BaeImageService.class.php');
	require_once '../sdk/BaeMemcache.class.php';
	/***Image服务的配置信息***/
	$iuser = ServiceConf::$aksk['ak'];
	$ipwd = ServiceConf::$aksk['sk'];
	$ihost = ServiceConf::$image_cfg['host'];
	/***Cache配置信息***/
	$cacheid = ServiceConf::$cache_cfg['cacheid'];
	$chost = ServiceConf::$cache_cfg['host'];
	$cport = ServiceConf::$cache_cfg['port'];
	$cuser = ServiceConf::$aksk['ak'];
	$cpwd = ServiceConf::$aksk['sk'];

	/****1. 创建验证码参数数组****/
	$params = array();
	$params[BaeImageConstant::VCODE_LEN] = 5;
	$params[BaeImageConstant::VCODE_PATTERN] = 2;
	
	/****2. 执行生成验证码操作****/
	$baeImageService = new BaeImageService($iuser, $ipwd, $ihost);
	$retVal = $baeImageService->generateVCode($params);
	
	/****3. 获取返回结果****/
	if($retVal !==false && isset($retVal['response_params']) && isset($retVal['response_params']['imgurl'])){
		$imgurl = $retVal['response_params']['imgurl'];
      	$secret = trim($retVal['response_params']['vcode_str'],"/");
      	$memcache = new BaeMemcache($cacheid, $chost.':'.$cport, $cuser, $cpwd);
      	$memcache->delete("imgurl");
      	$memcache->delete("secret");
      	$memcache->add("imgurl", $imgurl);
      	$memcache->add("secret", $secret);
      //echo "imgurl:" . $imgurl . "\n";
      //echo "secret:" . $secret . "\n";
	}else{
		echo 'generate vcode failed, error:' . $baeImageService->errmsg() . "\n";
	}
?>
