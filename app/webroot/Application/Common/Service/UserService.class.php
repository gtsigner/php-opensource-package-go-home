<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/26
 * Time: 14:26
 */

namespace Common\Service;


class UserService
{

    public static function checkExist($userMap)
    {
        return M('user')->where($userMap)->find();
    }

    public static function getUserByMap($map)
    {
        $user = M('user')
            ->join('LEFT JOIN ey_user_profile p ON p.uid=ey_user.id')
            ->where($map)
            ->find();
        return $user;
    }
}