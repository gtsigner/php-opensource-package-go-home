<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/27
 * Time: 12:33
 */

namespace Api\Controller;


use Common\Service\UserService;
use Common\Util\TokenUtil;

class ApiAuthController extends ApiController
{
    protected $user = array();
    protected $page_limit = 20;

    public function _initialize()
    {
        $this->user = $this->_parseToken();
    }

    private function _parseToken()
    {
        $token = I('token');
        if (!$token) {
            $this->errorMsg('请先登陆', 205);
        }
        $uid = TokenUtil::parseToken($token);
        if (!$uid) {
            $this->errorMsg('令牌失效,请重新登陆.', 205);
        }
        $uMap['id'] = $uid;
        $uMap['status'] = 1;
        $user = UserService::getUserByMap($uMap);
        if (!$user) {
            $this->errorMsg('对不起,用户不存在', 205);
        }
        return $user;
    }
}