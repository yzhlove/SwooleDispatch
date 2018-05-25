<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/21
 * Time: 下午1:39
 */
namespace services\utils ;

final class RedisHelp {

    private static $_hot_cache ;

    private function __construct() {}

    public static function getInstance() {
        if (RedisHelp::$_hot_cache)
            return RedisHelp::$_hot_cache;
        self::$_hot_cache = \Users::getHotWriteCache();
        return RedisHelp::$_hot_cache;
    }

    # user_type => UserType
    public static function camelize($string ,$operator = '_') {
        $string = strtolower($string);
        $string = str_replace($operator,' ',$string);
        $string = ucwords($string);
        return str_replace(' ','',$string);
    }

    # UserType => user_type
    public static function uncamelize($string ,$operator = '_' ) {
        $string = preg_replace('/([a-z])([A-Z])/',"$1" .  $operator . "$2",$string);
        return strtolower($string);
    }


}
