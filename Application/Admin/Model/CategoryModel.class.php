<?php
namespace Admin\Model;
use Think\Model;
class CategoryModel extends Model
{
	protected $insertFields = array('cat_name','parent_id','is_floor');
	protected $updateFields = array('id','cat_name','parent_id','is_floor');
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

    /**
     * 前台：获取导航条上的数据
     *
     */
    public function getNavData(){
        //先从缓存中取出数据
        $catData = S('catData');
        //判断如果没有缓存或者缓存过期就重新构造数组
        if(!$catData){
            //取出所有的分类
            $all = $this->select();
            $ret = array();//一维数组是顶级分类
            //循环出所有的分类找出顶级分类
            foreach ($all as $k=>$v){
                if ($v['parent_id'] == 0){
                    //循环出所有的分类，找出这个顶级分类的子分类
                    foreach ($all as $k1=>$v1){
                        if ($v1['parent_id'] == $v['id']){
                            //循环所有的分类找出这个二级分类的子分类
                            foreach ($all as $k2=>$v2){
                                if ($v2['parent_id'] == $v1['id']){
                                    $v1['children'][] = $v2;
                                }
                            }
                            $v['children'][] = $v1;
                        }
                    }
                    $ret[] = $v;
                }
            }
            //把数组缓存一天
            S('catData',$ret,56400);
            return $ret;
        }else{
            return $catData;//有缓存直接返回缓存数据
        }
    }

    /**
     * 取出一个分类所有上级分类
     */
    public function parentPath($catId){
        static $ret = array();//递归函数中。请记住static的作用：仅在第一次调用函数的时候对变量进行初始化，并且保留变量值。其后每一次执行完都会保留 $ret 的值,不再进行初始化，相当于直接忽略了 static $ret = array(); 这一句。
        $info = $this->field('id,cat_name,parent_id')->find($catId);
        $ret[] = $info;
        //如果还有上级再取上级信息
        if($info['parent_id'] > 0)
            $this->parentPath($info['parent_id']);
        return $ret;
        //dump($ret);exit();

    }

    /**
     * 获取前台首页楼层中的数据
     */
    public function floorData(){
        $floorData = S('floorData');
        if($floorData)
            return $floorData;
        else{
            //先取出推荐到楼层的顶级分类
            $ret = $this->where(array(
                'parent_id' => array('eq',0),
                'is_floor' => array('eq','是'),
            ))->select();
            $goodsModel = D('Admin/Goods');
            foreach ($ret as $k=>$v){
                /******            这个楼层中的品牌数据************/
                //先取出这个楼层下所有的商品ID
                $goodsId = $goodsModel->getGoodsIdByCatId($v['id']);
                //再取出这些商品所用到的品牌
                $ret[$k]['brand'] = $goodsModel->alias('a')
                    ->join('LEFT JOIN __BRAND__ b ON a.brand_id=b.id')
                    ->field('DISTINCT brand_id,b.brand_name,b.logo')
                    ->where(array(
                        'a.id' => array('in',$goodsId),
                        'a.brand_id' => array('neq',0),
                    ))->limit(9)->select();

                /*取出未推荐的二级分类并保存到这个顶级分类的subCat字段中************/
                $ret[$k]['subCat'] =$this->where(array(
                    'parent_id' => array('eq',$v['id']),
                    'is_floor' => array('eq','否'),
                ))->select();
                /*取出推荐的二级分类并保存到这个顶级分类的recSubCat字段中************/
                $ret[$k]['recSubCat'] =$this->where(array(
                    'parent_id' => array('eq',$v['id']),
                    'is_floor' => array('eq','是'),
                ))->select();
                /**       循环每个推荐的二级分类取出分类下的8件被推荐到楼层的商品   ***/
                foreach ($ret[$k]['recSubCat'] as $k1=>&$v1){
                    //取出这个分类下所有商品的Id并返回一维数组
                    $gids = $goodsModel->getGoodsIdByCatId($v1['id']);
                    //再根据商品ID取出商品的详细信息
                    $v1['goods'] = $goodsModel->field('id,mid_logo,goods_name,shop_price')->where(array(
                        'is_on_sale' => array('eq','是'),
                        'is_floor' => array('eq','是'),
                        'id' =>array('in',$gids),
                    ))->order('sort_num ASC')->limit(8)->select();
                }
            }
            S('floorData',$ret,86400);
            return $ret;
        }
    }

}