<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Page;

class AdminController extends Controller
{
    protected $admin_id;
    protected $static = array();
    protected $page_limit = 20;


    protected function _initialize()
    {
        $this->checkLogin();
        $this->initStatic();
    }

    private function initStatic()
    {

        //$this->page_limit = intval(C('SYSTEM_ADMIN_PAGE_LIMIT')) <= 0 ? 10 : intval(C('SYSTEM_ADMIN_PAGE_LIMIT'));
    }


    private function checkLogin()
    {
        $admin_id = session('admin_login_id');
        if (!$admin_id) {
//            redirect(U('Public/login'));
            $this->error("请先登陆系统", U('public/login'));
            exit(0);
        }
        $this->admin_id = $admin_id = 1;
    }

    public function restfulReturn($status, $msg, $data)
    {
        $json['status'] = $status;
        $json['msg'] = $msg;
        $json['data'] = $data;
        $this->ajaxReturn($json);
    }

    final protected function showSingleModelList($modelName, $p)
    {
        $map = array();
        $count = M($modelName)
            ->where($map)
            ->count();
        $pModel = new Page($count, $this->page_limit);
        $list = M($modelName)
            ->where($map)
            ->page($p, $this->page_limit)
            ->order("id DESC")
            ->select();
        $this->assign('data_list', $list);
        $this->assign('page', $pModel->show());
    }
}