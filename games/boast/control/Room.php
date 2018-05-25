<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/24
 * Time: 下午9:07
 */

namespace games\boast\control;

use services\utils\RedisHelp;

class Room {

    private $_room_id;
    private $_hot_cache;
    private $_room_key;

    public function __construct($room_id)
    {
        $this->_room_id = $room_id;
        $this->_hot_cache = RedisHelp::getInstance();
    }

    public function createRoom(& $opts) {

    }

    public function addRoom(& $opts) {

    }

    public function exists() {
        return $this->_hot_cache->exists($this->_room_key);
    }

}