<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/27
 * Time: 13:05
 */

namespace Api\Controller;

//不用登陆
use Common\Helper\PackageHelper;

class AppController extends ApiController
{
    public function search_package()
    {
        PackageHelper::searchPackage(I('nu'));
    }

    //获取更新
    public function get_update()
    {
        $map['v.os_type'] = I('os_type');//0:android
        //获取最新版本号的APP
        $version = M('app_version v')
            ->join('LEFT JOIN ey_system_upload u ON u.id=v.upload_id')
            ->where($map)
            ->order("v.id DESC")
            ->field('v.*,u.path as file_path')
            ->find();
        $this->apiReturn('success', $version);
    }

    //banner
    public function get_banner()
    {
        $map = array();
        $map['status'] = 1;
        //获取最新版本号的APP
        $data = M('app_banner')
            ->join('LEFT JOIN ey_system_upload u ON u.id=ey_app_banner.upload_id')
            ->field('ey_app_banner.*,u.path as file_path')
            ->where($map)->order("sort DESC")
            ->select();
        $this->apiReturn('success', $data);
    }

    //系统通知
    public function get_system_notice()
    {
        $map = array();
        $data = M('system_notice')
            ->where($map)
            ->order("create_time DESC")
            ->select();
        $this->apiReturn('获取系统通知成功', $data);

    }

}