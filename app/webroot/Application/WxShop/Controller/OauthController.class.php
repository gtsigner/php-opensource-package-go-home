<?php
/**
 * Created by PhpStorm.
 * Author：软件开发,网站建设,网站优化/
 * Date: 2016/9/9
 * Time: 19:02
 */

namespace WxShop\Controller;


use Common\WeChat\TpWeChat;
use Think\Controller;
use Think\Log;

class OauthController extends Controller
{
    protected $_wxObj;
    protected $_wxOptions;

    protected function _initialize()
    {
        $this->_wxOptions = C('WECAHT_OPTIONS');
        $this->_wxObj = new TpWeChat($this->_wxOptions);
        $this->_wxObj->debug = true;
    }

    //处理消息
    public function oauth_callback()
    {
        $state = I('state');
        switch ($state) {
            case "wxOauth":
                //处理回调授权
                $this->_wxOauth();
                break;
            default:

                break;
        }
        exit("Permission Denied");
    }

    private function _wxOauth()
    {
        $accessObj = $this->_wxObj->getOauthAccessToken();
        if ($accessObj['scope'] == 'snsapi_userinfo') {
            session('access_info_oauth', $accessObj, 300);
            redirect(U('index/index'));
        } else {
            session('access_oauth', $accessObj, 300);
            redirect(U('index/index'));
        }
    }

    public function bind()
    {

    }


}

