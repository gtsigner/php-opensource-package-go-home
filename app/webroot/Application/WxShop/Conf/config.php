<?php
return array(
    //'配置项'=>'配置值'
    'URL_MODEL' => 1,
    //Theme
    'DEFAULT_THEME' => 'wx',
    'TMPL_PARSE_STRING' => array(
        '__THEME__' => __ROOT__ . '/theme/wx', // 增加新的JS类库路径替换规则
    ),
    'SHOW_PAGE_TRACE' => false,
);