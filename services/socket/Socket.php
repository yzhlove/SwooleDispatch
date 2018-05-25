<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/23
 * Time: 下午5:16
 */

namespace services\socket;

use services\keys\RedisKey;
use services\utils\RedisHelp;

abstract class Socket {

    private $_fd;
    private $_socket_key;
    private $_hot_cache;

    public function __construct($fd)
    {
        $this->_fd = $fd;
        $this->_socket_key = RedisKey::_SOCKET_URL_INFO . $fd;
        $this->_hot_cache = RedisHelp::getInstance();
        $this->_hot_cache->expire($this->_socket_key,RedisKey::_SOCKET_EXPIRE);
    }

    public function init(& $opts) {
        foreach ($opts as $key => $value) {
            $type = 'set' . $key;
            $this->$type($value);
        }
    }


    public function __call($name, $arguments)
    {
        if (!empty($arguments)) {
            $data = $arguments[0];
            if (is_object($arguments[0]) || is_array($arguments[0]))
                $data = json_encode($arguments[0],JSON_UNESCAPED_UNICODE);
        }
        $prefix = substr($name, 0, 3);
        $user_type_key = "user_type_key_" . RedisHelp::uncamelize(substr($name, 3));
        $result  = null;
        if ("set" == $prefix) {
            $result = $this->hset($this->_user_key,$user_type_key,$data);
        } elseif ("get" == $prefix) {
            $result = $this->hget($this->_user_key,$user_type_key);
        }
        return $result;
    }



}