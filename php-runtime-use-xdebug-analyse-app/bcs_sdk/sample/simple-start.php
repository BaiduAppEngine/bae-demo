<?php
require_once '../bcs.class.php';
$host = '';
$ak = '';
$sk = '';
$bucket = 'bcs-phpsdk-simple-start-' . time () . "-" . rand ( 1000, 999 );
$object = '/a.txt';
$fileUpload = './a.txt';
$fileWriteTo = './a.' . time () . '.txt';
$baiduBCS = new BaiduBCS ( $ak, $sk, $host );

//step1. create a bucket
$response = $baiduBCS->create_bucket ( $bucket );

if ($response->isOK ()) {
	echo "Create bucket[$bucket] success\n";

	//step2. create an object
	sleep ( 3 );
	$response = $baiduBCS->create_object ( $bucket, $object, $fileUpload );
	if (! $response->isOK ()) {
		die ( "Create object failed." );
	}
	echo "Create object[$object] in bucket[$bucket] success\n";

	//step3. download this object
	sleep ( 3 );
	$opt = array (
			"fileWriteTo" => $fileWriteTo );
	$response = $baiduBCS->get_object ( $bucket, $object, $opt );
	if (! $response->isOK ()) {
		die ( "Download object failed." );
	}
	echo "Download object[$object] in bucket[$bucket] success. And write to [$fileWriteTo]\n";

    //step4. delete this object
	sleep ( 3 );
    $response = $baiduBCS->delete_object ( $bucket, $object);
	if (! $response->isOK ()) {
		die ( "Delete object failed." );
	}
	echo "Delete object[$object] in bucket[$bucket] success\n";

    //step5. delete this bucket
	sleep ( 3 );
    $response = $baiduBCS->delete_bucket ( $bucket);
	if (! $response->isOK ()) {
		die ( "Delete bucket failed." );
	}
	echo "Delete bucket[$bucket] success\n";
}
