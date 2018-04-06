<?php
namespace Home\Controller;
class SearchController extends NavController {
    //分类搜索
    public function cat_search(){
        $catId = I('get.cat_id');
        //取出筛选条件
        $catModel = D('Admin/Category');
        $searchFilter = $catModel->getSearchConditionByCatId($catId);

        //取出商品和翻页
        $goodsModel = D('Admin/Goods');
        $data = $goodsModel->cat_search($catId);

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
}