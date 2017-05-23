<?php

namespace Common\Behavior;

use Think\Behavior;

/**
 * Author: zhaojunlike@
 * Gtihub: https://github.com/zhaojunlike
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Class InitConfigBehavior
 * @package Common\Behavior
 */
class InitConfigBehavior extends Behavior
{

    /**
     * 执行行为 run方法是Behavior唯一的接口
     * @access public
     * @param mixed $params 行为参数
     * @return void
     */
    public function run(&$params)
    {
        //加载配置
        if (!APP_DEBUG) {
            $config = S('system_config');
        }
        if (!$config) {
            $config = M('system_config')->getField('key,value');
            S('system_config', $config);
        }
        C($config);

    }
}