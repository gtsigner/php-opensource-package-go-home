<?php
/**
 * system .
 * system
 * Author：软件开发,网站建设,网站优化/
 * Date: 2016/8/23
 * Time: 13:47
 */

namespace Common\Helper;

/**
 * 包含了所有验证
 * Class CheckRHelper
 * @package Common\Helper
 */
class CheckRHelper
{


    public static function isPhone($txt)
    {
        if (preg_match("/\d{3}\d{8}/", $txt)) {
            return true;
        } else {
            return false;
        }
    }


    public static function isUrl($txt)
    {
        if (preg_match("/[a-zA-z]+://[^\s]*/", $txt)) {
            return true;
        } else {
            return false;
        }
    }

    public static function isEmail($txt)
    {
        if (preg_match("/[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/", $txt)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 是否是身份证件号码
     * @param $txt
     * @return bool
     */
    public static function isIDCard($txt)
    {
        if (preg_match("/^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/", $txt)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 是否是中文
     * @param $txt
     * @return bool
     */
    public static function isZhCN($txt)
    {
        if (preg_match("/.*[\u4e00-\u9fa5].*/", $txt)) {
            return true;
        } else {
            return false;
        }
    }
}