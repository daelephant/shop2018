<?php
namespace Admin\Model;
use Think\Model;
class OrderModel extends Model
{
    //下单时允许表单的字段
	protected $insertFields = array('shr_name','shr_tel','shr_province','shr_city','shr_area','shr_address');
	//protected $updateFields = array('id','type_name');
    //下单时的表单验证规则
	protected $_validate = array(
		array('shr_name', 'require', '收货人姓名不能为空！', 1, 'regex', 3),
		array('shr_tel', 'require', '收货人电话不能为空！', 1, 'regex', 3),
		array('shr_province', 'require', '所在省份不能为空！', 1, 'regex', 3),
		array('shr_city', 'require', '所在城市不能为空！', 1, 'regex', 3),
		array('shr_area', 'require', '所在地区不能为空！', 1, 'regex', 3),
		array('shr_address', 'require', '详细地址不能为空！', 1, 'regex', 3),
	);
    protected function _before_insert(&$data, &$options)
    {
        $memberId = session('m_id');
        /*******下单前的检查****************/
        //是否登录
        if(!$memberId){
            $this->error = '必须先登录';
            return FALSE;
        }
        //检查购物车中是否有商品
        $cartModel = D('Cart');
        $options['goods'] = $goods = $cartModel->cartList();//获取购物车中的商品，并保存到$options中，这个$options会被传到 _after_insert中
        if(!$goods){
            $this->error = '购物车中没有商品，无法下单！';
            return FALSE;
        }

        //读库存之前加锁，注意：把锁赋值给这个模型，这样这个锁可以一直保存到下单结束，否则如果是局部变量这个锁在_before_insert函数执行完之后就释放了
        $this->fp = fopen('./order.lock');
        flock($this->fp,LOCK_EX);//设置为排它锁

        //循环购物车中的商品，检查库存量并且计算商品总价
        $gnModel = D('goods_number');
        $total_price = 0;//总价
        foreach ($goods as $k=>$v){
            //检查库存量
            $gnNumber = $gnModel->field('goods_number')->where(array(
                'goods_id' => $v['goods_id'],
                'goods_attr_id' => $v['goods_attr_id'],
            ))->find();
            if($gnNumber['goods_number']<$v['goods_number']){
                $this->error = '下单失败，原因：商品：<strong>'.$v['goods_name'].'</strong>库存量不足！';
                return FALSE;
            }
            //统计总数
            $total_price += $v['price'] * $v['goods_number'];
        }
        //把其他的信息补到订单中
        $data['total_price'] = $total_price;
        $data['member_id'] = $memberId;
        $data['addtime'] = time();

        //为了确定三张表的操作都能成功：订单基本信息表，订单商品表，库存量表
        $this->startTrans();
    }
    //订单基本信息生成之后，$data['id']就是新生成的订单的id
    protected function _after_insert($data, $options)
    {
       //从$option中取出购物车中的商品并循环插入到订单商品表中并且减少库存
        $ogModel = D('order_goods');
        $gnModel = D('goods_number');
        foreach ($options['goods'] as $k=>$v){
             $ret = $ogModel->add(array(
                 'order_id' => $data['id'],
                 'goods_id' => $v['goods_id'],
                 'goods_attr_id' => $v['goods_attr_id'],
                 'goods_number' => $v['goods_number'],
                 'price' => $v['price'],
             ));
             if(!$ret){
                 $this->rollback();
                 return FALSE;
             }
             //减库存
            $ret = $gnModel->where(array(
                'goods_id' => $v['goods_id'],
                'goods_attr_id' => $v['goods_attr_id'],
            ))->setDec('goods_number',$v['goods_number']);
             if(FALSE === $ret){
                 $this->rollback();
                 return FALSE;
             }
        }
        //所有操作都成功则提交事务
        $this->commit();

        //释放锁
        flock($this->fp,LOCK_UN);
        fclose($this->fp);

        //清空购物车
        $cartModel = D('Cart');
        $cartModel->clear();


    }
    /************************************ 其他方法 ********************************************/
}