<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends NavController {
    //处理浏览历史
    public function displayHistory(){
        $id = I('get.id');
        //$id =14;ok//[{"id":"14","mid_logo":"Goods\/2018-02-25\/thumb_2_5a926a1205683.png","goods_name":"mix2s"}]
        //先从COOKIE中取出浏览历史的ID数组
        $data = isset($_COOKIE['display_history'])?unserialize($_COOKIE['display_history']):array();
        //把最新浏览的这件商品放到放到数组中的第一个位置上
        array_unshift($data,$id);
        //去重
        $data = array_unique($data);
        //只取数组中前6个
        if(count($data)>6)
            $data = array_slice($data,0,6);
        //数组存回COOKIE
        setcookie('display_history',serialize($data),time()+30*86400,'/');
        //再根据商品ID取出商品详细信息
        $goodsModel = D('Goods');
        $data = implode(',',$data);
        $data = trim($data,',');
        $gData = $goodsModel->field('id,mid_logo,goods_name')->where(array(
            'id' => array('in',$data),
            'is_on_sale' => array('eq','是'),
        ))->order("FIELD(id,$data)")->select();
        echo json_encode($gData);
        //ajax中单元测试，bug检查
        $sql = $goodsModel->getLastSql();
        $gDatatxt=json_encode($gData);
        file_put_contents('./ajax.txt',"$gDatatxt");
    }

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
        //接受商品的ID
        $id = I('get.id');
        //根据ID取出商品的详细信息
        $gModel = D('Goods');
        $info = $gModel->find($id);
        //再根据主分类ID找出这个分类所有上级分类制作导航
        $catModel = D('Admin/Category');
        $catPath = $catModel->parentPath($info['cat_id']);
        //dump($catPath);

        //取出商品相册
        $gpModel = D('goods_pic');
        $gpData = $gpModel->where(array(
            'goods_id' => array('eq',$id),
        ))->select();
        //解决乱码问题
        header('Content-Type:text/html;chartset=utf-8');
        //取出这件商品所有的属性
        $gaModel = D('goods_attr');
        $gaData = $gaModel->alias('a')
            ->field('a.*,b.attr_name,b.attr_type')
            ->join('LEFT JOIN __ATTRIBUTE__ b ON a.attr_id=b.id')
            ->where(array(
                'a.goods_id' => array('eq',$id),
            ))
            ->select();
        //dump($gaData);
        //整理所有的商品，把唯一的和可选的属性分开存放
        $uniArr = array();//唯一属性
        $mulArr = array();//可选属性
        foreach ($gaData as $k=>$v){
            if ($v['attr_type'] == '唯一')
                $uniArr[] = $v;
            else
                //把同一个属性放到一起 -》 三维数组
                $mulArr[$v['attr_name']][] = $v;
        }
        //dump($mulArr);
        //取出这件商品所有的会员价格
        $mpModel = D('member_price');
        $mpData = $mpModel->alias('a')
            ->field('a.price,b.level_name')
            ->join('LEFT JOIN __MEMBER_LEVEL__ b on a.level_id=b.id')
            ->where(array(
                'a.goods_id'=>array('eq',$id)
            ))
            ->select();
        //dump($mpData);

        $viewPath = C('IMAGE_CONFIG');
        //dump($viewPath);

        $this->assign(array(
            'info' => $info,
            'catPath' => $catPath,
            'gpData' => $gpData,
            'uniArr' => $uniArr,
            'mulArr' => $mulArr,
            'mpData' => $mpData,
            'viewPath' => $viewPath['viewPath'],
        ));

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