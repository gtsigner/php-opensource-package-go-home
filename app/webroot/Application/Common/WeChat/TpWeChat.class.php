<?php
/**
 * Created by PhpStorm.
 * User: QQ: 209900956
 * oschina:http://git.oschina.net/zhaojunlike
 * github: http://github.com/zhaojunlike
 * Author：软件开发，网站建设/
 * Date: 2016/7/28
 * Time: 16:34
 */

namespace Common\WeChat;

use Think\Log;

class TpWeChat extends WeChat
{
    /**
     * log overwrite
     * @see Wechat::log()
     */
    protected function log($log)
    {
        if ($this->debug) {
            if (function_exists($this->logcallback)) {
                if (is_array($log)) $log = print_r($log, true);
                return call_user_func($this->logcallback, $log);
            } elseif (class_exists('Log')) {
                Log::write('wechat：' . $log, Log::DEBUG);
                return true;
            }
        }
        return false;
    }

    /**
     * 重载设置缓存
     * @param string $cachename
     * @param mixed $value
     * @param int $expired
     * @return boolean
     */
    protected function setCache($cachename, $value, $expired)
    {
        return S($cachename, $value, $expired);
    }

    /**
     * 重载获取缓存
     * @param string $cachename
     * @return mixed
     */
    protected function getCache($cachename)
    {
        return S($cachename);
    }

    /**
     * 重载清除缓存
     * @param string $cachename
     * @return boolean
     */
    protected function removeCache($cachename)
    {
        return S($cachename, null);
    }
}