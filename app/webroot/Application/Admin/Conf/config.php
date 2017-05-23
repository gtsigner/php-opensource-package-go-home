<?php
return array(
    //'配置项'=>'配置值'
    'URL_MODEL' => 1,
    //Theme
    'DEFAULT_THEME' => 'v1',
    'TMPL_PARSE_STRING' => array(
        '__THEME__' => __ROOT__ . '/admin/v1', // 增加新的JS类库路径替换规则
    ),
    'TOKEN_ON' => false, // 是否开启令牌验证 默认关闭
    'TOKEN_NAME' => '__hash__', // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE' => 'md5', //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET' => false, //令牌验证出错后是否重置令牌
    'SHOW_PAGE_TRACE' => false,
);
