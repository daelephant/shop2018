<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends NavController {
    //首页
    public function index(){
        //每个页面都要用到导航条，写父类控制器
        //$catModel = D('Admin/Category');
        //$catData = $catModel->getNavData();
        //设置页面信息
        $this->assign(array(
            '_show_nav' => 1,
            '_page_title' => '首页',
            '_page_keywords' => '首页',
            '_page_description' => '首页',
        ));
        $this->display();
    }
    //商品详情页
    public function goods(){
        //设置页面信息
        $this->assign(array(
            '_show_nav' => 0,
            '_page_title' => '商品详情页',
            '_page_keywords' => '特价商品详情页...',
            '_page_description' => '特卖商品详情页...',
        ));
        $this->display();
    }
}