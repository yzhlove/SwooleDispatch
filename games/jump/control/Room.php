<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/15
 * Time: 下午5:01
 */

namespace games\jump\control;

# room_id
# status
# loop_num
# timestamp
# owner_id
# reset


use games\jump\help\LoopQueue;
use games\jump\help\Queue;
use games\jump\help\REDISKey;
use services\utils\RedisHelp;

class Room {

    private $_hot_cache;
    private $_room_id;
    private $_room_key;

    private $_loop_queue;
    private $_generate_queue;

    public function __construct($room_id)
    {
        $this->_room_id = $room_id;
        $this->_hot_cache = RedisHelp::getInstance();
        $this->_room_key = REDISKey::_ROOM_KEY . $room_id;
        $this->_hot_cache->expire($this->_room_key,REDISKey::_ROOM_EXPIRE);
        $this->_loop_queue = new LoopQueue($this->_room_id);
        $this->_generate_queue = new Queue($this->_room_id);
    }

    # 初始化房间
    public function initRoom(& $opts) {
        $room = [
            'room_id' => $this->_room_id,
            'status' => REDISKey::_ROOM_STATUS_WAIT,
            'online_num' => $opts['user_num_limit'],
            'timestamp' => time(),
            'reset' => ROOM_RESET_STATUS_FALSE,
        ];
        # 房主
        if (!$opts['owner_id'])
            $room['owner_id'] = $opts['owner_id'];

        # 设置值
        foreach ($room as $key => $value) {
            $method = "set" . $key;
            $this->$method($value);
        }
    }

    # 设置房间
    public function __call($name, $arguments)
    {
        info('Room=>','name:',$name,' arguments:',implode(',',$arguments));
        if (!empty($arguments)) {
            $data = $arguments[0];
            if (is_object($arguments[0]) || is_array($arguments[0]))
                $data = json_encode($arguments[0],JSON_UNESCAPED_UNICODE);
        }

        $prefix = substr($name, 0, 3);
        $user_type_key = "user_type_key_" . REDISKey::uncamelize(substr($name, 3));
        $result  = null;
        if ("set" == $prefix) {
            $result = $this->_hot_cache->hset($this->_room_key,$user_type_key,$data);
        } elseif ("get" == $prefix) {
            $result = $this->_hot_cache->hget($this->_room_key,$user_type_key);
        }
        return $result;
    }

    # 将用户加入房间
    public function setUserToRoom($user_id) {
        if ($this->getStatus() == REDISKey::_ROOM_STATUS_WAIT)
            $this->setUserToLoopQueue($user_id);
        if ($this->getStatus() == REDISKey::_ROOM_STATUS_START)
            $this->setUserToGenerateQueue($user_id);
    }

    # 将用户加入队列
    protected function setUserToLoopQueue($user_id) {
        $this->_loop_queue->push($user_id);
    }
    protected function setUserToGenerateQueue($user_id) {
        $this->_generate_queue->push($user_id);
    }

    # 拿到所有的用户
    public function getUserIds() {
        $loop_queue_users = $this->getSiteUserIds();
        $queue_users = $this->getWatchUserIds();
        $user_ids = array_merge($loop_queue_users,$queue_users);
        return $user_ids;
    }

    # 拿到游戏用户
    public function getSiteUserIds() {
        return $this->_loop_queue->show();
    }

    # 拿到观察用户
    public function getWatchUserIds() {
        return $this->_generate_queue->show();
    }

    # 拿到下一个用户的信息
    public function getNextUser() {
        return $this->_loop_queue->next();
    }


    # 清除房间信息
    public function clearRoom() {

    }

    # 清除队列信息
    public function clearQueue() {
        $this->_loop_queue->delete();
        $this->_generate_queue->delete();
    }

}
