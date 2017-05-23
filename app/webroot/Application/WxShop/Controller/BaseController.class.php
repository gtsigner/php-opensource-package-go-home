<?php
/**
 * Created by PhpStorm.
 * User: zhaojunlike
 * Date: 2016/9/16
 * Time: 20:09
 */

namespace WxShop\Controller;

use Common\Helper\OrderHelper;
use Common\WeChat\TpWeChat;
use Think\Controller;
use WxShop\Api\User;

/**
 * 這次活動的基礎控制器
 * Class BaseController
 * @package Play\Controller
 */
class BaseController extends Controller
{
    protected $_wxOpenID;
    protected $_wxObj;
    protected $_wxAccessOauth;

    protected function _initialize()
    {
        $this->_checkWxBrowser();
    }

    /**
     * 检测用户授权
     * www.pospal.cn
     */
    final  private function _checkOauth()
    {
        //检测用户授权
        $accessOauth = session('access_oauth');
        if (!$accessOauth) {
            $goUrl = $this->_wxObj->getOauthRedirect(WeChat_CallBack, 'wxOauth', "snsapi_base");
            redirect($goUrl);
            exit($goUrl);
        }
        $this->_wxOpenID = $accessOauth['openid'];
        $this->_wxAccessOauth = $accessOauth;
        $user = S('user_' . $this->_wxOpenID);
        if (!$user) {
            $user = $this->checkUserExist($this->_wxOpenID);
            S('user_' . $this->_wxOpenID, $user);
        }
        $this->user = $user;
    }

    private function _checkWxBrowser()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        } else {
            $seo['title'] = "error-成都易猿网络";
            $seo['keywords'] = "";
            $this->assign('seo', $seo);
            $this->display("Error/notWxBrowser");
            exit();
        }
    }

    /**
     * Api Data
     * @param $data
     * @param string $msg
     * @param int $code
     */
    final  protected function apiSuccessReturn($data = null, $msg = 'SUCCESS', $code = 1001)
    {
        if ($data) {
            $json['data'] = $data;
        }
        $json['msg'] = $msg;
        $json['code'] = $code;
        $this->ajaxReturn($json);
    }

    /**
     * 接口失败返回
     * @param string $msg
     * @param int $code
     * @param $data
     */
    final  protected function apiFailReturn($data = null, $msg = 'FAIL', $code = 1002)
    {
        if ($data) {
            $json['data'] = $data;
        }
        $json['msg'] = $msg;
        $json['code'] = $code;
        $this->ajaxReturn($json);
    }

    private function checkUserExist($openid)
    {
        $map['openid'] = $openid;
        //查询是否存在用户
        $oauth = User::getWeUser($map);
        if (!$oauth) {
            $accessUserInfoOauth = session('access_info_oauth');
            if (!$accessUserInfoOauth) {
                $goUrl = $this->_wxObj->getOauthRedirect(WeChat_CallBack, 'wxOauth', "snsapi_userinfo");
                redirect($goUrl);
                exit($goUrl);
            }
            $this->_wxAccessOauth = $accessUserInfoOauth;
            $userInfo = $this->_getUserProfile($this->_wxAccessOauth['access_token'], $openid);
            $newWeUser['username'] = "wx_" . OrderHelper::createOrderNo();
            //增加应用层
            $playMember['openid'] = $openid;
            $playMember['head_img_url'] = $userInfo['headimgurl'] . '';
            $playMember['country'] = $userInfo['country'] . '';
            $playMember['province'] = $userInfo['province'] . '';
            $playMember['city'] = $userInfo['city'] . '';
            $playMember['create_time'] = time();
            $playMember['sex'] = $userInfo['sex'] . '';
            $playMember['score'] = 10;//默认赠送积分
            $playMember['nickname'] = $userInfo['nickname'] . '';
            $playMember['status'] = 1;
            User::addWeUser($playMember);
        } else {
            return $oauth;
        }
    }

    protected function _getUserProfile($accessToken, $openid)
    {
        $userInfo = $this->_wxObj->getOauthUserinfo($accessToken, $openid);
        return $userInfo;
    }
}