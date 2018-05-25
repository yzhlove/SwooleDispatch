<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/21
 * Time: 上午11:40
 */

# 公共redis_key同一管理

namespace services\keys;

class RedisKey
{

    # 用户与socket绑定
    const _BIND_SOCKET_FD = "bind_socket_fd_";
    const _BIND_USER_ID = "bind_user_id_";

    # 过期时间
    const _SOCKET_USER_EXPIRE = 60;

    # socket 链接信息保持
    const _SOCKET_URL_INFO = "sock_url_info_";

    # URL信息过期时间
    const _SOCKET_EXPIRE = 3600;

    # key
    const _LOOP_QUEUE = "loop_queue_";
    const _ROOM_KEY = "room_data_key_";
    const _ROOM_USER_KEY = "room_user_key_";
    const _GENERAL_QUEUE = "general_queue_";

    # 过期时间
    const _LOOP_QUEUE_EXPIRE = 60 * 2;
    const _USER_EXPIRE = 60 ;
    const _ROOM_EXPIRE = 60 ;



}

