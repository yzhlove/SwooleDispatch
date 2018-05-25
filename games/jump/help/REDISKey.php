<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/15
 * Time: 下午5:01
 */

namespace games\jump\help;

class REDISKey {





    # 房间状态
    const _ROOM_STATUS_WAIT = 0;
    const _ROOM_STATUS_START = 1;
    const _ROOM_STATUS_END = 2;

    # 用户状态
    const _USER_STATUS_WAIT = 0x01;
    const _USER_STATUS_START = 0x02;
    const _USER_STATUS_END = 0x03;


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