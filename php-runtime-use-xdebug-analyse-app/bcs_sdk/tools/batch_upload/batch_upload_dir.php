<?php

require dirname ( realpath ( __FILE__ ) ) . "/../../bcs.class.php";
require dirname ( __FILE__ ) . "/conf.php";
/*
 * 获取线程数和线程id
 */
if ($argc != 3) {
	trigger_error ( "Can not get thread num" );
	exit ();
}
$thread_num = $argv [1];
$thread_id = $argv [2];
trigger_error ( "Upload dir thread start, thread_num=[$thread_num], thread_id=[$thread_id]" );

//开始上传
$baidu_bcs = new BaiduBCS ( $ak, $sk, $host );
$opt = array (
		BaiduBCS::IMPORT_BCS_PRE_FILTER => 'pre_filter', 
		BaiduBCS::IMPORT_BCS_POST_FILTER => 'post_filter', 
		"prefix" => $prefix,
		"has_sub_directory" => $has_sub_directory );
if(isset($seek_object_id)){
	$opt["seek_object_id"] = $seek_object_id;
}
if(isset($seek_object)){
	$opt["seek_object"] = $seek_object;
}
$baidu_bcs->upload_directory ( $bucket, $upload_dir, $opt );


/*
 * 可定制化，发送前操作
 * 比如返回 boolean型
 */
function pre_filter($bucket, $object, $file, &$opt) {
	//文件名的md5 % thread_num 得到的余数若等于thread_id，上传该文件
	global $thread_num, $thread_id;
	$crc32 = crc32 ( $object );
	$mod = ($crc32 > 0 ? $crc32 : - $crc32) % $thread_num;
	if ($mod == $thread_id) {
		return true;
	} else {
		return false;
	}
}
/*
 * 可定制化，发送后操作
 */
function post_filter($bucket, $object, $file, &$opt, $response) {

}
?>
