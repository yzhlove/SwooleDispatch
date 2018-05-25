<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/24
 * Time: 下午9:13
 */

namespace games\boast\control;

use services\utils\RedisHelp;

class User {

    private $_user_id;
    private $_hot_cahce;

    public function __construct($user_id)
    {
        $this->_user_id = $user_id;
        $this->_hot_cahce = RedisHelp::getInstance();

    }

    # 创建用户
    public function createUser(& $opts) {



    }



}