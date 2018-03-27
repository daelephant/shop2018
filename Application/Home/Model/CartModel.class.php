<?php
/**
 * Created by PhpStorm.
 * User: cyx
 * Date: 2018-03-27
 * Time: 15:20
 */
namespace Home\Model;
use Think\Model;
class CartModel extends Model{
    //添加购物车时允许接收的字段
    protected $insertFields = 'goods_id,goods_attr_id,goods_number';
    //加入购物车时的表单验证规则
    protected $_validate = array(
        array('goods_id','require','必须选择商品',1),
        array('goods_number','chkGoodsNumber','库存量不足',1,'callback'),
    );
    //检查库存量
    public function chkGoodsNumber($goodsNumber){
        //选择的商品属性id
        $gaid = I('post.goods_attr_id');
        sort($gaid,SORT_NUMERIC);//以数字的方式升序排序。
        $gaid = implode(',',$gaid);
        //取出库存量
        $gnModel = D('goods_number');
        $gn = $gnModel->field('goods_number')->where(array(
            'goods_id' => I('post.goods_id'),
            'goods_attr_id' => $gaid,
        ))->find();
        //返回库存量是否够
        return ($gn['goods_number'] >= $goodsNumber);
    }
    //如果没登陆，需要插入到COOKIE,父类的add是直接插入到数据库的。
    //所以要重写父类的add方法：判断如果没有登陆是存COOKIE，否则存数据库
    public function add(){
        $memberId = session('m_id');
        //先把商品属性id升序并转化成字符串
        sort($this->goods_attr_id,SORT_NUMERIC);//sort — 对数组排序
        $this->goods_attr_id = implode(',',$this->goods_attr_id);
        //判断有没有登录
        if($memberId){
            $goodsNumber = $this->goods_number;//表单数据已经接收到模型中了。先把表单中的库存量存到这个变量中，否则调用find之后就没有了
            //从数据库中取出数据，并保存到模型中【覆盖原数据】
            $has = $this->field('id')->where(array(
                'member_id' => $memberId,
                'goods_id' => $this->goods_id,
                'goods_attr_id' => $this->goods_attr_id,
            ))->find();
            //如果购物车已经有这个商品就在原数量上加上这次购买的数量
            if($has){
                $this->where(array(
                    'id' => array('eq',$has['id']),
                ))->setInc('goods_number',$goodsNumber);
            }else{
                //如果调用父类的add方法，父类模型去操作数据库
                parent::add(array(
                    'member_id' => $memberId,
                    'goods_id' => $this->goods_id,
                    'goods_attr_id' => $this->goods_attr_id,
                    'goods_number' => $this->goods_number,
                ));
            }
        } else{
            //未登录的时候就插入cookie里
            //从COOKIE先取出购物车的一维数组
            $cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();//如果COOKIE有的话就反序列化成数组，如果没有则赋值空一维数组。
            //先拼一个下标
            $key = $this->goods_id.'-'.$this->goods_attr_id;
            //如果添加过了购物车，再次添加，则需要把数量加起来
            if(isset($cart[$key]))
                $cart[$key] += $this->goods_number;
            else
                $cart[$key] = $this->goods_number; //把商品加入
            //把一维数组(需要序列化后才能存COOKIE)存回到COOKIE,过期时间一个月
            setcookie('cart',serialize($cart),time()+30*86400,'/');
        }
        return TRUE;
    }
}
