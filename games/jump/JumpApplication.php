<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/17
 * Time: 下午8:08
 */

namespace games\jump;


use games\jump\control\ContextRoom;
use services\Application;
use services\route\Route;

class JumpApplication extends Application {

//    protected $_route;

    private $_ctxRoom;

    public function __construct(Route $route)
    {
        parent::__construct($route);
        $this->_ctxRoom = new ContextRoom($route);
    }


    public function open()
    {
        $this->_ctxRoom->joinRoom();
    }

    public function close()
    {
        $this->_ctxRoom->deleteRoom();
    }

    # 交给ContextRoom处理
    public function __call($name, $arguments)
    {
        info('JumpApplication => ','name:',$name,' arguments:',implode(',',$arguments));
        $this->_ctxRoom->$name();
    }

}