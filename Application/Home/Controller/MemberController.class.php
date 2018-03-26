<?php
namespace Home\Controller;
use Think\Controller;
class MemberController extends Controller {

    //首页
    public function index(){
       
        $this->assign(array(
            'goods1' => $goods1,
            'goods2' => $goods2,
            'goods3' => $goods3,
            'goods4' => $goods4,
            'floorData' => $floorData,
            '_show_nav' => 1,
            '_page_title' => '首页',
            '_page_keywords' => '首页',
            '_page_description' => '首页',
        ));
        $this->display();
    }
}