<?php
/**
 * system .
 * system
 * Author：软件开发,网站建设,网站优化/
 * Date: 2016/8/15
 * Time: 9:21
 */

namespace Common\Util;

/**
 * Token 生成工具箱
 *
 *
 * Author: zhaojunlike@
 * Gtihub: https://github.com/zhaojunlike
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Class TokenUtil
 * @package Common\Util
 */
class TokenUtil
{

    //生成一个token
    public static function createToken($uid)
    {
        //登陆，简化版本
        $tokenStr = md5($uid . "_" . $uid . "_" . time());
        S("token_{$uid}", $tokenStr);
        S("token_str_{$tokenStr}", $uid);
        return $tokenStr;
    }

    //解析token，返回，uid
    public static function parseToken($token)
    {
        return S("token_str_{$token}");
    }


    //销毁token
    public static function destroyToken($uid)
    {
        $tokenStr = S("token_{$uid}");
        S("token_{$uid}", null);//销毁token
        S("token_str_{$tokenStr}", null);//销毁token
    }


}