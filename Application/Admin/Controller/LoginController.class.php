<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller
{
    public function chkcode(){
        $Verify = new \Think\Verify(array(
            'fontSize'=>30,//验证码字体大小
            'length'=>2,//验证码长度
            'useNoise'=>TRUE,//开启or关闭验证码杂点
        ));
        $Verify->entry();
    }
    public function login()
    {
        if(IS_POST){
            $model = D('Admin');
            //接收表单并且验证表单,不能用默认的验证规则，需要手动指定
            if ($model->validate($model->_login_validata)->create()){
                if ($model->login()){
                    $this->success('登录成功',U('Index/index'));
                    exit;
                }
            }
            $this->error($model->getError());
        }
    	$this->display();
    }
    public function logout()
    {
        $model = D('Admin');
        $model->logout();
        redirect('login');
    	//$this->display();
    }

    
}