<?php
require dirname ( __FILE__ ) . "/conf.php";
require dirname ( realpath ( __FILE__ ) ) . "/../../bcs.class.php";

$log_path = "./log";
$log_file_prefix = "/upload_log*";
if ($argc > 1) {
	$log_path = $argv [1];
}

//preg
$get_object_all_p = '/id\[.*\]/i';
$get_object_p = '/object\[(.*)\]file\[' . preg_quote ( $upload_dir, '/' ) . '/i';
$get_file_p = '/file\[(.*)\]$/i';
$get_directory_p = '/upload_dir\[(.+?)\]/i';
$get_bucket_p = '/bucket\[(.+?)\]/i';
$get_file_sum_p = '/file_sum\[(.+?)\]/i';
$get_seek_object_id_p = '/seek_object_id\[(.+?)\]/i';
$get_seek_object_p = '/seek_object\[(.+?)\]/i';

$success_p = "/^Notice: Upload Success .*/";
$failed_p = "/^Notice: Upload Failed .*/";
$directory_info_p = '/^Notice: Upload directory: .*/';

$baidu_bcs = new BaiduBCS ( $ak, $sk, $host );

//找出日志文件
$log_files = BaiduBCS::get_filetree ( $log_path, $log_file_prefix );
sort ( $log_files );
if (! empty ( $log_files )) {
	echo "************************************************************\r\n";
	echo "************************** log files ***********************\r\n";
	echo "************************************************************\r\n";
	foreach ( $log_files as $log_file ) {
		echo "$log_file\r\n";
	}
} else {
	echo "No log files found in[$log_path]. Please check.";
	exit ( 0 );
}

//逐行扫描日志文件，并head 文件是否存在


$result = array (
		"bucket" => "", 
		"DirectoryInfo" => array (
				"directory" => "", "file_sum" => "", 
				"seek_object_id" => NULL, "seek_object" => "" ), 
		"Successed" => array (
				"num" => 0, "upload_success_but_not_exist" => array () ), 
		"Failed" => array () );
$opt = array (
		BaiduBCS::IMPORT_BCS_LOG_METHOD => "log_null" );

//head object
foreach ( $log_files as $log_file ) {
	$handler = fopen ( $log_file, "r" );
	if (false === $handler) {
		trigger_error ( "ERROR: fopen file failed! " );
		fclose ( $handler );
		return false;
	}
	while ( ! feof ( $handler ) ) {
		$line = fgets ( $handler, 2048 );
		if (empty ( $line )) {
			continue;
		}
		$success_p_res = preg_match ( $success_p, $line );
		$failed_p_res = preg_match ( $failed_p, $line );
		$directroy_info_p_res = preg_match ( $directory_info_p, $line );
		if ($success_p_res > 0) {
			$object = get_info_from_brackets ( $get_object_p, $line );
			$bolRes = $baidu_bcs->is_object_exist ( $bucket, $object, $opt );
			if ($bolRes) {
				$result ["Successed"] ["num"] ++;
			} else {
				$result ["Successed"] ["upload_success_but_not_exist"] [] = get_info ( $get_object_all_p, $line );
			}
		} elseif ($failed_p_res > 0) {
			$result ["Failed"] [] = get_info ( $get_object_all_p, $line );
		} elseif ($directroy_info_p_res > 0) {
			$result ["bucket"] = get_info_from_brackets ( $get_bucket_p, $line );
			$result ["DirectoryInfo"] ["directory"] = get_info_from_brackets ( $get_directory_p, $line );
			$result ["DirectoryInfo"] ["file_sum"] = get_info_from_brackets ( $get_file_sum_p, $line );
			if (NULL == ($result ["DirectoryInfo"] ["seek_object_id"] = get_info_from_brackets ( $get_seek_object_id_p, $line ))) {
				unset ( $result ["DirectoryInfo"] ["seek_object_id"] );
			}
			if (NULL == ($result ["DirectoryInfo"] ["seek_object"] = get_info_from_brackets ( $get_seek_object_p, $line ))) {
				unset ( $result ["DirectoryInfo"] ["seek_object"] );
			}
		
		} else {
			continue;
		}
	}
	fclose ( $handler );
}

print_result ( $result );

function print_result($result) {
	echo "************************************************************\r\n";
	echo "**************************  Summary  ***********************\r\n";
	echo "************************************************************\r\n";
	echo "Task Info:\r\n";
	echo "\tBucket: " . $result ["bucket"] . "\r\n";
	echo "\tDirectory: " . $result ["DirectoryInfo"] ["directory"] . "\r\n";
	echo "\tFile Sum: " . $result ["DirectoryInfo"] ["file_sum"] . "\r\n";
	if (isset ( $result ["DirectoryInfo"] ["seek_object_id"] )) {
		echo "\tSeek Object From ID: " . $result ["DirectoryInfo"] ["seek_object_id"] . "\r\n";
	}
	if (isset ( $result ["DirectoryInfo"] ["seek_object"] )) {
		echo "\tSeek Object From : " . $result ["DirectoryInfo"] ["seek_object"] . "\r\n";
	}
	echo "\tUpload Success num :" . ($result ["Successed"] ["num"] + count ( $result ["Successed"] ["upload_success_but_not_exist"] )) . "\r\n";
	echo "\tUpload Failed num : " . count ( $result ["Failed"] ) . "\r\n";
	echo "************************************************************\r\n";
	echo "Upload Success:\r\n";
	echo "\tNum : " . ($result ["Successed"] ["num"] + count ( $result ["Successed"] ["upload_success_but_not_exist"] )) . "\r\n";
	echo "\tCheck Result:\r\n";
	echo "\t\tCheck exist:\r\n";
	echo "\t\t\tNum : " . $result ["Successed"] ["num"] . "\r\n";
	if (! empty ( $result ["Successed"] ["upload_success_but_not_exist"] )) {
		echo "\t\tCheck not exist:\r\n";
		echo "\t\t\tNum : " . count ( $result ["Successed"] ["upload_success_but_not_exist"] ) . "\r\n";
		echo "\t\t\tThese objects may have problems, upload success but s3 says not exist:\r\n";
		foreach ( $result ["Successed"] ["upload_success_but_not_exist"] as $str ) {
			echo "\t\t\t\t$str\r\n";
		}
	}
	if (! empty ( $result ["Failed"] )) {
		echo "************************************************************\r\n";
		echo "Upload Failed:\r\n";
		echo "\tNum : " . count ( $result ["Failed"] ) . "\r\n";
		echo "\tList:\r\n";
		foreach ( $result ["Failed"] as $str ) {
			echo "\t\t$str\r\n";
		}
	}
}

function get_info_from_brackets($pattern, $line) {
	preg_match_all ( $pattern, $line, $matches );
	if (! empty ( $matches [0] )) {
		return $matches [1] [0];
	} else {
		return NULL;
	}
}

function get_info($pattern, $line) {
	preg_match_all ( $pattern, $line, $matches );
	if (! empty ( $matches [0] )) {
		return $matches [0] [0];
	} else {
		return NULL;
	}
}

function log_null($log) {
	//do nothing
}

?>
