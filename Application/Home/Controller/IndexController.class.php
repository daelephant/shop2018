<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends NavController {
    //首页
    public function index(){
        //echo 'abbbbbbbbbbbbbbbbbabbbbbbbbbbbbb';
        //验证高并发雪崩问题
        $file = uniqid();//基于以微秒计的当前时间,以字符串形式返回唯一标识符。
        file_put_contents('./piao/'.$file,'abc1');//不存piao文件目录，需要手动创建，存在追加
        //每个页面都要用到导航条，写父类控制器
        //$catModel = D('Admin/Category');
        //$catData = $catModel->getNavData();

        //取出疯狂抢购的商品
        $goodsModel = D('Admin/Goods');
        $goods1 = $goodsModel->getPromoteGoods();//疯狂抢购，需要设置开始结束时间和价格
        //dump($goods1);

        $goods2 = $goodsModel->getRecGoods('is_new');//新品
        $goods3 = $goodsModel->getRecGoods('is_hot');//热卖
        $goods4 = $goodsModel->getRecGoods('is_best');//精品
        //取出首页楼层的数据
        $catModel = D('Admin/Category');
        $floorData = $catModel->floorData();
        //dump($floorData);
        //设置页面信息
        $this->assign(array(
            'goods1' => $goods1,
            'goods2' => $goods2,
            'goods3' => $goods3,
            'goods4' => $goods4,
            'floorData' => $floorData,
            '_show_nav' => 1,
            '_page_title' => '首页',
            '_page_keywords' => '首页',
            '_page_description' => '首页',
        ));
        $this->display();
    }
    //商品详情页
    public function goods(){
        //设置页面信息
        $this->assign(array(
            '_show_nav' => 0,
            '_page_title' => '商品详情页',
            '_page_keywords' => '特价商品详情页...',
            '_page_description' => '特卖商品详情页...',
        ));
        $this->display();
    }
}