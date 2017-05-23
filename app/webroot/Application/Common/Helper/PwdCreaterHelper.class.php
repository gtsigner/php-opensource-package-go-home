<?php
/**
 * Created by PhpStorm.
 * User: zhaojunlike
 * Date: 2016/10/5
 * Time: 14:41
 */

namespace Common\Helper;


class PwdCreaterHelper
{

    protected $config = array(
        'chars_int' => '23456789',
        'chars_str' => 'abcdfghjkmnpqrstuvwxyz'
    );

    public function createRandString($length = 8)
    {
        $chars_int = $this->config['chars_int'];
        $chars_str = $this->config['chars_str'];
        $password = '';
        //生成数字
        for ($i = 0; $i < $length - 3; $i++) {
            $password .= $chars_int[mt_rand(0, strlen($chars_int) - 1)];
        }
        //生成字幕
        for ($i = 0; $i < 3; $i++) {
            $password .= $chars_str[mt_rand(0, strlen($chars_str) - 1)];
        }

        $password = str_shuffle($password);
        return $password;
    }
}