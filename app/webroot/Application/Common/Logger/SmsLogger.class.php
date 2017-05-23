<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/29
 * Time: 12:06
 */

namespace Common\Logger;


class SmsLogger
{

    //记录发送短信日志
    public static function logSendSms($uid, $sendJson, $retJson, $status = 0)
    {
        $log['uid'] = $uid;
        $log['time'] = time();
        $log['send_json'] = $sendJson;
        $log['ret_json'] = $retJson;
        $log['status'] = $status;
        return M('sms_send_log')->add($log);
    }

    //记录发送短信日志
    public static function logSendWeChat($uid, $package_id, $openid, $sendJson, $retJson, $status = 0)
    {
        $log['uid'] = $uid;
        $log['package_id'] = $package_id;
        $log['time'] = time();
        $log['openid'] = $openid;
        $log['send_json'] = $sendJson;
        $log['ret_json'] = $retJson;
        $log['status'] = $status;
        return M('wechat_send_log')->add($log);
    }
}