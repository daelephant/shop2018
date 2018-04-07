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