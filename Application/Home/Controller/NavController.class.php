<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18.3.20
 * Time: 22:42
 */
namespace Home\Controller;
use Think\Controller;
class NavController extends Controller{

    public function __construct()
    {
        //以后哪个页面需要导航条，继承这个控制器就行
        //写构造函数必须先调用父类的构造函数
        parent::__construct();
        $catModel = D('Admin/Category');
        $catData = $catModel->getNavData();
        $this->assign('catData',$catData);
        //dump($catData);exit;
    }

}