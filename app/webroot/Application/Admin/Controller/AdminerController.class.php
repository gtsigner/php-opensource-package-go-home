<?php
namespace Admin\Controller;


use Think\Page;

class AdminerController extends AdminController
{
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->assign('_nav_show_id', "#role-nav");
    }

    public function reset_pwd()
    {
        if (IS_POST) {
            $admin = M('admin_user')->where(array('id' => $this->admin_id))->find();
            if (!$admin) {
                $this->error("对不起您的帐号异常", U('Public/login'));
            }
            if (md5($admin['username'] . I('old_pwd')) != $admin['password']) {
                $this->error("旧密码错误");
            }

            $newPwd = trim(I('new_pwd'));
            $newPwdRe = I('new_pwd_re');
            if ($newPwdRe != $newPwd) {
                $this->error("两次密码不一致");
            }
            $newPwd = md5($admin['username'] . $newPwd);
            $upPwdRet = M('admin_user')->where(array('id' => $this->admin_id))->setField('password', $newPwd);
            if ($upPwdRet) {
                $this->success("修改成功");
            } else {
                $this->error("修改失败");
            }
        } else {
            $this->display();
        }
    }

    public function logout()
    {
        session('admin_login_id', null);
        redirect(U('public/login'));
    }

    #region   权限管理


    public function admin_list($p = 1)
    {
        $this->showSingleModelList("admin_user", $p);
        $this->display();
    }

    //获取所有得菜单树
    public function menu_list()
    {

        $this->display();
    }

    public function group_list($p = 1)
    {
        $this->display();
    }


    #endregion  权限管理
}