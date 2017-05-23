<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/31
 * Time: 10:31
 */

namespace Common\Helper;


class PackageHelper
{
    private static $nu_url = "http://ali-deliver.showapi.com/showapi_expInfo";

    private static $config = [
        'appcode' => 'ab34a9c15eec41daa78c4d693644419f',
    ];

    /**
     * 查询快递包裹
     * Author: zhaojunlike@
     * Gtihub: https://github.com/zhaojunlike
     * Mark: 网站开发，软件开发，远程运维
     * Email:zhaojunlike@gmail.com
     * @param $com
     * @param $nu
     */
    public static function searchPackage($nu, $com = 'auto')
    {
        $headers = array();
        $data['com'] = $com;
        $data['nu'] = $nu;
        $header = [
            'Authorization' => "APPCODE" . self::$config['appcode']
        ];
        $ret = HttpHelper::http(self::$nu_url, $data, "GET", $header);
        var_dump($ret);
    }
}