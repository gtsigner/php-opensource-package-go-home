<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/27
 * Time: 12:50
 */

namespace Api\Controller;


class UserController extends ApiAuthController
{

    //获取账户信息
    public function get_wallet()
    {
        $map['uid'] = $this->user['id'];
        $wallet = M('user_wallet')->where($map)->find();
        $this->apiReturn('success', $wallet);
    }

    //获取
    public function get_profile()
    {
        $map['uid'] = $this->user['id'];
        $data = M('user_profile')->where($map)->find();
        $this->apiReturn('success', $data);
    }

    //获取通知消息
    public function get_notice($p = 1)
    {
        $uids = [];
        $uids[] = 0;//系统通知
        $uids[] = $this->user['id'];
        $map['uid'] = array('in', $uids);
        $data['list'] = M('user_notice')
            ->where($map)
            ->field('id,uid,title,desc,status,create_time')
            ->order("id DESC")
            ->select();
        $data['count'] = M('user_notice')
            ->where($map)
            ->count();
        $data['new_count'] = M('user_notice')
            ->where($map)
            ->where(array('status' => 0))
            ->count();
        if (!$data['list']) {
            $data['list'] = [];
        }
        $this->apiReturn('success', $data);
    }

    //设置
    public function set_profile()
    {
        $map['uid'] = $this->user['id'];
        $data = M('user_profile')->where($map)->find();
        $data['sms_notice_status'] = I('sms_notice_status');
        $data['wx_notice_status'] = I('wx_notice_status');
        $data['open_date'] = I('open_date');
        M('user_profile')->where($map)->save($data);
        $this->apiReturn('修改资料成功', $data);
    }
}