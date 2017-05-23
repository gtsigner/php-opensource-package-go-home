<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/27
 * Time: 13:26
 */

namespace Api\Controller;


class SmsController extends ApiAuthController
{
    //获取模版列表
    public function get_tpl_list()
    {
        $map['uid'] = $this->user['id'];
        $data = M('user_sms_tpl')->where($map)->select();
        $this->apiReturn('success', $data);
    }

    //设置默认模版
    public function set_default_tpl()
    {
        //先设置默认
        $dMap['uid'] = $this->user['id'];
        $dMap['id'] = I('tpl_id');
        $dMap['status'] = 1;
        $upRet = M('user_sms_tpl')->where($dMap)->setField('is_default', 1);
        if (!$upRet) {
            $this->errorMsg('设置失败,模版不存在,或者正在等待系统审核');
        }
        $map['uid'] = $this->user['id'];
        $map[] = "`id`!={$dMap['id']}";
        M('user_sms_tpl')->where($map)->setField('is_default', 0);
        $this->successMsg('设置成功');
    }

    //删除模版
    public function del_tpl()
    {
        $map['uid'] = $this->user['id'];
        $tplCount = M('user_sms_tpl')->where($map)->count();
        if ($tplCount <= 1) {
            $this->errorMsg('删除失败,您必须保留至少一个模版.');
        }
        $map['id'] = I('tpl_id');
        $map['is_default'] = 0;
        $delRet = M('user_sms_tpl')->where($map)->delete();
        if ($delRet) {
            $this->successMsg('删除成功');
        } else {
            $this->errorMsg('删除失败,模版不存在或者不可删除默认模版');
        }
    }

    //编辑模版
    public function edit_tpl()
    {
        $tplMap['id'] = I('id');
        $smsTpl = M('user_sms_tpl')->where($tplMap)->find();
        if (!$smsTpl) {
            $this->errorMsg('对不起,短息模版不存在');
        }
        $smsTpl = M('user_sms_tpl')->create();
        if (!$smsTpl) {
            $this->errorMsg('操作失败,系统可能过于繁忙!!!');
        }
        $smsTpl['uid'] = $this->user['id'];
        $smsTpl['create_time'] = time();
        $smsTpl['is_default'] = 0;//取消默认
        $smsTpl['status'] = 0;//审核,如果被编辑了,就需要重新审核
        $addRet = M('user_sms_tpl')->where(array('id' => I('id')))->save($smsTpl);
        if (!$addRet) {
            $this->errorMsg('保存失败,请稍后重试');
        } else {
            $this->successMsg('编辑保存成功');
        }
    }

    //添加模版
    public function add_tpl()
    {
        //TODO  限制模版数量
        $tplCount = M('user_sms_tpl')->where(array('uid' => $this->user['id']))->count();
        if ($tplCount >= 10) {
            $this->errorMsg("对不起,每一个门店只可以拥有最多10个模版短信");
        }
        $tpl = M('user_sms_tpl')->create();
        if (!$tpl) {
            $this->errorMsg('添加失败');
        }
        $tpl['uid'] = $this->user['id'];
        $tpl['create_time'] = time();
        $tpl['is_default'] = 0;
        $tpl['status'] = 0;//审核
        $addRet = M('user_sms_tpl')->add($tpl);
        if (!$addRet) {
            $this->errorMsg('添加失败,请稍后重试');
        } else {
            $this->successMsg('添加成功');
        }
    }

    //获取
    public function get_pool($p = 1)
    {
        $map['uid'] = $this->user['id'];
        $data['pools'] = M('user_sms_pool')
            ->where($map)
            ->page($p, $this->page_limit)
            ->select();

        $data['static']['total_count'] = intval(M('user_sms_pool')
            ->where($map)
            ->count());
        $data['static']['p_num'] = $p;

        $this->apiReturn('获取短信池成功', $data);
    }
}