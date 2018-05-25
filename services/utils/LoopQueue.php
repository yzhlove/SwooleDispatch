<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/15
 * Time: 下午5:21
 */

# 循环队列


namespace services\utils;

use services\keys\RedisKey;

class LoopQueue
{

    private $_hot_cache;
    private $_room_id;
    private $_queue_key;

    # 每个房间维持一个循环队列
    public function __construct($tag)
    {
        $this->_room_id = $tag;
        $this->_hot_cache = RedisHelp::getInstance();
        # 队列Key初始化
        $this->_queue_key = RedisKey::_LOOP_QUEUE . $tag;
        $this->_hot_cache->expire($this->_queue_key,RedisKey::_LOOP_QUEUE_EXPIRE);  # 超时时间
    }

    # 添加
    public function push($value)
    {
        return $this->_hot_cache->rpush($this->_queue_key, $value);
    }

    # 查找下一个轮到谁
    public function next()
    {
        $value = $this->_hot_cache->lpop($this->_queue_key);
        $this->_hot_cache->rpush($this->_queue_key, $value);
//        return $this->_hot_cache->lrange($this->_queue_key,0,0);
        return $value;
    }

    # 弹出队头
    public function pop() {
        return $this->_hot_cache->lpop($this->_queue_key);
    }

    # 弹出指定的元素
    public function remove($value)
    {
        return $this->_hot_cache->lrem($this->_queue_key, $value,1);
    }

    # 删除
    public function delete()
    {
        return $this->_hot_cache->expire($this->_queue_key, 0);
    }

    # 查看所有
    public function show()
    {
        return $this->_hot_cache->lrange($this->_queue_key, 0, -1);
    }

}

