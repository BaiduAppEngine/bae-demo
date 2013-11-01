<?php
/**
 * BCS API sample
 */
require_once '../bcs.class.php';
$host = 'bcs.duapp.com'; //online
$ak = '';
$sk = '';
$bucket = '';
$upload_dir = "../";
$object = '/a.txt';
$fileUpload = './a.txt';
$fileWriteTo = './a.' . time () . '.txt';
$baidu_bcs = new BaiduBCS ( $ak, $sk, $host );
/**
 * ************************single test******************************************* *
 * */
try {
	create_bucket ( $baidu_bcs );
	//list_bucket ( $baidu_bcs );
	//list_object ( $baidu_bcs );
	//set_bucket_acl_by_acl_type ( $baidu_bcs );
	//set_bucket_acl_by_json_array ( $baidu_bcs );
	//set_bucket_acl_by_json_string ( $baidu_bcs );
	//get_bucket_acl ( $baidu_bcs );
	//delete_bucket ( $baidu_bcs );
	create_object ( $baidu_bcs );

	//create_object_superfile ( $baidu_bcs );
	//upload_directory ( $baidu_bcs );
	//copy_object ( $baidu_bcs );
	//set_object_meta ( $baidu_bcs );
	//get_object ( $baidu_bcs );
	//is_object_exist ( $baidu_bcs );
	//get_object_info ( $baidu_bcs );
	//get_object_acl ( $baidu_bcs );
	//set_object_acl_by_acl_type ( $baidu_bcs );
	//set_object_acl_by_json_array ( $baidu_bcs );
	//set_object_acl_by_json_string($baidu_bcs);
	//delete_object ( $baidu_bcs );
	//generate_get_object_url ( $baidu_bcs );
	//generate_put_object_url ( $baidu_bcs );
	//generate_post_object_url ( $baidu_bcs );
} catch ( Exception $e ) {
	trigger_error ( $e->getMessage () );
}

/**
 * ************************bucket********************************** *
 * */
function create_bucket($baidu_bcs) {
	global $bucket;
	//	$acl = BaiduBCS::BCS_SDK_ACL_TYPE_PUBLIC_CONTROL;
	//	$acl = BaiduBCS::BCS_SDK_ACL_TYPE_PUBLIC_READ;
	//	$acl = BaiduBCS::BCS_SDK_ACL_TYPE_PUBLIC_READ_WRITE;
	//	$acl = BaiduBCS::BCS_SDK_ACL_TYPE_PUBLIC_WRITE;
	$acl = BaiduBCS::BCS_SDK_ACL_TYPE_PRIVATE;
	$response = $baidu_bcs->create_bucket ( $bucket, $acl );
	printResponse ( $response );
}

function delete_bucket($baidu_bcs) {
	global $bucket;
	$response = $baidu_bcs->delete_bucket ( $bucket );
	printResponse ( $response );
}

function list_object($baidu_bcs) {
	global $bucket, $fileWriteTo;
	$opt = array (
			'start' => 0, 
			'limit' => 5, 
			'prefix' => '/a' );
	$response = $baidu_bcs->list_object ( $bucket, $opt );
	printResponse ( $response );
}

function list_bucket($baidu_bcs) {
	$response = $baidu_bcs->list_bucket ();
	printResponse ( $response );
}

function get_bucket_acl($baidu_bcs) {
	global $bucket;
	$response = $baidu_bcs->get_bucket_acl ( $bucket );
	printResponse ( $response );
}

function set_bucket_acl_by_acl_type($baidu_bcs) {
	global $bucket;
	//	$acl = BaiduBCS::BCS_SDK_ACL_TYPE_PUBLIC_CONTROL;
	$acl = BaiduBCS::BCS_SDK_ACL_TYPE_PUBLIC_READ;
	//	$acl = BaiduBCS::BCS_SDK_ACL_TYPE_PUBLIC_READ_WRITE;
	//	$acl = BaiduBCS::BCS_SDK_ACL_TYPE_PUBLIC_WRITE;
	//	$acl = BaiduBCS::BCS_SDK_ACL_TYPE_PRIVATE;
	$response = $baidu_bcs->set_bucket_acl ( $bucket, $acl );
	printResponse ( $response );
}

function set_bucket_acl_by_json_array($baidu_bcs) {
	global $bucket;
	$acl = array (
			'statements' => array (
					'0' => array (
							'user' => array (
									"*" ), 
							'resource' => array (
									$bucket . '/' ), 
							'action' => array (
									BaiduBCS::BCS_SDK_ACL_ACTION_ALL ), 
							'effect' => BaiduBCS::BCS_SDK_ACL_EFFECT_ALLOW ) ) );
	
	$response = $baidu_bcs->set_bucket_acl ( $bucket, $acl );
	printResponse ( $response );
}

function set_bucket_acl_by_json_string($baidu_bcs) {
	global $bucket;
	$acl = array (
			'statements' => array (
					'0' => array (
							'user' => array (
									"psp:jason_zhengkan" ), 
							'resource' => array (
									$bucket . '/' ), 
							'action' => array (
									BaiduBCS::BCS_SDK_ACL_ACTION_GET_OBJECT, 
									BaiduBCS::BCS_SDK_ACL_ACTION_PUT_OBJECT ), 
							'effect' => BaiduBCS::BCS_SDK_ACL_EFFECT_ALLOW ) ) );
	$acl = json_encode ( $acl );
	$response = $baidu_bcs->set_bucket_acl ( $bucket, $acl );
	printResponse ( $response );
}

/**
 * ************************object********************************** *
 * */

function bs_log($log) {
	trigger_error ( basename ( __FILE__ ) . " [time: " . time () . "][LOG: $log]" );
}

function create_object($baidu_bcs) {
	global $fileUpload, $object, $bucket;
	$opt = array ();
	$opt ['acl'] = BaiduBCS::BCS_SDK_ACL_TYPE_PUBLIC_WRITE;
	$opt [BaiduBCS::IMPORT_BCS_LOG_METHOD] = "bs_log";
	$opt ['curlopts'] = array (
			CURLOPT_CONNECTTIMEOUT => 10, 
			CURLOPT_TIMEOUT => 1800 );
	$response = $baidu_bcs->create_object ( $bucket, $object, $fileUpload, $opt );
	printResponse ( $response );
}

function create_object_superfile($baidu_bcs) {
	global $fileUpload, $object, $bucket;
	$opt = array ();
	$opt ['acl'] = BaiduBCS::BCS_SDK_ACL_TYPE_PRIVATE;
	$opt [BaiduBCS::IMPORT_BCS_LOG_METHOD] = "bs_log";
	$opt ["sub_object_size"] = 1024 * 256;
	$response = $baidu_bcs->create_object_superfile ( $bucket, $object, $fileUpload, $opt );
	printResponse ( $response );
}

function pre_filter($bucket, $object, $file, &$tmp_opt) {
	//举例在上传文件在$opt中加入一个特定串，在post_filter中取出并打印
	$tmp_opt ["something"] = "something about [$object]";
	return true;
}

function post_filter($bucket, $object, $file, &$tmp_opt, $response) {
	//配合
	trigger_error ( $tmp_opt ["something"] );
	return;
}

function upload_directory($baidu_bcs) {
	global $upload_dir, $bucket;
	$opt = array (
			"prefix" => "/20110622", 
			"has_sub_directory" => true, 
			BaiduBCS::IMPORT_BCS_PRE_FILTER => "pre_filter", 
			BaiduBCS::IMPORT_BCS_POST_FILTER => "post_filter" );
	$baidu_bcs->upload_directory ( $bucket, $upload_dir, $opt );
}

function copy_object($baidu_bcs) {
	global $object, $bucket;
	$source = 'bs://' . $bucket . $object;
	
	$source = array (
			'bucket' => $bucket, 
			'object' => $object );
	$dest = array (
			'bucket' => $bucket, 
			'object' => $object . 'copy' );
	$response = $baidu_bcs->copy_object ( $source, $dest );
	printResponse ( $response );
	if ($response->isOK ()) {
		echo "you can download from =" . $baidu_bcs->generate_get_object_url ( $bucket, $dest ['object'] );
	}
}

function set_object_meta($baidu_bcs) {
	global $bucket, $object;
	$meta = array (
			"Content-Type" => BCS_MimeTypes::get_mimetype ( "pdf" ) );
	$response = $baidu_bcs->set_object_meta ( $bucket, $object, $meta );
	printResponse ( $response );
}

function get_object($baidu_bcs) {
	global $object, $bucket, $fileWriteTo;
	$opt = array (
			'fileWriteTo' => $fileWriteTo );
	$response = $baidu_bcs->get_object ( $bucket, $object, $opt );
	if ($response->isOK ()) {
		echo "response is OK\n";
	} else {
		printResponse ( $response );
	}
}

function delete_object($baidu_bcs) {
	global $object, $bucket;
	$response = $baidu_bcs->delete_object ( $bucket, $object );
	printResponse ( $response );
}

function get_object_acl($baidu_bcs) {
	global $bucket, $object;
	$response = $baidu_bcs->get_object_acl ( $bucket, $object );
	printResponse ( $response );
}

function set_object_acl_by_acl_type($baidu_bcs) {
	global $bucket, $object;
	//	$acl = BaiduBCS::BCS_SDK_ACL_TYPE_PUBLIC_CONTROL;
	$acl = BaiduBCS::BCS_SDK_ACL_TYPE_PUBLIC_READ;
	//	$acl = BaiduBCS::BCS_SDK_ACL_TYPE_PUBLIC_READ_WRITE;
	//	$acl = BaiduBCS::BCS_SDK_ACL_TYPE_PUBLIC_WRITE;
	//	$acl = BaiduBCS::BCS_SDK_ACL_TYPE_PRIVATE;
	$response = $baidu_bcs->set_object_acl ( $bucket, $object, $acl );
	printResponse ( $response );
}

function set_object_acl_by_json_string($baidu_bcs) {
	global $bucket, $object;
	$acl = array (
			'statements' => array (
					'0' => array (
							'user' => array (
									'psp:jason_zhengkan' ), 
							'resource' => array (
									$bucket . $object ), 
							'action' => array (
									BaiduBCS::BCS_SDK_ACL_ACTION_GET_OBJECT, 
									BaiduBCS::BCS_SDK_ACL_ACTION_PUT_OBJECT ), 
							'effect' => 'allow' ) ) );
	
	$response = $baidu_bcs->set_object_acl ( $bucket, $object, json_encode ( $acl ) );
	printResponse ( $response );
}

function set_object_acl_by_json_array($baidu_bcs) {
	global $bucket, $object;
	$acl = array (
			'statements' => array (
					'0' => array (
							'user' => array (
									"*" ), 
							'resource' => array (
									$bucket . $object ), 
							'action' => array (
									BaiduBCS::BCS_SDK_ACL_ACTION_GET_OBJECT, 
									BaiduBCS::BCS_SDK_ACL_ACTION_PUT_OBJECT, 
									BaiduBCS::BCS_SDK_ACL_ACTION_DELETE_OBJECT ), 
							'effect' => 'allow' ) ) );
	
	$response = $baidu_bcs->set_object_acl ( $bucket, $object, $acl );
	printResponse ( $response );
}

function is_object_exist($baidu_bcs) {
	global $bucket, $object;
	$bolRes = $baidu_bcs->is_object_exist ( $bucket, $object );
	echo $bolRes == true ? "Object exist" : "Object not exist";
}

function get_object_info($baidu_bcs) {
	global $bucket, $object;
	$response = $baidu_bcs->get_object_info ( $bucket, $object );
	printResponse ( $response );
	var_dump ( $response->header );
}

function generate_get_object_url($baidu_bcs) {
	global $bucket, $object;
	$opt = array ();
	$opt ["time"] = time () + 3600; //可选，链接生效时间为linux时间戳向后一小时 
	//$opt ["ip"] = "10.81.42.123"; //可选，限制本链接发起的客户端ip
	

	echo $baidu_bcs->generate_get_object_url ( $bucket, $object, $opt );
}

function generate_put_object_url($baidu_bcs) {
	global $bucket, $object;
	$opt = array ();
	$opt ["time"] = time () + 3600; //可选，链接生效时间为linux时间戳向后一小时 
	$opt ["size"] = 1024 * 1024; //可选，用户上传时，限制上传大小，这里限制1MB
	//"ip" => "192.168.1.1"    //可选，限制本链接发起的客户端ip
	

	echo $baidu_bcs->generate_put_object_url ( $bucket, $object, $opt );
}

function generate_post_object_url($baidu_bcs) {
	global $bucket, $object;
	$opt = array ();
	$opt ["time"] = time () + 3600; //可选，链接生效时间为linux时间戳向后一小时 
	$opt ["size"] = 1024 * 1024; //可选，用户上传时，限制上传大小，这里限制1MB
	//"ip" => "192.168.1.1"    //可选，限制本链接发起的客户端ip
	

	echo $baidu_bcs->generate_post_object_url ( $bucket, $object, $opt );
}

function printResponse($response) {
	echo $response->isOK () ? "OK\n" : "NOT OK\n";
	echo 'Status:' . $response->status . "\n";
	echo 'Body:' . $response->body . "\n";
	echo "Header:\n";
	var_dump ( $response->header );
}
?>