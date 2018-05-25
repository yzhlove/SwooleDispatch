<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/2/2
 * Time: 下午9:59
 */

/* 自动实例话对象并处理 */

namespace services;

use services\route\CloseRoute;
use services\route\ConnRoute;
use services\route\MessageRoute;
use services\route\Route;

class Adapter
{

    const _GAME_CLAZZ = "games\\%s\\%sApplication";
    const _GAME_TYPE = "GameCode";

    public function dispatch(Route $route)
    {
        $type = self::_GAME_TYPE;   # 字段名
        $clazz = $this->getClass($route->$type());
        if (!class_exists($clazz)) {
            $msg = ['action' => 'error', 'reason' => $clazz . '不存在!'];
            $route->pushMessage($msg);
            return FALSE;
        }
        info('class_clazz:', $clazz);
        $app = new $clazz($route);
        if ($route instanceof ConnRoute) {
            $app->initApp();
            $app->open();
        }
        if ($route instanceof MessageRoute) {
            $method = $route->getAction();
            if (method_exists($app, $method)) {
                $app->$method();
            } else {
                info('Adapter:', ' action is not found[' . $route->getAction() . ']');
                $msg = ['action' => 'error', 'reason' => $route->getAction() . '方法不存在!'];
                $route->pushMessage($msg);
                return FALSE;
            }
        }
        if ($route instanceof CloseRoute) {
            $app->close();
        }
    }

    # 拿到类名
    private function getClass($name)
    {
        return $clazz = sprintf(Adapter::_GAME_CLAZZ, $name, ucfirst($name));
    }

}