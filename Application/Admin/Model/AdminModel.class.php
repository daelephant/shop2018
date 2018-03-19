<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model 
{
	protected $insertFields = array('username','password','cpassword','chkcode');
	protected $updateFields = array('id','username','password','cpassword');
	protected $_validate = array(
		array('username', 'require', '用户名不能为空！', 1, 'regex', 3),
		array('username', '1,30', '用户名的值最长不能超过 30 个字符！', 1, 'length', 3),
		//第六个参数：代表规则什么时候生效，1：仅添加时生效，2：修改时生效，3：所有情况生效
		array('password', 'require', '密码不能为空！', 1, 'regex', 1),
		//array('password', '1,32', '密码的值最长不能超过 32 个字符！', 1, 'length', 3),
		array('username', '', '用户名已经存在！', 1, 'unique', 3),
		array('cpassword','password','两次密码输入不一致!',1,'confirm',3),
	);
	//为登录的表单定义一个验证规则,需要时公开的属性才可以调用
    public $_login_validata = array(
        array('username','require','用户名不能为空！！',1),
        array('password','require','密码不能为空！！',1),
        array('chkcode','require','验证码不能为空!!!',1),
        array('chkcode','check_verify','验证错误',1,'callback'),
    );
    //验证 验证码是否正确
    function check_verify($code,$id=''){
        $verify = new \Think\Verify();
        return $verify->check($code,$id);
    }
    public function login(){
        //从模型中获取用户名和密码
        $username = $this->username;
        $password = $this->password;
        //先检查这个用户名是否存在
        $user = $this->where(array(
            'username' => array('eq',$username),
        ))->find();
        if($user){
            if ($user['password'] == md5($password)){
                //登录成功存session
                session('id',$user['id']);
                session('username',$user['username']);
                return TRUE;
            }else{
                $this->error = '密码不正确！';
                return FALSE;
            }
        }else{
            $this->error = '用户不存在！';
            return FALSE;
        }
    }
	public function search($pageSize = 20)
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		if($username = I('get.username'))
			$where['username'] = array('like', "%$username%");
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, $pageSize);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		/************************************** 取数据 ******************************************/
		$data['data'] = $this->alias('a')->where($where)->group('a.id')->limit($page->firstRow.','.$page->listRows)->select();
		return $data;
	}
	// 添加前
	protected function _before_insert(&$data, $option)
	{
	    $data['password'] = md5($data['password']);
	}
	// 修改前
	protected function _before_update(&$data, $option)
	{
	    if($data['password']){
	        $data['password'] = md5($data['password']);
        }else{
	        unset($data['password']);//直接从表单中删除这个字段就不会修改这个字段了！！！
        }
	}
	// 删除前
	protected function _before_delete($option)
	{
        if($option['where']['id'] == 1){
            $this->error = "超级管理员无法删除";
            return FALSE;
        }
	}
	public function logout(){
        session(null);
    }
	/************************************ 其他方法 ********************************************/
}