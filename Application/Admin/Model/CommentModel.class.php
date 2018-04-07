<?php
namespace Admin\Model;
use Think\Model;
class CommentModel extends Model
{
    //评论时允许提交的字段
	protected $insertFields = array('star','content','goods_id');
	//发表评论时表单验证规则
	protected $_validate = array(
		array('goods_id', 'require', '参数错误！', 1),
		array('star', '1,2,3,4,5', '分值只能是1-5之间的数字！', 1, 'in'),
		//第四个参数表示一定验证
		//第六个参数：代表规则什么时候生效，1：仅添加时生效，2：修改时生效，3：所有情况生效
		array('content','1,200','内容必须是1-200个字符！',1,'length'),
	);

	// 添加前
	protected function _before_insert(&$data, $option)
	{
	    $memberId = session('m_id');
	    if(!$memberId){
	        $this->error = '必须先登录';
	        return FALSE;
        }
        $data['member_id'] = $memberId;
	    $data['addtime'] = date('Y-m-d H:i:s');
	}

	/************************************ 其他方法 ********************************************/
}