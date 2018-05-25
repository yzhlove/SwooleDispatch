<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/17
 * Time: 下午4:11
 */

# ==> onopen

namespace services\route;

class ConnRoute extends Route {

    # opts: {"name":"jump","username":"ding","room_id":"5435435","user_id":"43244","avater_url":"avater.png","user_num_limit":"4","time":"4324","site":"34","owner":"0","fd":1}

    const _GAME_CLAZZ = "games\\%s\\socket\\%sSocket";

    public function __construct(\swoole_websocket_server $server, $data)
    {
        parent::__construct($server, $data);
        $sock_clazz = $this->getSockClass($this->getGameCode());
        $sock_info = new $sock_clazz($this->getFd());
        $sock_info->init($this->_array_data);
    }

    private function getSockClass($name) {
        return $clazz = sprintf(self::_GAME_CLAZZ, $name, ucfirst($name));
    }

}