<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/28
 * Time: 10:32
 */

function get_company_name($id)
{
    //TODO  增加缓存
    return M('express_company')->where(array('id' => $id))->getField('name');
}

function get_package_type_name($id)
{
    return M('package_type')->where(array('id' => $id))->getField('name');
}

function get_user_default_sms_tpl($uid)
{
    //TODO 缓存
    return M('user_sms_tpl')->where(array('uid' => $uid, 'is_default' => 1))->find();

}

//助手函数：右推送一个sms到短信发送队列
function sms_queue_rpush($sms)
{
    if (\Common\Helper\RedisHelper::$IsConnect == false) {
        \Common\Helper\RedisHelper::Init();
    }
    \Common\Helper\RedisHelper::$handle->rPush("user_package_sms", json_encode($sms));
}

//助手函数：左推出一个短信发送
function sms_queue_lpop()
{
    if (\Common\Helper\RedisHelper::$IsConnect == false) {
        \Common\Helper\RedisHelper::Init();
    }
    $ret = \Common\Helper\RedisHelper::$handle->lPop("user_package_sms");
    if (!$ret) {
        return false;
    } else {
        return json_decode($ret, true);
    }
}

function get_notice_status_name($status)
{
    //0:未发送,1发送成功,2队列中,3发送失败,4余额不足,5异常
    switch (intval($status)) {
        case 0:
            return "<code style='color: #3242ff;'>不发送</code>";
            break;
        case 1:
            return "<code class='am-text-success'>成功</code>";
            break;
        case 2:
            return "<code class='am-text-warning'>队列中</code>";
            break;
        case 3:
            return "<code class='am-text-danger'>失败</code>";
            break;
        case 4:
            return "<code>余额不足</code>";
        case 5:
            return "<code>发送异常</code>";
            break;
        case 7;
            return "<code>未设置模版</code>";
            break;
        case 6:
            return "<code class='am-text-danger'>用户未绑定微信</code>";
            break;
        case 8:
            return "<code class='am-text-warning'>已发送微信</code>";
        default:
            break;
    }
    return "<code>发送异常</code>";
}
