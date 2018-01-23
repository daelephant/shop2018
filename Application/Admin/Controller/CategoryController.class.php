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
		$data = $model->getTree();

		//以下三种assign效果一样 ：
		// 第一种：
		//$this->assign($data);

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
		    'data' => $data,
			'_page_title' => '商品列表',
			'_page_btn_name' => '添加新商品',
			'_page_btn_link' => U('add'),
		));

		$this->display();
	}
	public function delete(){
	    $model = new \Admin\Model\CategoryModel();
	    //调用，模型的delete方法
	    if(FALSE !== $model->delete(I('get.id')))
	        $this->success('删除成功！',U('lst'));
	    else
	        $this->error('删除失败！原因：'.$model->getError());
    }

}













