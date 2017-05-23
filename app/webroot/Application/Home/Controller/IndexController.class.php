<?php

namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{

    public function index()
    {
        $this->display();
    }

    /**
     * 订单支付成功
     * @param type $money
     * @param type $param
     */
    public function pay($money, $param)
    {
        if (session("pay_verify") == true) {
            session("pay_verify", null);
            //处理goods1业务订单、改名good1业务订单状态
            M("Goods1Order")->where(array('order_id' => $param['order_id']))->setInc('haspay', $money);
        } else {
            E("Access Denied");
        }
    }

}
