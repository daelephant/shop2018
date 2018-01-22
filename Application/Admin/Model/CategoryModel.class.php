<?php
namespace Admin\Model;
use Think\Model;
class CategoryModel extends Model
{
	protected $insertFields = array('cat_name','parent_id');
	protected $updateFields = array('id','cat_name','parent_id');
	protected $_validate = array(
        ////array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
		array('cat_name', 'require', '分类名称不能为空！', 1, 'regex', 3),
	);

	/************************************ 其他方法 ********************************************/
    //找出一个分类所有子分类的ID
    public function getChildren($catId){
        //取出所有的分类
        $data = $this->select();
        //递归从所有的分类中挑出子分类的ID
        return $this->_getChildren($data,$catId,TRUE);
    }
    /**
     * 递归从数据中找子类
     */
    private function _getChildren($data,$catId,$isClear=FALSE){
        static $_ret = array();//保存找到的子分类的ID
        if ($isClear)
            $_ret = array();
        //循环所有的分类查找子分类
        foreach ($data as $k => $v){
            if ($v['parent_id'] == $catId){
                $_ret = $v['id'];
                //再找这个$v的子分类
                $this->_getChildren($data,$v['id']);
            }
        }
        return $_ret;
    }
}