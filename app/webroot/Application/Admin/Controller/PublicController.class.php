<?php
namespace Admin\Controller;


use Think\Cache\Driver\Memcache;
use Think\Controller;
use Think\Verify;

/***
 * 开放控制器
 * Class PublicController
 * @package Admin\Controller
 */
class PublicController extends Controller
{
    protected function _initialize()
    {
    }

    /**
     * 登入
     */
    public function login()
    {
        if (IS_POST) {
            $loginVerify = I('verify');
            //如果是debug模式取消验证码验证
            if ($this->_checkVerify($loginVerify) != true && C('CORE_DEBUG') != true) {
                $this->error("验证码错误，请重新输入");
            }
            $map['username'] = I('username');
            $password = I('password');
            $manager = M('admin_user')->where($map)->find();
            if (!$manager) {
                $this->error("管理员帐号或密码错误");
            }
            if ($manager['password'] != md5($manager['username'] . $password)) {
                $this->error("管理员帐号或密码错误");
            }
            if ($manager['status'] != 1) {
                $this->error("管理员帐号状态不可用,请联系超级管理员");
            }
            session('admin_login_id', $manager['id']);
            $this->success("登录成功", U('Index/index'));
        } else {
            $this->display();
        }
    }

    /**
     *登出
     */
    public function logout()
    {
        session('admin_login_id', null);
        redirect(U('Public/login'));
    }


    /**
     * 验证码
     */
    public function verify()
    {

        $verify = new Verify();
        ob_clean();
        $verify->entry(2);
    }

    /**
     * 校验验证
     * @return bool
     */
    private function _checkVerify($verify)
    {
        $verifyObj = new Verify();
        return $verifyObj->check($verify, 2);
    }

}