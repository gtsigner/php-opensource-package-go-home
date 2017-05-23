<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/27
 * Time: 12:45
 */

namespace Api\Controller;


class ExpressController extends ApiAuthController
{

    //获取物流公司
    public function get_company_list()
    {
        $map = array();
        $map['del_status'] = 0;
        $company = M('express_company')->where($map)->select();
        $this->apiReturn('success', $company);
    }
}