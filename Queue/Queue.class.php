<?php
namespace logic\Queue;

use logic\Lock\File;

class Queue
{
	private $queue_name;
	private $container = array();
	private $data = array();

	private function __construct($queue_name){
		$this->queue_name = $queue_name;
		
		$this->get(); //获取队列数据

		$lock = new File(); //锁定队列
		$lock->lock();
	}


	//
	public static function getInstance($queue_name){
		if (!empty($this->container[$queue_name])) {
			return $this->container[$queue_name];
		}

		$obj = new self($queue_name);
		$this->container[$queue_name] = $obj;
		return $obj;
	}

	public function push($node){
		$this->data[] = $node;
		$this->store();
	}

	public function shift(){
		$node = array_shift($this->data);
		$this->store();
	}

	public function getLen(){
		return count($this->data);
	}

	private function get(){
		$this->data = json_decode(cache($this->data), 1);
	}

	private function store(){
		cache($this->queue_name, json_encode($this->data));
	}

	public function __destruct(){
		$lock->unlock();
	}
}
