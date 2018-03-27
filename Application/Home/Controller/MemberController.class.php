<?php
namespace Home\Controller;
use Think\Controller;
class MemberController extends Controller {

    public function ajaxChkLogin(){
        if(session('m_id'))
            echo json_encode(array(
               'login' => 1,
               'username' => session('m_username'),
            ));
        else
            echo json_encode(array(
               'login' => 0,
            ));
    }
    //制作验证码
    public function chkcode(){
        $Verify = new \Think\Verify(array(
            'fontSize'=>30,//验证码字体大小
            'length'=>2,//验证码长度
            'useNoise'=>TRUE,//开启or关闭验证码杂点
        ));
        $Verify->entry();
    }
    //登录
    public function login(){
       if(IS_POST){
           $model = D('Admin/Member');
           if ($model->validate($model->_login_validate)->create()){
               if($model->login()){
                   $this->success('登录成功！',U('/'));
                   exit;
               }
           }
           $this->error($model->getError());
       }
       //设置页面信息
        $this->assign(array(
            '_page_title' => '登录',
            '_page_keywords' => '登录',
            '_page_description' => '登录',
        ));
        $this->display();
    }

    //注册
    public function regist(){
        if(IS_POST){
            $model = D('Admin/Member');
            if ($model->create(I('post.',1))){
                if($model->add()){
                    $this->success('注册成功！',U('login'));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        $this->assign(array(
            '_page_title' => '注册',
            '_page_keywords' => '注册',
            '_page_description' => '注册',
        ));
        $this->display();
    }
    public function logout(){
        $model = D('Admin/Member');
        $model->logout();
        redirect('/');
    }

}