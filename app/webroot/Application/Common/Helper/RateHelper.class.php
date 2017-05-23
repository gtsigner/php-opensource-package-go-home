<?php
/**
 * Created by PhpStorm.
 * User: QQ: 209900956
 * oschina:http://git.oschina.net/zhaojunlike
 * github: http://github.com/zhaojunlike
 * Author：软件开发，网站建设/
 * Date: 2016/8/7
 * Time: 8:25
 */

namespace Common\Helper;

/**
 * 利息helper
 * Class RateHelper
 * @package Common\Helper
 */
class RateHelper
{
    public static function getRate($from, $to, $provider = 'hexun')
    {
        switch ($provider) {
            case 'yahoo':
                $url = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s=' . strtoupper($from) . strtoupper($to) . '=X';
                $handle = @fopen($url, 'r');
                if ($handle) {
                    $result = fgets($handle, 4096);
                    fclose($handle);
                }
                $allData = explode(',', $result); /* Get all the contents to an array */
                $value = $allData[1];
                break;
            case 'google':
                $url = 'http://www.google.com/ig/calculator?hl=en&q=1USD%3D%3FCNY';
                $handle = @fopen($url, 'r');
                if ($handle) {
                    $result = fgets($handle, 4096);
                    fclose($handle);
                }
                $tmp = substr($result, strpos($result, 'rhs: "') + 6);
                $tmp = substr($tmp, 0, strpos($tmp, ' '));
                $value = floatval($tmp);
                break;
            case 'hexun':
                $str = file_get_contents('http://data.bank.hexun.com/other/cms/foreignexchangejson.ashx?callback=ShowDatalist');
                $str = substr($str, strpos($str, "{bank:'" . iconv('UTF-8', 'GB2312', '中国银行') . "',currency:'" . iconv('UTF-8', 'GB2312', '美元') . "',code:'USD',currencyUnit:'',"));
                $str = substr($str, 0, strpos($str, '}') + 1);
                $data = explode(',', $str);
                foreach ($data as $val) {
                    $tmp = explode(':', $val);
                    if ($tmp[0] == 'sellPrice2') {
                        $value = trim($tmp[1], '\'') / 100;
                    }
                }
                break;
        }
        return $value;
    }
}