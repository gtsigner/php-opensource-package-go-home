<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/28
 * Time: 21:01
 */

namespace Common\Helper;


class RedisHelper
{
    public static $handle;
    public static $IsConnect = false;

    //初始化链接
    public static function Init($options = array())
    {
        $config = array(
            'host' => C('REDIS_HOST'),
            'port' => C('REDIS_PORT'),
            'timeout' => false,
            'persistent' => false,
        );
        $config = array_merge($config, $options);
        self::$handle = new \Redis();
        self::$IsConnect = self::$handle->connect($config['host'], $config['port'], $config['timeout']);
    }
}