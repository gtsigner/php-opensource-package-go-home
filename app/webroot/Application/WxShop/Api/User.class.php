<?php
/**
 * Email:zhaojunlike@gmail.com
 * Date: 2017/1/8
 * Time: 14:18
 */

namespace WxShop\Api;


class User
{

    public static function getWeUser($map)
    {
        return M('wechat_user')->where($map)->find();
    }

    public static function addWeUser($playMember)
    {
        return M('wechat_user')->add($playMember);
    }
}