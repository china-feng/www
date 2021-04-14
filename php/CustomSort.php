<?php
//自定义二维数组排序，超过5000条数据 不建议使用
//自定义排序

class CustomSort
{	
	/**
	 * 
	 * @param  [array] $data  [二维数组]
	 * @param  array  $order [排序规则]
	 * @return [array]        [排序后的数组]
	 */
	public function sort($data, $order = array('field1' => 'desc', 'field2' => 'asc')){
    	//一个节点是一个二维数据  三个属性（节点值, 层级, 节点数据个数, 节点数据）
    	//array('8.9', '1', array($v));
    	if (empty($order)) {
    		return $data;
    	}
    	$order_ = array();
    	foreach ($order as $k => $v) {
    		$order_[] = array('field' => $k, 'order' => $v);
    	}

    	$tem_array = array();
    	$return_array = array();
    	foreach ($data as $k => $v) {
    		$node = $this->node($v[$order_[0]['field']], $v);
    		$tem_array = $this->insertNode($tem_array, $node, $order_);
            // var_dump($tem_array);
    	}
    	// return $tem_array;
    	return $this->getValue($tem_array);
    }

    public function getValue($data, $is_return = true){
    	if (empty($data)) {
    		return array();
    	}
    	static $return_data;
    	foreach ($data as $k => $v) {
    		if ($v->is_arr) {
    			// var_dump($v);
    			$this->getValue($v->data, false);
    		} else {
    			$return_data[] = $v->data;
    		}
    	}
    	if ($is_return) {
    		return $return_data;
    	}
    }

    public function insertNode($node_arr, $need_insert_node, $order, $level = 0){  //节点组  需要插入的节点
        if (!isset($order[$level])) {
            $node_arr[] = $need_insert_node;
            return $node_arr;
        }
        $is_have_eq = false;
        $eq_index = 0;
        foreach ($node_arr as $k => $v) {
            if ($v->value == $need_insert_node->value) {
                $is_have_eq = true;
                $eq_index = $k;
                break;
            }
        }
        if ($is_have_eq) {  //如果有相等的
            $level++;
            $node_arr_obj = $node_arr[$eq_index];
            $node_arr_obj_data = $node_arr_obj->data;
            $need_insert_node_data = $need_insert_node->data;
            $new_need_insert_node_value = 0;
            $new_arr_obj_value = 0;
            if (isset($order[$level])) {
                $new_need_insert_node_value = $need_insert_node_data[$order[$level]['field']];
                if ($node_arr_obj->is_arr !== true) $new_arr_obj_value = $node_arr_obj_data[$order[$level]['field']];
            }
            if ($node_arr_obj->is_arr === true) {
                $node_arr[$eq_index]->data = $this->insertNode($node_arr_obj_data, $this->node($new_need_insert_node_value, $need_insert_node_data), $order, $level);
            } else {
                $node_arr[$eq_index]->data  = $this->insertNode(array($this->node($new_arr_obj_value, $node_arr_obj_data)), $this->node($new_need_insert_node_value, $need_insert_node_data), $order, $level);
                $node_arr[$eq_index]->is_arr = true;
            }
        } else {
        	$node_arr[] = $need_insert_node;
        	if (!isset($order[$level]) || !isset($order[$level])) {
        		return $node_arr;
        	}
        	$flag = true; //是否倒序
    		if (strtolower($order[$level]['order']) == 'asc') {
    			$flag = false;
    		}
            $is_add = false; //是否插入
        	$arr_len = count($node_arr);
        	for ($i = $arr_len - 1; $i > 0; $i--) {
        		if ($node_arr[$i]->value == $node_arr[$i - 1]->value) {

        			$level++;
        			$node_arr_obj = $node_arr[$i - 1];
        			$node_arr_obj_data = $node_arr_obj->data;
        			$need_insert_node_data = $node_arr[$i]->data;
        			
        			$new_need_insert_node_value = 0;
        			$new_arr_obj_value = 0;
        			if (isset($order[$level])) {
        				$new_need_insert_node_value = $need_insert_node_data[$order[$level]['field']];
        				if ($node_arr[$i]->is_arr !== true) $new_arr_obj_value = $node_arr_obj_data[$order[$level]['field']];
        			}
        			if ($node_arr[$i]->is_arr === true) {
        				$node_arr[$i]->data = $this->insertNode($node_arr_obj_data, $this->node($new_need_insert_node_value, $need_insert_node_data), $order, $level);
        			} else {
        				$node_arr[$i]->data  = $this->insertNode(array($this->node($new_arr_obj_value, $node_arr_obj_data)), $this->node($new_need_insert_node_value, $need_insert_node_data), $order, $level);
        				$node_arr[$i]->is_arr = true;
        			}
        			break;
        		}
        	    if ($flag) {
    	    		if ($node_arr[$i]->value > $node_arr[$i - 1]->value) {
                        $tem = $node_arr[$i];
                        $node_arr[$i] = $node_arr[$i - 1];
    	    			$node_arr[$i - 1] = $tem;   
    	    		}
        		} else {
    	    		if ($node_arr[$i]->value < $node_arr[$i - 1]->value) {
    	    			$tem = $node_arr[$i];
                        $node_arr[$i] = $node_arr[$i - 1];
                        $node_arr[$i - 1] = $tem; 
    	    		}
        		}
        	}
        }
        
    	return $node_arr;
    }

    public function node($value, $data){
    	$node = new \stdclass();
    	$node->value = $value;
    	$node->is_arr = false;
    	$node->data = $data;
    	return $node;
    }
}
