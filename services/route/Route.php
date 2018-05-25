<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/17
 * Time: 下午3:09
 */

namespace services\route;

# 路由对象


use services\utils\RedisHelp;
use services\utils\SocketConnHelp;

abstract class Route
{

    protected $_source_data;    # 原始数据
    protected $_array_data;    # 以解析的json数据
    public $_socket;

    public function __construct(\swoole_websocket_server $server, & $data)
    {
        $this->_socket = $server;
        $this->_source_data = $data;

        # 是否对数据做解码(将json数据转换为数组)
        $this->jsonToArray();
    }

    protected function jsonToArray()
    {
        $this->_array_data = $this->_source_data;
    }

    public function __call($name, $arguments)
    {
        info('Route=>','name:',$name,' arguments:',implode(',',$arguments));
        $prefix = substr($name, 0, 3);
        $type = RedisHelp::uncamelize(substr($name, 3));
        $result = null;
        if ("get" == $prefix) {
            if (array_key_exists($type, $this->_array_data))
                $result = $this->_array_data[$type];
        }
        return $result;
    }

    # 得到json序列化之后的数组
    public function show()
    {
        return $this->_array_data;
    }

    # 得到原始的json串
    public function source()
    {
        return $this->_source_data;
    }

    # 推送消息
    public function pushMessage(& $data, $fd = NULL)
    {
        if ($this->_socket->exist($fd))
            $this->_socket->push(json_encode($data, JSON_UNESCAPED_UNICODE), $fd);
    }

    # 推送消息(需要自己json_encode)
    public function pushSpecialMessage(& $data, $fd = NULL)
    {
        if ($this->_socket->exists($fd))
            $this->_socket->push($data, $fd);
    }

}
