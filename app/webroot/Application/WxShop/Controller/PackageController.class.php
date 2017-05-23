<?php
/**
 * Email:zhaojunlike@gmail.com
 * Date: 2017/1/9
 * Time: 12:19
 */

namespace WxShop\Controller;


use Think\Controller;

class PackageController extends BaseController
{

    public function show()
    {

        $openid = I('openid');
        $package_id = I('id');
        $wechatUser = M('wechat_user')->where(array('openid' => $openid))->find();
        $package = $package = M('package p')
            ->join('LEFT JOIN ey_express_company c ON c.id=p.company_id')
            ->join('LEFT JOIN ey_user_profile f ON f.uid=p.uid')
            ->join('LEFT JOIN ey_user u ON u.id=p.uid')
            ->where(array('p.id' => $package_id))
            ->field('p.*,c.name as company_name,f.address_desc,f.open_date,sale_mark,u.phone as address_phone')
            ->find();
        if ($package['get_phone'] != $wechatUser['phone']) {

        } else {
            $this->assign('data', $package);
        }
        $this->display();
    }
}