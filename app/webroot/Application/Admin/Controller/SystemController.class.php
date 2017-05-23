<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/30
 * Time: 20:13
 */

namespace Admin\Controller;


class SystemController extends AdminController
{
    public function setting()
    {
        if (IS_POST) {

        } else {
            $this->display();
        }
    }

    public function info()
    {
        phpinfo();
    }

    /**
     * 清理系统缓存
     */
    public function clearcache()
    {
        removeDir(RUNTIME_PATH);
        S('system_config', null);
        S('system_level', null);
        $mem = new Memcache();
        $mem->clear();
        $this->success("移除缓存成功");
    }
}