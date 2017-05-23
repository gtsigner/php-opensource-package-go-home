<?php
/**
 * Mark: 网站开发，软件开发，远程运维
 * Email:zhaojunlike@gmail.com
 * Date: 2016/12/27
 * Time: 12:48
 */

namespace Api\Controller;

use Common\Helper\CheckRHelper;
use Common\Service\WeChatUserService;
use Think\Upload;

class PackageController extends ApiAuthController
{
    //获取包裹类型
    public function get_package_types()
    {
        $map = array();
        $data = M('package_type')->where($map)->select();
        $this->apiReturn('success', $data);
    }

    //包裹入库
    public function push_package()
    {
        //包裹基本信息
        $package = array();
        $package['uid'] = $this->user['id'];
        $package['yun_no'] = I('yun_no') == '' ? $this->errorMsg('请输入正确的运单号') : I('yun_no');

        $package['company_id'] = I('company_id');

        $package['get_address'] = I('get_address');
        $package['get_phone'] = I('get_phone');
        $package['get_name'] = I('get_name');
        $package['get_no'] = I('get_no') == '' ? $this->errorMsg('请输入正确的提货码') : I('get_no');

        $package['package_type_id'] = I('package_type_id');//包裹类型ID,如果为0，那么就是text
        $package['package_type_text'] = I('package_type_text');
        $package['notice_wx_status'] = 0;
        $package['notice_sms_status'] = 0;

        #region
        //TODO step1： 验证信息
        //1.验证包裹号和公司
        $existPackage = M('package')->where(array('yun_no' => $package['yun_no'], 'company_id' => $package['company_id']))->find();
        if ($existPackage) {
            $this->errorMsg('对不起,此包裹似乎已经入库了,您再检查一下？');
        }
        if (strlen(I('yun_no')) <= 6) {
            $this->errorMsg('运单号太短啦,再长点!!!');
        }
        //2.验证公司ID, 增加默认
        $company = M('express_company')->where(array('id' => $package['company_id']))->find();
        if (!$company) {
            $this->errorMsg('请选择正确的快递公司');
        }
        //3.验证包裹类型ID

        //.4 验证手机号
        if (!CheckRHelper::isPhone($package['get_phone'])) {
            $this->errorMsg('对不起,手机号码不正确');
        }
        #endregion
        #region //step2： 生成sender,手机号确认一个用户
        $senderMap['phone'] = $package['get_phone'];
        //$senderMap['name'] = $package['get_name'];
        $sender = M('sender')->where($senderMap)->find();
        if (!$sender) {
            //ADD sender
            $senderMap['create_time'] = time();
            $senderMap['send_count'] = 1;
            $senderMap['address'] = $package['get_address'];
            $senderMap['get_name'] = $package['get_name'];
            $sender['id'] = M('sender')->add($senderMap);
        } else {
            // ++
            //M('sender')->where($senderMap)->setInc('send_count');
            $sender['name'] = $package['get_name'];
            $sender['address'] = $package['get_address'];
            $sender['send_count'] += 1;
            M('sender')->where($senderMap)->save($sender);
        }
        //关联收货人
        #endregion
        $package['sender_id'] = $sender['id'];
        $package['push_time'] = time();
        $addRet = M('package')->add($package);
        if ($addRet) {
            //取消，让用户手动发短信，是否发送短信
            $this->_package_notice($addRet, $package);

            $this->successMsg('包裹入库成功.');
        } else {
            $this->errorMsg('包裹入库异常,请重新尝试.');
        }
    }

    /**
     *
     * @param $package_id 包裹iD
     * @param bool $cache 是否缓存
     */
    private function _package_notice($package_id, $cache = true)
    {
        $package = M('package p')
            ->join('LEFT JOIN ey_express_company c ON c.id=p.company_id')
            ->where(array('p.id' => $package_id))
            ->field('p.*,c.name as company_name')
            ->find();
        $wxRet = false;
        //发微信
        if ($this->user['wx_notice_status'] == 1) {
            //入库的时候
            $wxRet = WeChatUserService::noticeUserPackage($package);
        }
        //修改,
//        if ($this->user['sms_notice_status'] == 1 && $wxRet == false) {
        if ($this->user['sms_notice_status'] == 1) {
            //查询短信余额
            $smsPool = M('user_sms_pool')
                ->where(array('uid' => $this->user['id'], 'status' => 1))
                ->where("last_count >0")
                ->order("create_time ASC")
                ->find();
            if (!$smsPool) {
                //修改包裹状态
                M('package')->where(array('id' => $package_id))->setField('notice_sms_status', 4);//余额不足
                //TODO 发送消息通知
            } else {
                //获取用户默认模版
                $defaultTpl = get_user_default_sms_tpl($this->user['id']);
                if ($defaultTpl) {
                    //加入用户等待队列
                    $sms = array();
                    $sms['phone'] = $package['get_phone'];
                    $sms['content'] = $defaultTpl['tpl_content'];//替换模版变量
                    //收货地址
                    $sms['content'] = str_replace('{$address}', $this->user['address_desc'], $sms['content']);
                    //提货码
                    $sms['content'] = str_replace('{$get_no}', $package['get_no'], $sms['content']);
                    //公司
                    $sms['content'] = str_replace('{$company_name}', $package['package_name'], $sms['content']);
                    $sms['package_id'] = $package_id;
                    $sms['uid'] = $this->user['id'];
                    if ($cache) {
                        //如果缓存
                        $smsList = S("user_sms_list_{$this->user['id']}");//获取缓存
                        if (!$smsList) {
                            $smsList = [];
                        }
                        array_push($smsList, $sms);//
                        S("user_sms_list_{$this->user['id']}", $smsList);
                    } else {
                        //直接发送
                        sms_queue_rpush($sms);
                    }
                    M('package')->where(array('id' => $package_id))->setField('notice_sms_status', 2);//队列中
                    M('user_sms_pool')->where(array('id' => $smsPool['id']))->setDec("last_count");
                } else {
                    //没有默认模版就使用系统默认模版 TODO
                    M('package')->where(array('id' => $package_id))->setField('notice_sms_status', 7);//无短信模版
                }
            }
        } else {
            M('package')->where(array('id' => $package_id))->setField('notice_sms_status', 8);//余额不足
        }
    }

    //出库
    public function pop_package()
    {
        $map['uid'] = $this->user['id'];
        $map['id'] = I('id') == '' ? $this->errorMsg('请输入包裹ID . ') : I('id');
        $package = M('package')->where($map)->find();
        if (!$package) {
            $this->errorMsg('对不起,该单号包裹不存在 . ');
        }
        //Step1：验证信息,如果是补传照片就不验证
        if ($package['status'] == 5) {
            //$this->errorMsg('对不起,包裹已经出库 . ');
            //那么是补传照片，或者...
        }
        //Step2：获取拍照
        $upConf = C('PACKAGE_IMG_UPLOAD');
        $upModel = new Upload($upConf);
        $upRet = $upModel->upload();
        if ($upRet) {
            //存储信息
            $upRet = $upRet['out_img'];
            $upload['uid'] = $this->user['id'];
            $upload['time'] = time();
            $upload['name'] = $upRet['name'];
            $upload['savename'] = $upRet['savename'];
            $upload['savepath'] = $upRet['savepath'];
            $upload['ext'] = $upRet['ext'];
            $upload['md5'] = $upRet['md5'];
            $upload['sha1'] = $upRet['sha1'];
            $upload['size'] = $upRet['size'];
            $upload['type'] = 0;
            $upload['path'] = "./Uploads/" . $upRet['savepath'] . $upRet['savename'];
            $addImgRet = M('system_upload')->add($upload);
            if (!$addImgRet) {
            } else {
                $package['out_img_id'] = $addImgRet;
            }
        }
        $package['pop_time'] = time();
        $package['status'] = 5;//出库
        unset($package['id']);
        $saveRet = M('package')->where($map)->save($package);
        if ($saveRet) {
            $this->successMsg('出库成功');
        } else {
            $this->errorMsg('出库失败');
        }

    }

    /**
     * 查询
     * Author: zhaojunlike@
     * @param: yun_no
     */
    public function get_package()
    {
        $map['ey_package.uid'] = $this->user['id'];
        $filter = I('filter');//包裹状态
        if ($filter == 1) {
            //移库筛选
            $map['status'] = array('in', array('0', '1', '2'));
        } else {

        }
        $map['ey_package.yun_no'] = I('yun_no') == '' ? $this->errorMsg('请输入运单号.') : I('yun_no');
        $data = M('package')
            ->join('LEFT JOIN ey_express_company c ON c.id = ey_package.company_id')
            ->field('ey_package.*,c.name as company_name')
            ->order("ey_package.status ASC,ey_package.push_time DESC")
            ->where($map)
            ->select();
        if (!$data) {
            $this->errorMsg('对不起,该包裹不存在');
        } else {
            $this->apiReturn("获取包裹成功", $data);
        }
    }

    /**
     * 处理快递
     */
    public function handle_package()
    {
        if (IS_POST) {
            $map = [];
            $map['id'] = I('id');
            $map['uid'] = $this->user['id'];
            $package = M('package')->where($map)->find();
            if (!$package) {
                $this->errorMsg('对不起,该包裹不存在');
            }

            $actionType = I('action_type');
            if ($actionType == 1) {
                //退件操作
                if ($package['status'] >= 3) {
                    $this->errorMsg('对不起,该包裹已经出库或者已经退件');
                }
                M('package')->where($map)->setField("status", 4);//退件
            } else {
                //修改保存
                if ($package['status'] >= 3) {
                    $this->errorMsg('对不起,该包裹已经出库或者已经退件');
                }
                //$package['yun_no'] = I('yun_no') == '' ? $this->errorMsg('请输入正确的运单号') : I('yun_no');
                $package['company_id'] = I('company_id');
                $package['get_phone'] = I('get_phone');
                $package['get_name'] = I('get_name');
                $package['get_no'] = I('get_no');
                $package['notice_wx_status'] = 0;
                $package['notice_sms_status'] = 0;
                M('package')->where($map)->save($package);//保存信息
                //发短信
                $sendNotice = I('send_notice');
                if ($sendNotice == 1) {
                    //发送短信
                    $this->_package_notice($package['id'], false);
                }
            }
            $this->successMsg("操作成功");
        } else {
            //查询
        }
    }


    /**
     * 包裹移库
     * @param：$id int 包裹ID
     */
    public function move_package()
    {
        $map = [];
        $map['id'] = I('id');
        $map['uid'] = $this->user['id'];
        $package = M('package')->where($map)->find();
        if (!$package) {
            $this->errorMsg('对不起,该包裹不存在');
        }
        if ($package['status'] >= 3) {
            $this->errorMsg('对不起,该包裹已经出库或者已经退件');
        }
        //M('package')->where($map)->setField('status', 2);//移库
        //发短信
        $this->_package_notice($package['id'], false);
        $this->successMsg("移库成功");
    }

    //模糊查询自己的包裹列表数据
    public function search_package($p = 1)
    {
        $map = [];
        $map['ey_package.uid'] = $this->user['id'];

        $keywords = trim(I('keywords'));
        if ($keywords != '') {
            $likeStr = "";
            $likeStr .= "ey_package.`get_name` like '%{$keywords}%'  OR ";
            $likeStr .= "ey_package.`get_phone` like '%{$keywords}%'  OR ";
            $likeStr .= "ey_package.`get_no` like '%{$keywords}%'  OR ";
            $likeStr .= "ey_package.`yun_no` like '{$keywords}' OR ";
            $likeStr .= "ey_package.`get_address` like '%{$keywords}%' ";
            $map[] = $likeStr;
        }
        $list = M('package')
            ->where($map)
            ->join('LEFT JOIN ey_express_company c ON c.id = ey_package.company_id')
            ->join('LEFT JOIN ey_package_type t ON t.id = ey_package.package_type_id')
            ->join('LEFT JOIN ey_system_upload up ON up.id = ey_package.out_img_id')
            ->field('ey_package.*,c.name as company_name,t.name as package_type_name,up.path as out_img_path')
            ->order("ey_package.status ASC,ey_package.push_time DESC")
            ->page($p, $this->page_limit)
            ->select();
        //echo M('package')->getLastSql();
        //总数
        $static['total_count'] = M('package')->where($map)->count();

        //未出库
        $static['in_count'] = M('package')->where($map)->where(array('status' => 0))->count();
        $static['p_num'] = $p;

        if (!$list) {
            $this->errorMsg('对不起,为查询到相关包裹信息');
        } else {
            $json['static'] = $static;
            $json['package_list'] = $list;
            $this->apiReturn("获取包裹成功", $json);
        }
    }

    //快递猿查询
    public function get_all_package($p = 1)
    {
        $map = [];

        //验证权限
        if ($this->user['group_type'] != 1) {
            $this->errorMsg("对不起,您没有查询权限");
        }

        $keywords = trim(I('keywords'));
        if ($keywords != '') {
            $likeStr = "";
            $likeStr .= "ey_package.`get_name` like '%{$keywords}%'  OR ";
            $likeStr .= "ey_package.`get_phone` like '%{$keywords}%'  OR ";
            $likeStr .= "ey_package.`get_no` like '%{$keywords}%'  OR ";
            $likeStr .= "ey_package.`yun_no` like '{$keywords}' OR ";
            $likeStr .= "ey_package.`get_address` like '%{$keywords}%' ";
            $map[] = $likeStr;
        }
        $list = M('package')
            ->where($map)
            ->join('LEFT JOIN ey_express_company c ON c.id = ey_package.company_id')
            ->join('LEFT JOIN ey_package_type t ON t.id = ey_package.package_type_id')
            ->join('LEFT JOIN ey_system_upload up ON up.id = ey_package.out_img_id')
            ->join('LEFT JOIN ey_user uu ON uu.id = ey_package.uid')
            ->join('LEFT JOIN ey_user_profile upp ON upp.uid = ey_package.uid')
            ->field('ey_package.*,c.name as company_name,t.name as package_type_name,up.path as out_img_path,upp.address_desc')
            ->order("ey_package.status ASC,ey_package.push_time DESC")
            ->page($p, $this->page_limit)
            ->select();
        //echo M('package')->getLastSql();
        //总数
        $static['total_count'] = M('package')->where($map)->count();

        //未出库
        $static['in_count'] = M('package')->where($map)->where(array('status' => 0))->count();
        $static['p_num'] = $p;

        if (!$list) {
            $this->errorMsg('对不起,为查询到相关包裹信息');
        } else {
            $json['static'] = $static;
            $json['package_list'] = $list;
            $this->apiReturn("获取包裹成功", $json);
        }
    }

    /**
     *获取等待发送的短信队列
     *
     */
    public function get_sms_list()
    {
        $smsList = S("user_sms_list_{$this->user['id']}");//获取缓存
        if ($smsList) {
            $json = [];
            $json['sms_list'] = $smsList;
            $json['sms_count'] = count($smsList);
            $this->apiReturn("获取到数据", $json);
        } else {
            $this->errorMsg("没有等待发送的短信");
        }
    }

    /**
     *开始发送短信
     */
    public function send_sms()
    {
        //取出缓存,然后添加到短信发送队列
        $smsList = S("user_sms_list_{$this->user['id']}");//获取缓存
        S("user_sms_list_{$this->user['id']}", null);
        if ($smsList) {
            $count = 0;
            //TODO  高并发加锁
            while (($item = array_pop($smsList))) {
                sms_queue_rpush($item);
                $count++;
            }
            //清空缓存区
            $send_data['count'] = $count;
            $this->apiReturn("成功加入短信发送队列", $send_data);
        } else {
            $this->errorMsg('短信队列中,没有任何待发送短信');
        }
    }

    /**
     * 自动匹配sender
     */
    public function search_sender()
    {
        $map = [];
        $phone = I('phone');
        if ($phone != '') {
            $map[] = "`phone` like '%{$phone}%'";
        }
        $data['sender_list'] = M('sender')
            ->where($map)
            ->limit(20)
            ->field("id,phone,address,name,sender_type")
            ->order("send_count DESC")
            ->select();

        if (!$data['sender_list']) {
            $data['sender_list'] = [];
        }
        $this->apiReturn("success", $data);
    }
}