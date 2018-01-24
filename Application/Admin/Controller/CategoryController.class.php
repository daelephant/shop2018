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
			'_page_title' => '分类列表',
			'_page_btn_name' => '添加新分类',
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
    public function add()
    {
        $model = new \Admin\Model\CategoryModel();
        if(IS_POST)
        {
            //$model = D('Brand');
            if($model->create(I('post.'), 1))
            {
                if($id = $model->add())
                {
                    $this->success('添加成功！', U('lst?p='.I('get.p')));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        //取出所有的分类做下拉框
        $catData = $model->getTree();

        // 设置页面中的信息
        $this->assign(array(
            'catData' => $catData,
            '_page_title' => '添加分类',
            '_page_btn_name' => '分类列表',
            '_page_btn_link' => U('lst'),
        ));
        $this->display();
    }
    public function edit()
    {
        $id = I('get.id');
        $model = new \Admin\Model\CategoryModel();//也可用TP的D放法自己的模型、M（）生产父类模型\Think\Model
        if(IS_POST)
        {
            //$model = D('Brand');
            if($model->create(I('post.'), 2))
            {
                if($model->save() !== FALSE)
                {
                    $this->success('修改成功！', U('lst', array('p' => I('get.p', 1))));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        //$model = new \Admin\Model\CategoryModel();
        $data = $model->find($id);

        //取出所有的分类做下拉框
        $catData = $model->getTree();
        //取出当前分类的子分类
        $children = $model->getChildren($id);

        $this->assign(array(
            'data' => $data,
            'catData' => $catData,
            'children' => $children
        ));

        // 设置页面中的信息
        $this->assign(array(
            '_page_title' => '修改分类',
            '_page_btn_name' => '分类列表',
            '_page_btn_link' => U('lst'),
        ));
        $this->display();
    }
}













