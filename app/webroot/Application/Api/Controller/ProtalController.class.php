<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/26
 * Time: 10:17
 */

namespace Api\Controller;


use Common\Util\TokenUtil;


class ProtalController extends ApiController
{
    //登陆获取token
    public function getToken()
    {
        $password = md5(I('password'));
        $userMap['username'] = I('username');
        $user = M('user')->where($userMap)->find();
        if (!$user) {
            $this->errorMsg('对不起,用户不存在');
        }
        if ($user['password'] != $password) {
            $this->errorMsg('用户名或者密码错误');
        }
        if ($user['status'] != 1) {
            $this->errorMsg('用户状态不可用');
        }
        //登陆成功
        $userProfile = M('user')
            ->join('LEFT JOIN ey_user_profile p ON p.uid=ey_user.id')
            ->where(array('id' => $user['id']))
            ->field('ey_user.id,ey_user.group_type,ey_user.username,ey_user.phone,ey_user.status,ey_user.token,p.*')
            ->find();
        $token = TokenUtil::createToken($user['id']);
        M('user')->where(array('id' => $user['id']))->setField('token', $token);
        $data['token'] = $token;
        $data['user'] = $userProfile;
        $this->apiReturn('success', $data);
    }

    //退出
    public function delToken()
    {
        //$uMap['username'] = I('username');

    }
}