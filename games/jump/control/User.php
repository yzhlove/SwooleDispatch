<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/15
 * Time: 下午8:37
 */

# user_id
# room_id
# score
# loop_num
# user_name
# avater_url
# created_at
# updated_at
# status

namespace games\jump\control;


use games\jump\help\REDISKey;
use services\utils\RedisHelp;

class User
{

    private $_user_id;
    private $_hot_cache;
    private $_user_key;

    public function __construct($user_id)
    {
        $this->_user_id = $user_id;
        $this->_hot_cache = RedisHelp::getInstance();
        $this->_user_key = REDISKey::_ROOM_USER_KEY . $user_id;
        $this->_hot_cache->expire($this->_user_key, REDISKey::_USER_EXPIRE);
    }

    public function __call($name, $arguments)
    {
        if (!empty($arguments)) {
            $data = $arguments[0];
            if (is_object($arguments[0]) || is_array($arguments[0]))
                $data = json_encode($arguments[0],JSON_UNESCAPED_UNICODE);
        }
        $prefix = substr($name, 0, 3);
        $user_type_key = "user_type_key_" . REDISKey::uncamelize(substr($name, 3));
        $result  = null;
        if ("set" == $prefix) {
            $result = $this->hset($this->_user_key,$user_type_key,$data);
        } elseif ("get" == $prefix) {
            $result = $this->hget($this->_user_key,$user_type_key);
        }
        return $result;
    }

    # 设置参数
    public function setUser(& $opts) {
        $time = time();
        $user = [
            'status' => ROOM_USER_STATUS_WAIT,
            'user_id' => $opts['user_id'],
            'user_fd' => $opts['user_fd'],
            'room_id' => $opts['room_id'],
            'score' => 0,
            'user_name' => $opts['user_name'],
            'avatar_url' => $opts['avatar_url'],
            'created_at' => $time,
            'updated_at' => $time
        ];
        foreach ($user as $key => $value) {
            $type = "set" . $key;
            $this->$type($value);
            info("User:",' operator:',$type,' value:',$value);
        }
    }

    # 销毁用户对象
    public function delete() {
        $this->_hot_cache->expire($this->_user_key,0);
    }

    # 拿到一个用户的所有信息
    public function show() {
        return $this->_hot_cache->hgetall($this->_user_key);
    }

    # 验证用户是否已经操作

}
