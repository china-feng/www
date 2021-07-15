<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);

$connection = new \MongoDB\Driver\Manager("mongodb://129.211.187.162:27017");


//查询操作
$id           = new \MongoDB\BSON\ObjectId("60ec67003e2f6172a06d4277");
$filter      = ['_id' => $id];
$options = [];

$query = new \MongoDB\Driver\Query($filter, $options);
$rows   = $connection->executeQuery('test.sxcGameLog20210713', $query)->toArray();  //test.sxcGameLog20210713 数据库名.表名

//增删改查可以参考下面链接
//https://www.runoob.com/mongodb/php7-mongdb-tutorial.html

/*echo '<pre>';
var_dump($rows);
echo '</pre>';
foreach ($rows as $v) {
	echo '<pre>';
	var_dump($v);
	echo '</pre>';
}*/


//aggregate 操作
$param = [
'aggregate' => 'sxcGameLog20210713',  //数据库名
'pipeline' => [
	[
		'$match' => [
			't' => ['$lte' => 1626105636034]
		]
	],
	[
		'$group' => [
			'_id' => '$uid',
				'hr_count' => [
				'$sum' => '$sc'
			]
		]
	],
	['$skip' => 300],
	['$limit' => 100]

],
'cursor' => ['batchSize' => 0],  //给返回集合设置一个初始大小。
'allowDiskUse'=>true  //可往磁盘写临时数据。
];

$cmd = new MongoDB\Driver\Command($param);
echo '<pre>';
var_dump($connection->executeCommand('test', $cmd)->toArray()); //test 表名
echo '</pre>';
