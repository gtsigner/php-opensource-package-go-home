<?php
/**
 * Created by PhpStorm.
 * User: zhaojunlike
 * Date: 2016/12/12
 * Time: 12:01
 */

namespace Common\Helper;


use AlibabaAliqinFcSmsNumSendRequest;
use TopClient;

class AliSmsHelper
{
    /**
     * @param $phone
     * @param $code
     * @param $product
     * @return bool|int
     */
    public static function sendSmsVerifyCode($phone, $code, $product)
    {
        $importRet = vendor("AliSDK/TopSdk");
        if (!$importRet) {
            return -4;
        }
        $c = new TopClient();
        $c->appkey = C('ALIDAYU_APP_KEY');
        $c->secretKey = C('ALIDAYU_SECRET_KEY');
        $req = new AlibabaAliqinFcSmsNumSendRequest;
        $req->setExtend("123456");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("外卖通知");
        $req->setSmsParam("{\"code\":\"{$code}\",\"product\":\"{$product}\"}");
        $req->setRecNum($phone);
        $req->setSmsTemplateCode("SMS_16740917");
        $resp = $c->execute($req);
        if ($resp->result->err_code == 0) {
            return true;
        } else {
            return false;
        }
    }
}