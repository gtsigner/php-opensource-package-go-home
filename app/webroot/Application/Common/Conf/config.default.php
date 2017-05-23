<?php
defined('THINK_PATH') or exit();
//基础配置
$arr1 = array(
    'LOAD_EXT_CONFIG' => 'sdk,pay,user',
    'URL_MODEL' => 1,

    //'配置项'=>'配置值'
    'WECAHT_OPTIONS' => array(
        'token' => '', //填写你设定的key
        'encodingaeskey' => '', //填写加密用的EncodingAESKey
        'appid' => '', //填写高级调用功能的app id
        'appsecret' => '' //填写高级调用功能的密钥
    ),

    /* 文件上传相关配置 */
    'DOWNLOAD_UPLOAD' => array(
        'mimes' => '', //允许上传的文件MiMe类型
        'maxSize' => 5 * 1024 * 1024, //上传的文件大小限制 (0-不做限制)
        'exts' => 'jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml', //允许上传的文件后缀
        'autoSub' => true, //自动子目录保存文件
        'subName' => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => UPLOADS_PATH, //保存根路径
        'savePath' => 'download/', //保存路径
        'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt' => '', //文件保存后缀，空则使用原后缀
        'replace' => false, //存在同名是否覆盖
        'hash' => true, //是否生成hash编码
        'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
    ), //下载模型上传配置（文件上传类配置）


    /* SESSION 和 COOKIE 配置 */
    'DATA_CACHE_PREFIX' => 'fx_', // 缓存前缀
    'DATA_CACHE_TYPE' => 'Redis',
    'SESSION_PREFIX' => 'ey_s_', //session前缀
    'COOKIE_PREFIX' => 'ey_c_', // Cookie前缀 避免冲突

    'PACKAGE_IMG_UPLOAD' => array(
        'mimes' => '', //允许上传的文件MiMe类型
        'maxSize' => 5 * 1024 * 1024, //上传的文件大小限制 (0-不做限制)
        'exts' => 'jpg,gif,png,jpeg', //允许上传的文件后缀
        'autoSub' => true, //自动子目录保存文件
        'subName' => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => UPLOADS_PATH, //保存根路径
        'savePath' => 'package/', //保存路径
        'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt' => '', //文件保存后缀，空则使用原后缀
        'replace' => false, //存在同名是否覆盖
        'hash' => true, //是否生成hash编码
        'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
    ), //附件上传配置（文件上传类配置）

    'APP_UPDATE_UPLOAD' => array(
        'mimes' => '', //允许上传的文件MiMe类型
        'maxSize' => 0,
        'exts' => 'apk', //允许上传的文件后缀
        'autoSub' => true, //自动子目录保存文件
        'subName' => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => UPLOADS_PATH, //保存根路径
        'savePath' => 'app/', //保存路径
        'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt' => '', //文件保存后缀，空则使用原后缀
        'replace' => false, //存在同名是否覆盖
        'hash' => true, //是否生成hash编码
        'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
    ), //附件上传配置（文件上传类配置）

    'APP_BANNER_UPLOAD' => array(
        'mimes' => '', //允许上传的文件MiMe类型
        'maxSize' => 0,
        'exts' => 'jpg,png,jpeg,gif', //允许上传的文件后缀
        'autoSub' => true, //自动子目录保存文件
        'subName' => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => UPLOADS_PATH, //保存根路径
        'savePath' => 'banner/', //保存路径
        'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt' => '', //文件保存后缀，空则使用原后缀
        'replace' => false, //存在同名是否覆盖
        'hash' => true, //是否生成hash编码
        'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
    ), //附件上传配置（文件上传类配置）

    'PUBLIC_UPLOAD' => array(
        'mimes' => '', //允许上传的文件MiMe类型
        'maxSize' => 5 * 1024 * 1024, //上传的文件大小限制 (0-不做限制)
        'exts' => 'jpg,gif,png,jpeg', //允许上传的文件后缀
        'autoSub' => true, //自动子目录保存文件
        'subName' => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => UPLOADS_PATH, //保存根路径
        'savePath' => 'public/', //保存路径
        'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt' => '', //文件保存后缀，空则使用原后缀
        'replace' => false, //存在同名是否覆盖
        'hash' => true, //是否生成hash编码
        'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
    ), //附件上传配置（文件上传类配置）
    'SESSION_AUTO_START' => true,


    'REDIS_HOST' => 'redis-db',
    'REDIS_PORT' => 6379,

    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => 'mysql-db', // 服务器地址
    'DB_NAME' => 'package_v1', // 数据库名
    'DB_USER' => 'root', // 用户名
    'DB_PWD' => 'zhaojun', // 密码
    'DB_PORT' => '3306', // 端口
    'DB_PREFIX' => 'ey_', // 数据库表前缀
    'is_dev' => 0,//开发者模式

    'ALIDAYU_APP_KEY' => '',
    'ALIDAYU_SECRET_KEY' => '',
);
$otherConfigs = array();
return array_merge($arr1, $otherConfigs);