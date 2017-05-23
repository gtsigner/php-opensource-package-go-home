<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2017/1/7
 * Time: 10:35
 */

namespace WxShop\Controller;


use Common\Helper\CheckRHelper;
use Common\Service\WeChatUserService;
use Common\Sms\HySms;
use Common\WeChat\TpWeChat;
use Think\Controller;
use Think\Log;

class PublicController extends Controller
{

    protected $_wxObj;

    protected function _initialize()
    {
        $this->_wxObj = new TpWeChat(C('WECAHT_OPTIONS'));
        $this->_wxObj->debug = true;
    }

    public function get_bind_code()
    {
        if (IS_POST) {
            $bind = I('bind');
            if (!CheckRHelper::isPhone($bind['phone'])) {
                $this->error("请输入正确的手机号码");
            }
            if ($bind['openid'] == '') {
                $this->error("对不起,非法操作");
            }
            /*查看是否已经bind*/
            $user = WeChatUserService::checkExist(array('phone' => $bind['phone']));
            if ($user) {
                $this->error("对不起,您已经绑定了其他微信号了,不可多次绑定");
            } else {

            }
            $last = S('bind_code_' . $bind['phone']);
            if ((time() - $last) <= 120) {
                $this->error("120秒內不可重复发送");
            }
            $rand = mt_rand(111111, 999999);

            S('bind_code_v_' . $bind['phone'], $rand, 121);
            S('bind_code_' . $bind['phone'], time(), 121);

            $sms = new HySms();
            $smsRet = $sms->sendBindSms($bind['phone'], $rand);
            $this->success($smsRet['SubmitResult']['msg']);
        }
    }

    //bind
    public function bind()
    {
        if (IS_POST) {
            $bind = I('bind');
            $codeV = S('bind_code_v_' . $bind['phone']);
            if (!$codeV) {
                $this->error("验证码已经失效,请重新获取");
            }
            if ($bind['vcode'] != $codeV) {
                $this->error("对不起,您的验证码错误");
            }
            if ($bind['openid'] == '') {
                $this->error("对不起,非法操作");
            }
            //清理缓存
            S('bind_code_v_' . $bind['phone'], null);
            S('bind_code_' . $bind['phone'], null);


            /*查看是否已经bind*/
            $user = WeChatUserService::checkExist(array('openid' => $bind['openid']));
            if (!$user) {
                //添加一个用户
                $singleUser['openid'] = $bind['openid'];
                $singleUser['create_time'] = time();
                $singleUser['phone'] = $bind['phone'];
                $singleUser['status'] = 1;
                M('wechat_user')->add($singleUser);
                $this->success("绑定成功,您将可以收到快递短信通知", U('public/bind_success?openid=' . $bind['openid']));
            } else {
                //进行换绑
                $user['openid'] = $bind['openid'];
                $user['phone'] = $bind['phone'];
                $user['status'] = 1;
                M('wechat_user')->save($user);
                $this->success("绑定成功,您将可以收到快递短信通知", U('public/bind_success?openid=' . $bind['openid']));
            }
        } else {
            $this->display();
        }
    }

    public function bind_success($openid)
    {
        $user = WeChatUserService::checkExist(array('openid' => $openid));
        $this->assign('user', $user);
        $this->display();
    }

    //处理消息
    public function wx_handler()
    {
        $this->_wxObj = new TpWeChat(C('WECAHT_OPTIONS'));
        $this->_wxObj->valid();
        $type = $this->_wxObj->getRev()->getRevType();
        switch ($type) {
            case TpWeChat::MSGTYPE_TEXT:
                $this->_wxObj->text("您好,欢迎您关注" . C('WEB_SITE_TITLE') . ",公众号建设中,请耐心等待开发者维护!")->reply();
                exit;
                break;
            case TpWeChat::MSGTYPE_EVENT:
                //如果是事件的话
                $eventType = $this->_wxObj->getRevEvent();
                Log::write("Event:" . date("Y-m-d H:i:s") . $eventType['event'] . "     From:" . $this->_wxObj->getRevFrom() . "");
                switch ($eventType['event']) {
                    case "subscribe":
                        //用户订阅
                        $openId = $this->_wxObj->getRevFrom();
                        $this->_subscribe($openId, time());
                        /*进行回复*/
                        break;
                    case "unsubscribe":
                        //用户取消订阅
                        /*进行用户行为*/
                        $openId = $this->_wxObj->getRevFrom();
                        $this->_unSubscribe($openId);
                        break;
                    case "CLICK":
                        switch ($eventType['key']) {
                            case "BIND_PHONE_CLICK":
                                //绑定手机
                                $user = $this->_getWeChatUser($this->_wxObj->getRevFrom());
                                if ($user) {
                                    //session
                                    //TODO
                                    $this->_wxObj->text("<a href='http://30dj.oeynet.com/wx.php/public/bind_success/openid/{$this->_wxObj->getRevFrom()}'>快递到家</a>")->reply();
                                    exit();
                                } else {
                                    //TODO
                                    $this->_wxObj->text("<a href='http://30dj.oeynet.com/wx.php/public/bind/openid/{$this->_wxObj->getRevFrom()}'>绑定手机号,开启接受快递通知</a>")->reply();
                                    exit();
                                }
                                break;
                            default:
                                $this->_wxObj->text("招商加盟请联系客服:15760079693")->reply();
                                break;
                        }
                        break;
                    default:
                        //$this->_wxObj->text("招商加盟请联系客服:023-66030008")->reply();
                        break;
                }
                break;
            case TpWeChat::MSGTYPE_IMAGE:
                break;
            default:
                //$this->_wxObj->text("help info")->reply();
                break;
        }
    }

    private function _unSubscribe($openId)
    {
        file_put_contents("./Public/logs/sub.log", $openId . "\n", FILE_APPEND);
    }

    private function _subscribe($openId, $time)
    {
        file_put_contents("./Public/logs/sub.log", $openId . "\n", FILE_APPEND);
    }

    private function _getWeChatUser($openId)
    {
        return M('wechat_user')->where(array('openid' => $openId))->find();
    }
}