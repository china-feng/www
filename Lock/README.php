<?php
//来源github Yurunsoft/YurunLock
namespace logic\Lock;

//文件锁
$lock = new File();
$lock->lock(); // 阻塞锁
// TODO:在这里做你的一些事情
$lock->unlock(); // 解锁

// 带回调的阻塞锁，防止并发锁处理重复执行
$result = $lock->lock(
	function(){
		// TODO:在这里做你的加锁后处理的任务

	},
	function(){
		// 判断是否其它并发已经处理过任务
		return false;
	}
);
switch($result)
{
	case LockConst::LOCK_RESULT_CONCURRENT_COMPLETE:
		// 其它请求已处理
		break;
	case LockConst::LOCK_RESULT_CONCURRENT_UNTREATED:
		// 在当前请求处理
		break;
	case LockConst::LOCK_RESULT_FAIL:
		// 获取锁失败
		break;
}

// 不阻塞锁，获取锁失败就返回false
if($lock->unblockLock())
{
	// TODO:在这里做你的一些事情
}
else
{
	// 获取锁失败
}

//---------------------------------------------------------------------------
//redis/memcache/memcached锁
$lock = new Redis(	// 可以把Redis替换成Memcache/Memcached，下面代码用法相同
	'我是锁名称',
	array(
		'host'		=>	'127.0.0.1',
		'port'		=>	11211,
		'timeout'	=>	0,
		'pconnect'	=>	false,
	), // 连接配置，留空则为默认值
	0, // 获得锁等待超时时间，单位：毫秒，0为不限制，留空则为默认值
	1, // 获得锁每次尝试间隔，单位：毫秒，留空则为默认值
	3, // 锁超时时间，单位：秒，留空则为默认值
);

$lock->lock(); // 阻塞锁
// TODO:在这里做你的一些事情
$lock->unlock(); // 解锁

// 带回调的阻塞锁，防止并发锁处理重复执行
result = $lock->lock(
	function(){
		// TODO:在这里做你的加锁后处理的任务

	},
	function(){
		// 判断是否其它并发已经处理过任务
		return false;
	}
);
switch($result)
{
	case LockConst::LOCK_RESULT_CONCURRENT_COMPLETE:
		// 其它请求已处理
		break;
	case LockConst::LOCK_RESULT_CONCURRENT_UNTREATED:
		// 在当前请求处理
		break;
	case LockConst::LOCK_RESULT_FAIL:
		// 获取锁失败
		break;
}

// 不阻塞锁，获取锁失败就返回false
if($lock->unblockLock())
{
	// TODO:在这里做你的一些事情
}
else
{
	// 获取锁失败
}