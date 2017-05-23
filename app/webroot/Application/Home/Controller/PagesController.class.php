<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/27
 * Time: 13:29
 */

namespace Home\Controller;


use Think\Controller;

class PagesController extends Controller
{
    //关于我们
    public function about_us()
    {
        $this->display();
    }

    //帮助
    public function help()
    {
        $this->display();
    }

    public function move_help()
    {
        $this->display();
    }

    public function ques_help()
    {
        $this->display();
    }
}