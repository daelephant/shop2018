<?php
namespace Admin\Model;
use Think\Model;
class MemberModel extends Model
{
    //添加和修改会员时使用的表单验证规则
	protected $insertFields = array('username','password','cpassword','chkcode','must_click');
	protected $updateFields = array('id','username','password','cpassword');
	//注册时用到
	protected $_validate = array(
		array('must_click', 'require', '必须同意注册协议！', 1, 'regex', 3),
		array('username', 'require', '用户名不能为空！', 1, 'regex', 3),
		array('username', '1,30', '用户名的值最长不能超过 30 个字符！', 1, 'length', 3),
		//第六个参数：代表规则什么时候生效，1：仅添加时生效，2：修改时生效，3：所有情况生效
		array('password', 'require', '密码不能为空！', 1, 'regex', 1),
		array('password', '6,20', '密码的值最长不能低于6 或超过 20 个字符！', 1, 'length', 3),
		array('username', '', '用户名已经存在！', 1, 'unique', 3),
		array('cpassword','password','两次密码输入不一致!',1,'confirm',3),
        array('chkcode','require','验证码不能为空!!!',1),
        array('chkcode','check_verify','验证错误',1,'callback'),
	);
	//登录时用到
	//为登录的表单定义一个验证规则,需要时公开的属性才可以调用
    public $_login_validate = array(
        array('username','require','用户名不能为空！！',1),
        array('password','require','密码不能为空！！',1),
        array('chkcode','require','验证码不能为空!!!',1),
        array('chkcode','check_verify','验证码验证错误',1,'callback'),
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
        $user = $this->field('id,username,password,jifen')->where(array(
            'username' => array('eq',$username),
        ))->find();
        if($user){
            if ($user['password'] == md5($password)){
                //登录成功存session
                session('m_id',$user['id']);
                session('m_username',$user['username']);
                session('face','/Public/Home/images/user1.gif');
                //计算当前会员级别ID并保存SESSION
                $mlModel = D('member_level');
                $levelId = $mlModel->field('id')->where(array(
                    'jifen_bottom' => array('elt',$user['jifen']),
                    'jifen_top' => array('egt',$user['jifen']),
                ))->find();
                //$sql = $mlModel->getLastSql();//SELECT `id` FROM `p2018_member_level` WHERE `jifen_bottom` <= '20000' AND `jifen_top` >= '20000' LIMIT 1
                //$levelId = json_encode($levelId);//{"id":"3"}
                //file_put_contents('./model.txt',"$levelId");
                session('level_id',$levelId['id']);
                // move CartData in cart to DB
                $cartModel = D('Home/Cart');
                $cartModel->moveDataToDb();
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
    public function _before_insert(&$data, $options)
    {
        $data['password'] = md5($data['password']);
    }

    public function logout(){
        session(null);
    }
	/************************************ 其他方法 ********************************************/
}