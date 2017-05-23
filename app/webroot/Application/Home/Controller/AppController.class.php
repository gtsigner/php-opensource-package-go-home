<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2017/1/6
 * Time: 13:48
 */

namespace Home\Controller;


class AppController extends HomeController
{
    //通知页面
    public function notice($id)
    {
        $data = M('user_notice')->where(array('id' => $id))->find();
        $this->assign("data", $data);
        $this->display();
    }


    public function get_android_app()
    {
        $map['v.os_type'] = 0;//0:android
        //获取最新版本号的APP
        $version = M('app_version v')
            ->join('LEFT JOIN ey_system_upload u ON u.id=v.upload_id')
            ->where($map)
            ->order("v.id DESC")
            ->field('v.*,u.path as file_path')
            ->find();
        $filename = $version['file_path'];
        var_dump(__ROOT__ . $filename);
        if (!file_exists($filename)) {
            $this->error("文件不存在");
        }
        // http headers
        header('Content-Type: application-x/force-download');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        ob_end_clean();
        return readfile($filename);
    }
}