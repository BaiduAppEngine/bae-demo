
<?php
require_once("../configure.php");
/*连接数据库所需要的五要素（可从数据库详情页中查到相应信息）*/
$dbname = ServiceConf::$mongo_cfg['dbname'];;
$host = ServiceConf::$mongo_cfg['host'];
$port = ServiceConf::$mongo_cfg['port'];;
$user = ServiceConf::$aksk['ak'];;
$pwd = ServiceConf::$aksk['sk'];;

try {
	/*建立连接后，在进行集合操作前，需要先select使用的数据库，并进行auth*/
	$mongoClient = new MongoClient("mongodb://{$host}:{$port}");
	$mongoDB = $mongoClient->selectDB($dbname);
	$mongoDB->authenticate($user, $pwd);

	/*接下来就可以对该库上的集群进行操作了，具体操作方法请参考php-mongodb官方文档*/

	//集合并不需要预先创建
	$mongoCollection = $mongoDB->selectCollection('test_mongo');

	//插入数据
	$array = array(
		'no' => new MongoInt32('2007'),
		'name' => 'this is a test message',
	);
	$mongoCollection->insert($array);
	$array = array(
		'no' => new MongoInt32('2008'),
		'name' => 'this is another test message',
	);
	$mongoCollection->insert($array);
	$array = array(
		'no' => new MongoInt32('2009'),
		'name' => 'xxxxxxxx',
	);
	$mongoCollection->insert($array);

	//删除数据
	$mongoCollection->remove(array('no'=> 2008));

	//更新数据
	$mongoCollection->update(array('no' => 2009), array('$set'=>array('name'=>'yyyyyy')));

	//检索数据
	$mongoCursor = $mongoCollection->find();
	while($mongoCursor->hasNext()) {
		$ret = $mongoCursor->getNext();
		echo json_encode($ret) . '<br />';
	}

	//删除集合
	$mongoCollection->drop();

} catch (Exception $e) {
	die($e->getMessage());
}
?>
