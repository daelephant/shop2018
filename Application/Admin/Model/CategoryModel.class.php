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
                $_ret[] = $v['id'];//记得向数组添加元素，要加[],否则返回是字符串。
                //再找这个$v的子分类
                $this->_getChildren($data,$v['id']);
            }
        }
        return $_ret;
    }

    public function getTree(){
        $data = $this->select();
        return $this->_getTree($data);
    }
    private function _getTree($data,$parent_id=0,$level=0){
        static $_ret = array();
        foreach ($data as $k => $v){
            if($v['parent_id'] == $parent_id){
                $v['level'] = $level;//用来标记这个分类是第几级的
                $_ret[] = $v;
                //找子分类
                $this->_getTree($data,$v['id'],$level+1);
            }
        }
        return $_ret;
    }
    /*删除方法一：传统逻辑找到子分类紧接着删除*/
    protected function _before_delete($options)
    {
        //先找出所有子分类的ID
        $children = $this->getChildren($options['where']['id']);
        //var_dump($children);exit();//array(6) { [0]=> string(2) "16" [1]=> string(2) "22" [2]=> string(2) "17" [3]=> string(2) "18" [4]=> string(2) "19" [5]=> string(2) "20" }
        if($children){
            $children = implode(',',$children);//把数组转化成字符串。以，分割
            //删除这些子分类
            //避免before delete死循环
            //说明这里必须生成父类模型然后调用delete，因为如果使用$this调用delete那么在delete之前又会调用$this->>_before_delete
            //这样就死循环了。用父类的delete就会在delete之前调用父类的before_delete和这个_before_delete没关系，就不会死循环了！！！
            $model = new \Think\Model();//实例化父类model
            $model->table('__CATEGORY__')->delete($children);//简化数据表前缀的传入,TP自带
        }
    }
    /*删除方法二：*/
    /******修改源$option,把所有子分类的id也加进来，这样tp会一起删除掉**********/
    //protected function _before_delete(&$options)
    //{
    //        //先找出所有子分类的ID
    //        $children = $this->getChildren($options['where']['id']);
    //        //var_dump($children);exit();
    //        $children[] = $options['where']['id'];//把要删除的id添加到子类的数组中，方便下一步整体删除
    //        //再填回拼装成TP识别的数据形式：$options['where']['id']
    //        $options['where']['id'] = array(
    //            0=>'IN',
    //            1=>implode(',',$children)
    //        );
    //}

}