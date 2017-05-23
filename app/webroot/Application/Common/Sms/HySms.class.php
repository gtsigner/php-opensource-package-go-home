<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/27
 * Time: 20:28
 */

namespace Common\Sms;

use Common\Helper\HttpHelper;
use Common\Helper\XmlHelper;
use Common\Logger\SmsLogger;

/**
 *
 *  互译短信
 * Author: zhaojunlike@
 * Gtihub: https://github.com/zhaojunlike
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Class HySms
 * @package Common\Sms
 */
class HySms
{

    //submit
    protected $sub_url = "http://106.ihuyi.com/webservice/sms.php?method=Submit";
    //余额查询接口说明
    protected $wallet_url = "http://106.ihuyi.com/webservice/sms.php?method=GetNum";


    protected $config = array(
        'account' => 'cf_30dj',
        'appkey' => 'ec131cd100f7e31772b7eaaaf832b390',
    );

    public function __construct($config = array())
    {
        $this->config = array_merge($this->config, $config);
    }


    //发送验证码
    public function sendSms($tplContent, $phone)
    {
        $sms = array();
        $sms['mobile'] = $phone;
        $sms['content'] = $tplContent;
        $sms['time'] = time();
        $sms['account'] = $this->config['account'];
        //$sms['password'] = md5($this->config['account'] . $this->config['appkey'] . $phone . $tplContent . $sms['time']);
        $sms['password'] = $this->config['appkey'];
        $xml = HttpHelper::http($this->sub_url, $sms, "POST");
        $ret = XmlHelper::xml_to_array($xml);
        if ($ret['SubmitResult']['code'] === 2) {
            //提交成功
            //TODO   log echo "success";
            return $ret;
        } else {
            //提交失败
            //TODO  log echo "error";
            return $ret;
        }
    }


    //获取钱包余额
    public function getWallet()
    {
        $data = array();
        $data['account'] = $this->config['account'];
        $data['password'] = $this->config['appkey'];
        $xml = HttpHelper::http($this->wallet_url, $data, "POST");
        $ret = XmlHelper::xml_to_array($xml);
        return $ret;
    }

    //直接发送一个封装好了得sms对象
    public function sendSmsBean($sms)
    {
        //这里去记录发送数据
        $ret = $this->sendSms($sms['content'], $sms['phone']);
        if ($ret['SubmitResult']['code'] == 2) {
            SmsLogger::logSendSms($sms['uid'], json_encode($sms), json_encode($ret), 1);
            return true;
        } else {
            SmsLogger::logSendSms($sms['uid'], json_encode($sms), json_encode($ret), 0);
            return false;
        }
    }

    public function sendBindSms($phone, $code)
    {
        $tplContent = "您的验证码是：{$code}。请不要把验证码泄露给其他人。如非本人操作，可不用理会！";
        $send_json = [];
        $send_json['content'] = $tplContent;
        $send_json['phone'] = $phone;
        $ret = $this->sendSms($tplContent, $phone);
        if ($ret['SubmitResult']['code'] == 2) {
            SmsLogger::logSendSms(0, json_encode($send_json), json_encode($ret), 1);
        } else {
            SmsLogger::logSendSms(0, json_encode($send_json), json_encode($ret), 0);
        }
        return $ret;
    }
}