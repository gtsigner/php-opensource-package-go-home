<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/28
 * Time: 19:46
 */

namespace Admin\Controller;


use Think\Page;

class SmsController extends AdminController
{
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->assign("_nav_show_id", "#sms-nav");
    }

    public function tpl_list($p = 1)
    {
        $map = array();
        $status = I('status');
        if ($status != '') {
            $map['t.status'] = $status;
        }
        $data_list = M('user_sms_tpl t')
            ->join('LEFT JOIN ey_user u ON u.id=t.uid')
            ->where($map)
            ->page($p, $this->page_limit)
            ->field('t.*,u.username')
            ->order("status ASC,id DESC")
            ->select();

        $count = M('user_sms_tpl t')
            ->join('LEFT JOIN ey_user u ON u.id=t.uid')
            ->where($map)
            ->count();

        $pModel = new Page($count, $this->page_limit);

        $this->assign('page', $pModel->show());
        $this->assign('tpl_list', $data_list);
        $this->display();
    }

    public function send_log($p = 1)
    {
        $map = array();
        $data_list = M('sms_send_log l')
            ->join('LEFT JOIN ey_user u ON u.id=l.uid')
            ->where($map)
            ->page($p, $this->page_limit)
            ->field('l.*,u.username')
            ->order("id DESC")
            ->select();

        $count = M('sms_send_log l')
            ->join('LEFT JOIN ey_user u ON u.id=l.uid')
            ->where($map)
            ->count();
        $pModel = new Page($count, $this->page_limit);

        $this->assign('page', $pModel->show());
        $this->assign('data_list', $data_list);
        $this->display();
    }

    public function del_tpl()
    {
        $ids = I('ids');
        $mIds = array();
        if (!is_array($ids)) {
            $mIds[] = $ids;
        } else {
            $mIds = array_values($ids);
        }
        $map['id'] = array('in', $mIds);
        $upRet = M('user_sms_tpl')->where($map)->delete();
        if ($upRet) {
            $this->success("删除成功");
        } else {
            $this->error("删除失败");
        }
    }

    public function no_accept()
    {

    }

    public function status_tpl()
    {
        $ids = I('ids');
        $status = intval(I('status'));
        $userIds = array();
        if (!is_array($ids)) {
            $userIds[] = $ids;
        } else {
            $userIds = array_values($ids);
        }
        $userMap['id'] = array('in', $userIds);
        $upRet = M('user_sms_tpl')->where($userMap)->setField(array('status' => $status));
        if ($upRet) {
            $this->success("修改成功");
        } else {
            $this->error("修改失败");
        }
    }
}