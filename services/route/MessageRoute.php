<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/17
 * Time: 下午9:36
 */

# ==> onmessage

namespace services\route;


class MessageRoute extends Route {


    # Swoole\WebSocket\Frame@{{"fd":1,"finish":true,"opcode":1,"data":"{\"action\":\"update_score\",\"data\":{\"score\":18}}"}}

    # 拿到参数包 <==> getData
    public function get($type = "data") {
        return $this->_array_data[$type];
    }

    # 拿到[data]里面的数据,默认所有的参数包都是在[data]里面携带
    public function fetch($param,$default = NULL,$type = "data") {
        if (isset($this->_array_data[$type][$param]))
            return $this->_array_data[$type][$param];
        return $default;
    }

}