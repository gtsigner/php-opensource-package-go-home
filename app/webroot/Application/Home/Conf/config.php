<?php
return array(
    //'配置项'=>'配置值'
    'URL_MODEL' => 1,
    //Theme
    'DEFAULT_THEME' => 'v1',
    'TMPL_PARSE_STRING' => array(
        '__THEME__' => __ROOT__ . '/theme/home', // 增加新的JS类库路径替换规则
    ),
    'SHOW_PAGE_TRACE' => false,
);
