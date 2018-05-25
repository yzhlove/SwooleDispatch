<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/16
 * Time: 上午11:31
 */

namespace services\utils;

use services\keys\RedisKey;

class Queue {

    private $_hot_cache;
    private $_room_id;
    private $_queue_key;

    public function __construct($room_id,$queue_type = RedisKey::_GENERAL_QUEUE)
    {
        $this->_queue_key = $queue_type . $room_id;
        $this->_hot_cache = RedisHelp::getInstance();
        $this->_hot_cache->expire($this->_queue_key,RedisKey::_LOOP_QUEUE_EXPIRE);
        $this->_room_id = $room_id;
    }

    public function push($value) {
        if (is_object($value))
            $value = json_encode($value,JSON_UNESCAPED_UNICODE);
        return $this->_hot_cache->rpush($this->_queue_key,$value);
    }

    public function pop() {
        return $this->_hot_cache->lpop($this->_queue_key);
    }

    public function top() {
        return $this->_hot_cache->lrange($this->_queue_key,0,0);
    }

    public function isEmpty() {
        return $this->_hot_cache->llen($this->_queue_key) ? TRUE : FALSE;
    }

    public function delete() {
        return $this->_hot_cache->expire($this->_queue_key,0);
    }

    public function show() {
        return $this->_hot_cache->lrange($this->_queue_key,0,-1);
    }

}
