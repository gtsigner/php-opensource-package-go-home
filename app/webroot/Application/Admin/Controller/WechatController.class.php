<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2017/1/6
 * Time: 15:22
 */

namespace Admin\Controller;

use Think\Page;

class WechatController extends AdminController
{
    public function send_log($p = 1)
    {
        $map = array();
        $data_list = M('wechat_send_log l')
            ->join('LEFT JOIN ey_user u ON u.id=l.uid')
            ->where($map)
            ->page($p, $this->page_limit)
            ->field('l.*,u.username')
            ->order("id DESC")
            ->select();

        $count = M('wechat_send_log l')
            ->join('LEFT JOIN ey_user u ON u.id=l.uid')
            ->where($map)
            ->count();
        $pModel = new Page($count, $this->page_limit);

        $this->assign('page', $pModel->show());
        $this->assign('data_list', $data_list);
        $this->display();
    }

    public function user_list()
    {

    }

    public function notice_send_logs()
    {

    }

}