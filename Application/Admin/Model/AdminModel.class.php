<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model 
{
	protected $insertFields = array('username','password','cpassword');
	protected $updateFields = array('id','username','password','cpassword');
	protected $_validate = array(
		array('username', 'require', '用户名不能为空！', 1, 'regex', 3),
		array('username', '1,30', '用户名的值最长不能超过 30 个字符！', 1, 'length', 3),
		//第六个参数：代表规则什么时候生效，1：仅添加时生效，2：修改时生效，3：所有情况生效
		array('password', 'require', '密码不能为空！', 1, 'regex', 1),
		//array('password', '1,32', '密码的值最长不能超过 32 个字符！', 1, 'length', 3),
		array('username', '', '用户名已经存在！', 1, 'unique', 3),
		array('cpassword','password','两次密码输入不一致',1,'confirm',3),
	);
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
	/************************************ 其他方法 ********************************************/
}