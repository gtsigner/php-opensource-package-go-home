<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/26
 * Time: 13:52
 */

namespace Admin\Controller;


use Common\Helper\OrderHelper;
use Common\Service\UserService;
use Think\Page;

class UserController extends AdminController
{
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->assign('_nav_show_id', "#user-nav");
    }

    //首页
    public function index($p = 1)
    {
        if (IS_POST) {

        }
        $userMap['del_status'] = 0;
        $userList = M('user')
            ->join('LEFT JOIN ey_user_profile p ON p.uid=ey_user.id')
            ->where($userMap)
            ->page($p, $this->page_limit)
            ->order("create_time DESC")
            ->select();
        $count = M('user')
            ->join('LEFT JOIN ey_user_profile p ON p.uid=ey_user.id')
            ->where($userMap)->count();

        $pModel = new Page($count, $this->page_limit);

        $this->assign('page', $pModel->show());
        $this->assign('user_list', $userList);
        $this->display();
    }

    //切换用户状态
    public function status_user()
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
        $upRet = M('user')->where($userMap)->setField(array('status' => $status));
        if ($upRet) {
            $this->success("修改成功");
        } else {
            $this->error("修改失败");
        }
    }

    //重置密码
    public function reset_pwd($uid, $pwd)
    {
        if (IS_POST) {
            $user = M('user')->where(array('id' => $uid))->find();
            if (!$user) {
                $this->error("对不起您的帐号异常");
            }
            $newPwd = md5($pwd);
            $upPwdRet = M('user')->where(array('id' => $uid))->setField('password', $newPwd);
            if ($upPwdRet) {
                $this->success("修改成功");
            } else {
                $this->error("修改失败");
            }
        }
    }

    //查看包裹
    public function package($p = 1)
    {
        if (IS_POST) {

        }
        $userMap['del_status'] = 0;
        $userList = M('user')
            ->join('LEFT JOIN ey_user_profile p ON p.uid=ey_user.id')
            ->page($p, $this->page_limit)
            ->where($userMap)
            ->select();

        $this->assign('user_list', $userList);
        $this->display();
    }

    public function add_user()
    {
        if (IS_POST) {
            $user['username'] = I('username');
            $user['password'] = md5(I('password'));
            $user['phone'] = I('phone');
            $user['status'] = I('status');
            $user['group_type'] = I('group_type');
            $user['create_time'] = time();
            //check
            if (UserService::checkExist(array('username' => I('username')))) {
                $this->error('用户名已经存在');
            }
            if (UserService::checkExist(array('phone' => I('phone')))) {
                $this->error('手机号已经存在');
            }
            $addRet = M('user')->add($user);
            //
            if (!$addRet) {
                $this->error('添加失败');
            }
            //添加个人资料
            $userProfile['uid'] = $addRet;
            $addProRet = M('user_profile')->add($userProfile);
            //添加钱包
            $userWallet['uid'] = $addRet;
            $userWallet['sms_total_count'] = 0;
            $userWallet['sms_count'] = 0;
            $userWallet['money'] = 0;
            $addWalletRet = M('user_wallet')->add($userWallet);
            $this->success('添加成功', U('user/index'));
        } else {
            $this->display();
        }
    }

    public function edit_user($id)
    {
        $userMap['id'] = $id;
        $user = M('user')
            ->join('LEFT JOIN ey_user_profile p ON p.uid=ey_user.id')
            ->where($userMap)
            ->find();
        if (!$user) {
            $this->error("对不起，用户不存在");
        }
        if (IS_POST) {
            //开始修改资料
            $user['phone'] = I('phone');
            $user['status'] = I('status');
            $saveRet = M('user')->save($user);
            if (!$saveRet) {
                //$this->error('修改失败');
            }
            $userProfile['sale_mark'] = I('sale_mark');
            $userProfile['open_date'] = I('open_date');
            $userProfile['name'] = I('name');
            $userProfile['address_desc'] = I('address_desc');
            $userProfile['accept_mark'] = I('accept_mark');
            $userProfile['card_name'] = I('card_name');
            $userProfile['card_id'] = I('card_id');
            $userProfile['sms_notice_status'] = I('sms_notice_status');
            $userProfile['wx_notice_status'] = I('wx_notice_status');
            $userProfile['uid'] = $user['id'];
            M('user_profile')->save($userProfile);
            $this->success('修改资料成功');
        } else {
            $this->assign('user', $user);
            $this->display();
        }
    }

    public function del_user($type = 1)
    {
        //删除用户
        $ids = I('ids');
        $userIds = array();
        if (!is_array($ids)) {
            $userIds[] = $ids;
        } else {
            $userIds = array_values($ids);
        }
        $userMap['id'] = array('in', $userIds);

        //软删除

        $upRet = false;
        switch ($type) {
            case 1:
                $upRet = M('user')->where($userMap)->setField(array('del_status' => 1));
                break;
            case 2:
                $upRet = M('user')->where($userMap)->delete();
                $upProRet = M('user_profile')->where($userMap)->delete();
                break;
        }

        if ($upRet) {
            $this->success("删除成功");
        } else {
            $this->error("删除失败");
        }
    }

    //获取短信使用情况
    public function sms_list($p = 1)
    {
        $map = array();
        $count = M('user_sms_pool p')
            ->join('ey_user u ON u.id=p.uid')
            ->where($map)
            ->count();

        $pool_list = M('user_sms_pool p')
            ->join('ey_user u ON u.id=p.uid')
            ->join('ey_user_profile f ON f.uid=p.uid')
            ->field('u.username,f.name,f.address_desc,p.*')
            ->where($map)
            ->page($p, $this->page_limit)
            ->order("id DESC")
            ->select();
        $pModel = new Page($count, $this->page_limit);

        $this->assign('sms_list', $pool_list);
        $this->assign('page', $pModel->show());
        $this->display();
    }

    //充值短信
    public function payin_sms()
    {
        $map['id'] = I('uid');
        $user = M('user')->where($map)->find();
        if (!$user) {
            $this->error('用户不存在');
        }
        if (IS_POST) {
            $sms['uid'] = $user['id'];
            $sms['total_count'] = intval(I('total_count'));
            $sms['pay_type'] = 0;
            $sms['order_no'] = OrderHelper::createOrderNo();
            $sms['create_time'] = time();
            $sms['last_count'] = intval(I('last_count'));
            $addRet = M('user_sms_pool')->add($sms);
            if (!$addRet) {
                $this->error('充值失败');
            } else {
                $this->success('充值成功');
            }
        } else {
            $this->assign('user', $user);
            $this->display();
        }

    }

    #region 通知 Actions
    //通知列表
    public function notice_list($p = 1)
    {
        $map = array();
        $dataList = M('user_notice n')
            ->join('LEFT JOIN ey_user u ON u.id=n.uid')
            ->where($map)
            ->page($p, $this->page_limit)
            ->order("n.create_time DESC")
            ->field("n.*,u.username")
            ->select();
        $count = M('user_notice n')
            ->where($map)
            ->count();

        $pModel = new Page($count, $this->page_limit);
        $this->assign('page', $pModel->show());
        $this->assign('data_list', $dataList);
        $this->display();
    }

    //发送通知
    public function send_notice()
    {
        if (IS_POST) {
            $data = M('user_notice')->create();
            if (!$data) {
                $this->error("创建数据失败");
            }
            $data['create_time'] = time();
            $data['title'] = I('title');
            $data['message'] = $_POST['message'];
            $ret = M('user_notice')->add($data);
            if (!$ret) {
                $this->error("创建失败");
            } else {
                $this->success("已经派送通知");
            }
        } else {
            $this->display();
        }
    }

    //remove
    public function del_notice()
    {
        //删除用户
        $ids = I('ids');
        $Ids = array();
        if (!is_array($ids)) {
            $Ids[] = $ids;
        } else {
            $Ids = array_values($ids);
        }
        $map['id'] = array('in', $Ids);
        $upRet = M('user_notice')->where($map)->delete();
        if ($upRet) {
            $this->success("删除成功");
        } else {
            $this->error("删除失败");
        }
    }
    #endregion
}