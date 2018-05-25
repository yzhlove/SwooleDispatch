<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/17
 * Time: 下午5:57
 */

namespace services;

use services\route\Route;
use services\utils\SocketConnHelp;

abstract class Application {

    protected $_route;

    public function __construct(Route $route)
    {
        $this->_route = $route;
    }

    public function initApp() {
        
    }

    abstract public function open() ;
    abstract public function close();



}