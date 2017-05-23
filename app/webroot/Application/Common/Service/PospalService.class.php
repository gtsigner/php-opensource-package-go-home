<?php
/**
 * Email:zhaojunlike@gmail.com
 * Date: 2017/1/9
 * Time: 9:23
 */

namespace Common\Service;


use Common\Helper\HttpHelper;

class PospalService
{

    protected $config = [
        'appId' => ''
    ];
    protected $api_urls = [
        'user_phone' => 'pospal-api2/openapi/v1/customerOpenapi/queryBytel',
    ];

    private function _sign($appKey, $data)
    {
        return strtoupper(md5($appKey . json_encode($data)));
    }

    public function getUserByPhone($phoneNumber)
    {
        $data['customerTel'] = $phoneNumber;
        $data['appId'] = $this->config['appId'];
        $header = [
            'time-stamp' => time(),
            'data-signature' => $this->_sign($this->config['appKey'], $data)
        ];
        $ret = HttpHelper::http($this->api_urls['user_phone'], json_encode($data), "POST", $header);
        $ret = json_decode($ret, true);
    }
}