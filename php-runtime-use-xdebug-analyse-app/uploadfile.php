<?php

require_once './bcs_sdk/bcs.class.php';

$ak = '******';	//用户自己bcs的ak
$sk = '******';	//用户自己bcs的sk
$bucket = '******';	//用户自己bcs的bucket
$baidu_bcs = new BaiduBCS ( $ak, $sk );
$upload_dir = '/home/bae/app/xdebug_dir';	//要上传的文件夹，如果完全按照demo来，则此参数不用改
$res = $baidu_bcs->upload_directory ( $bucket, $upload_dir, array() );
var_dump( $res );
shell_exec('rm -rf /home/bae/app/xdebug_dir/*');
?>