<?php
/**
 * Email:zhaojunlike@gmail.com
 * Date: 2017/1/9
 * Time: 10:46
 */

namespace Common\Service;


use Common\Logger\SmsLogger;
use Common\WeChat\TpWeChat;
use Think\Log;

class WeChatUserService
{

    public static function checkExist($map)
    {
        return M('wechat_user')->where($map)->find();
    }


    //通知用户包裹到了
    public static function noticeUserPackage($package)
    {
        $weObj = new TpWeChat(C('WECAHT_OPTIONS'));
        $weUser = M('wechat_user')->where(array('phone' => $package['get_phone'], 'status' => 1))->find();
        if (!$weUser) {
            M('package')->where(array('id' => $package['id']))->setField('notice_wx_status', 6);
            return false;
        }
        //获取微信门店

        $userHome = M('user u')
            ->join('LEFT JOIN ey_user_profile p ON p.uid=u.id')
            ->where(array('id' => $package['uid']))
            ->find();

        //发送微信通知
        //"wx.php/package/show/openid/{$weUser['openid']}/id/{$package['id']}",
        $tplData = array(
            'touser' => $weUser['openid'],
            "template_id" => "tmacu2v-bXmr-4iPY1zJwnX0A2liqQJ_kx0yNcGozOc",
            "url" => WEB_HOST_PATH . "wx.php/package/show/openid/{$weUser['openid']}/id/{$package['id']}",
            "data" => array(
                'first' => array(
                    'value' => "您的包裹已到{$userHome['address_desc']}，请凭取货码速来领取，感谢您得合作，祝您生活愉快。",
                    'color' => '#000'
                ),
                'keyword1' => array(
                    'value' => $package['get_no'],
                    'color' => '#000'
                ),
                'keyword2' => array(
                    'value' => $package['yun_no'],
                    'color' => '#000'
                ),
                'keyword3' => array(
                    'value' => $package['company_name'],
                    'color' => '#dd322d'
                ),
                'keyword4' => array(
                    'value' => $userHome['phone'],
                    'color' => '#000'
                ),
                'remark' => array(
                    'value' => "取件时间:  {$userHome['open_date']}",
                    'color' => '#000'
                ),
            ),
        );
        $ret = $weObj->sendTemplateMessage($tplData);
        if ($ret) {
            SmsLogger::logSendWeChat($package['uid'], $package['id'], $weUser['openid'], json_encode($tplData['data']['first']), json_encode($ret), 1);
            M('package')->where(array('id' => $package['id']))->setField('notice_wx_status', 1);
            return true;
        } else {
            //通知失败
            $retLog['errmsg'] = $weObj->errMsg;
            SmsLogger::logSendWeChat($package['uid'], $package['id'], $weUser['openid'], json_encode($tplData['data']['first']), json_encode($retLog), 0);
        }
        //如果发送失败,重新获取access_token
        $weObj->resetAuth();
        $ret = $weObj->sendTemplateMessage($tplData);
        if ($ret) {
            SmsLogger::logSendWeChat($package['uid'], $package['id'], $weUser['openid'], json_encode($tplData['data']['first']), json_encode($ret), 1);
            M('package')->where(array('id' => $package['id']))->setField('notice_wx_status', 1);
            return true;
        } else {
            //如果2次通知失败
            $retLog['errmsg'] = $weObj->errMsg;
            SmsLogger::logSendWeChat($package['uid'], $package['id'], $weUser['openid'], json_encode($tplData['data']['first']), json_encode($retLog), 0);
            M('package')->where(array('id' => $package['id']))->setField('notice_wx_status', 5);
            return false;
        }

    }

    private function _trySend()
    {

    }
}