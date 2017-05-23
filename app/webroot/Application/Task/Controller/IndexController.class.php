<?php

namespace Task\Controller;

use Common\Sms\HySms;
use Think\Log;

class IndexController extends TaskController
{
    public function index()
    {
        set_time_limit(0);//设置脚本超时
        $this->_sms_package_task();
    }

    //短信任务
    private function _sms_package_task()
    {
        $hyHelper = new HySms();
        //处理队列的所有数据,进行发送
        while (true) {
            $sms = sms_queue_lpop();
            if (!$sms) {
                $dateNow = date("Y-m-d H:i:s");
                $log = "sms queue is empty Date:{$dateNow}.\n";
                file_put_contents(DATA_PATH . "logs/sms.log", $log, FILE_APPEND);
                //如果没有数据,就直接退出
                break;
            }
            $isOK = $hyHelper->sendSmsBean($sms);
            if (!$isOK) {
                //TODO 失败记录
                //step:1 返换扣费
                M('package')->where(array('id' => $sms['package_id']))->setField('notice_sms_status', 3);
                $log = "Sms send FAIL.\n";
                file_put_contents(DATA_PATH . "logs/sms.log", $log, FILE_APPEND);
            } else {
                //TODO 成功记录
                //Step2：修改包裹发送记录
                M('package')->where(array('id' => $sms['package_id']))->setField('notice_sms_status', 1);
                $log = "Sms send SUCCESS.\n";
                file_put_contents(DATA_PATH . "logs/sms.log", $log, FILE_APPEND);
            }
        }
        //在本次结束后,去
    }

    //短信任务
    private function _sms_package_task_bak()
    {
        $hyHelper = new HySms();
        //处理队列的所有数据,进行发送
        while (true) {
            $sms = sms_queue_lpop();
            if (!$sms) {
                $dateNow = date("Y-m-d H:i:s");
                echo "sms queue is empty Date:{$dateNow}.\n";
                sleep(1);//如果没有数据,就等待10秒再去
                continue;
            }
            $isOK = $hyHelper->sendSmsBean($sms);
            file_put_contents("sms.log", json_encode($isOK) . "\n", FILE_APPEND);
            if (!$isOK) {
                //TODO 失败记录
                //step:1 返换扣费
                M('package')->where(array('id' => $sms['package_id']))->setField('notice_sms_status', 3);
                echo "Sms send FAIL.\n";
            } else {
                //TODO 成功记录
                //Step2：修改包裹发送记录
                M('package')->where(array('id' => $sms['package_id']))->setField('notice_sms_status', 1);
                echo "Sms send SUCCESS.\n";
            }
        }
        //在本次结束后,去
    }
}