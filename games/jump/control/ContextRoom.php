<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/16
 * Time: 下午3:11
 */

# 房间管理
namespace games\jump\control;


use games\jump\help\REDISKey;
use services\route\CloseRoute;
use services\route\ConnRoute;
use services\route\MessageRoute;
use services\route\Route;
use services\utils\SocketConnHelp;

class ContextRoom
{

    private $_route;    # 路由
    private $_room;     # 房间
    private $_user;     # 用户

    public function __construct(Route & $route)
    {
        # 初始化路由，房间，用户
        $this->_route = $route;
        $this->_user = new User($this->_route->getUserId());
        $room_id = $this->_room->getRoomId();
        if ($route instanceof CloseRoute)
            $room_id = $this->_user->getRoomId();
        $this->_room = new Room($room_id);
    }

    public function joinRoom()
    {

        # 判断房间的状态
        if ($this->_room->getStatus() == REDISKey::_ROOM_STATUS_END) {
            $msg = ['action' => 'error', 'reason' => '游戏已结束'];
            $this->_route->pushMessage($msg);
            return FALSE;
        }
        # 初始化房间的信息 房间状态为NULL 且 owner_id = 0 (房主)
        if (!($this->_room->getStatus() && $this->_route->getOwner()))
            $this->_room->initRoom($this->_route->show());
        # 初始化用户信息
        $this->_user->setUser($this->_route->show());
        # 将用户加入房间
        $this->_room->setUserToRoom($this->_user->getUserId());
    }

    public function deleteRoom()
    {
        # 玩家离线，清除操作
        # 1. 房间状态
        # 1.1
    }

    # 开始游戏
    public function start() {
        # 判断用户是不是房主
        if ($this->_user->getOwner()) {
            # 设置当前房间的状态为游戏中
            $this->_room->setStatus(REDISKey::_ROOM_STATUS_START);
            # 拿到房间的游戏用户并改变状态
            $user_ids = $this->_room->getSiteUserIds();
            foreach ($user_ids as $user_id) {
                $user = new User($user_id);
                $user->setStatus(REDISKey::_USER_STATUS_START);
            }
            # 拿到下一个用户的信息
            $user_next_id = $this->_room->getNextUser();
            $next_user = new User($user_next_id);

            $msg = [
                'control_data' => $this->_route->getData(),
                'next_user' => $next_user->show()
            ];

            # 拿到所有用户，像所有用户推送消息
            $ids = $this->_room->getUserIds();
            foreach ($ids as $id) {
                $this->_route->pushMessage($msg,$id);
            }

        } else {
            $msg = ['action' => 'error','reason' => '不是房主!'];
            $this->_route->pushMessage($msg);
        }
    }

    # 控制信息
    public function control() {

        $ids = $this->_room->getUserIds();
        foreach ($ids as $id)
            $this->_route->pushSpecialMessage($this->_route->source(),$id);

    }

    # 更新分数
    public function update_score() {




        # 更新用户分数
        $this->_user->setScore($this->_route->get()['score']);
        # 更新房间的时间戳
        $this->_room->setTimestamp(time());

    }




}


