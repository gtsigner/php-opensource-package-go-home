<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/28
 * Time: 16:44
 */

namespace Admin\Controller;


class ServiceController extends AdminController
{

    //
    public function api_get_company()
    {
        $map = array();
        $data = M('express_company')->where($map)->order("create_time DESC")->select();
        if (!$data) {
            $data = array();
        }
        $this->restfulReturn(1, 'success', $data);
    }

    //获取包裹类型
    public function api_get_package_type()
    {
        $map = array();
        $data = M('package_type')->where($map)->order("create_time DESC")->select();
        if (!$data) {
            $data = array();
        }
        $this->restfulReturn(1, 'success', $data);
    }

    public function api_get_total()
    {
        $total["home_count"] = M('user')->count();
        $total["sender_count"] = M('sender')->count();
        $total["package_count"] = M('package')->count();
        $total["sms_send_count"] = M('sms_send_log')->count();
        $this->restfulReturn(1, 'success', $total);
    }
}