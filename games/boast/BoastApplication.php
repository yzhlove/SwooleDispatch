<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/24
 * Time: 下午8:55
 */

namespace games\boast;

use games\boast\control\Room;
use services\Application;

class BoastApplication extends Application {

    public function open()
    {
        /* ************************************
         * ----- 暂时不考虑送礼 ------
         * 1. 客户端发起连接请求，将第一个进来的用户加入房间
         * 2. 根据房间状态来加入用户。
         *  2.1 房间处于等待人员加入状态。
         *     +  麦位不为零的用户可以加入(site!=0)游戏，同时房主可以将这些用户踢出。踢出的用户五分钟之内不得加入游戏。
         *     +  麦位为零的用户只能加入直播。
         *  2.2 房间处于游戏中状态。
         *     +  所有新进来的用户只能加入直播。不得参与游戏。
         *  2.3 房间处于关闭状态。(特殊状态)
         *     +  所有新连接的用户不得加入房间
         *
         * *************************************/

        $room = new Room($this->_route->getRoomId());
        if ($room->exists()) {
            $room->createRoom($this->_route->show());
        }




    }

    public function close()
    {

    }


}
