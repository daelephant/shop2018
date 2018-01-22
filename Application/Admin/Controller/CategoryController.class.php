<?php
namespace Admin\Controller;
use Think\Controller;
class CategoryController extends Controller
{
	// 列表页
	// 商品列表页
	public function lst()
	{
		//$model = D('category');
		$model = new \Admin\Model\CategoryModel();
		// 返回数据和翻页
		//$data = $model->;

		//以下三种assign效果一样 ：
		// 第一种：
		$this->assign($data);

		// 第二种：
		//$this->assign('data', $data['data']);
		//$this->assign('page', $data['page']);

		// 第三种：
		//$this->assign(array(
		//	'data' => $data['data'],
		//	'page' => $data['page'],
		//));

		// 设置页面信息
		$this->assign(array(
			'_page_title' => '商品列表',
			'_page_btn_name' => '添加新商品',
			'_page_btn_link' => U('add'),
		));

		$this->display();
	}

}













