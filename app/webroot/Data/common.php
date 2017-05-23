<?php
/**
 * Email:zhaojunlike@gmail.com
 * Date: 2017/1/7
 * Time: 16:08
 */
if (version_compare(PHP_VERSION, '5.5.0', '<')) die('require PHP > 5.5.0 !');

define('APP_DEBUG', true);
define('APP_PATH', '../Application/');
define('UPLOADS_PATH', "../Uploads/");
define('DATA_PATH', "../Data/");
define("RUNTIME_PATH", APP_PATH . 'Runtime/');

/**
 * Const
 */
const Dev_Company = "成都易猿网络科技有限责任公司";
//微信callback
const WeChat_CallBack = "http://host/wx.php/oauth/handler";
const WEB_HOST_PATH = "http://host/";