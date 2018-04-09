<?php
namespace Home\Controller;
class SearchController extends NavController {
    //分类搜索
    public function cat_search(){
        $catId = I('get.cat_id');
        //先取出商品，然后再根据商品计算筛选条件
        $goodsModel = D('Admin/Goods');
        $data = $goodsModel->cat_search($catId);

        //取出筛选条件
        $catModel = D('Admin/Category');
        $searchFilter = $catModel->getSearchConditionByGoodsId($data['goods_id']);

        ////取出商品和翻页
        //$goodsModel = D('Admin/Goods');
        //$data = $goodsModel->cat_search($catId);

        //设置页面信息
        $this->assign(array(
            'page' => $data['page'],
            'data' => $data['data'],
            'searchFilter' => $searchFilter,
            '_page_title' => '分类搜索',
            '_page_keywords' => '分类搜索...',
            '_page_description' => '分类搜索...',
        ));
        $this->display();
    }

    //关键字搜索
    public function key_search(){
        $key = I('get.key');

        header('Content-Type:Text/html;charset=utf-8;');
        //搜索sphinx
        require('./sphinxapi.php');
        $sph = new \SphinxClient();
        $sph->SetServer('localhost',9312);
        //第一个参数：要查询的关键字
        //第一个参数：aphinx中索引的名字，默认是*，所有的索引
        $ret = $sph->Query($key,'goods');
        //提取出商品的id
        $ids = array_keys($ret['matches']);
        $gModel = D('Goods');
        $ret = $gModel->field('id,goods_name')->where(array(
            'id' => array('in',$ids),
        ))->select();
        dump($ret);

        //先取出商品，然后再根据商品计算筛选条件
        $goodsModel = D('Admin/Goods');
        $data = $goodsModel->key_search($key);

        //取出筛选条件
        $catModel = D('Admin/Category');
        $searchFilter = $catModel->getSearchConditionByGoodsId($data['goods_id']);

        ////取出商品和翻页
        //$goodsModel = D('Admin/Goods');
        //$data = $goodsModel->key_search($catId);

        //设置页面信息
        $this->assign(array(
            'page' => $data['page'],
            'data' => $data['data'],
            'searchFilter' => $searchFilter,
            '_page_title' => '关键字搜索',
            '_page_keywords' => '关键字搜索...',
            '_page_description' => '关键字搜索...',
        ));
        $this->display();
    }
}