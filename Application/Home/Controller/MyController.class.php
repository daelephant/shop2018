<?php
namespace Home\Controller;
class MyController extends NavController {
    public function __construct()
    {
        parent::__construct();
        $memberId = session('m_id');
        if(!$memberId){
            session('returnUrl',U('My/'.ACTION_NAME));
            redirect(U('Member/login'));
        }
    }

    public function order(){
        $orderModel = D('Admin/order');
        $data = $orderModel->search();
        //设置页面信息
        $this->assign(array(
            'data' => $data,
            '_page_title' => '个人中心-我的订单',
        ));
        $this->display();
    }
}