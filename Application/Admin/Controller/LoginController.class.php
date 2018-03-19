<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18.3.17
 * Time: 22:35
 */
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller{

    public function chkcode(){
        $Verify = new \Think\Verify(array(
           'fontSize' => 30,  //验证码字体大小
           'length'   => 2,   //验证码位数
            'useNoise'=> TRUE,//开启or关闭验证码杂点
        ));
        $Verify->entry();
    }

    public function login(){
        $this->display();
    }
}