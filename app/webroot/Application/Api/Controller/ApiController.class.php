<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/26
 * Time: 9:30
 */

namespace Api\Controller;


use Think\Controller;

class ApiController extends Controller
{

    public function apiReturn($msg, $data, $code = 200)
    {
        $json['msg'] = $msg;
        $json['data'] = $data;
        $json['code'] = $code;
        $this->ajaxReturn($json);
    }

    public function errorMsg($msg, $code = 204)
    {
        $json['msg'] = $msg;
        $json['code'] = $code;
        $this->ajaxReturn($json);
    }

    public function successMsg($msg, $code = 200)
    {
        $json['msg'] = $msg;
        $json['code'] = $code;
        $this->ajaxReturn($json);
    }

}