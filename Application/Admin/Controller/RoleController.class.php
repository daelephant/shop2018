<?php
namespace Admin\Controller;
class RoleController extends BaseController
{
    public function add()
    {
    	if(IS_POST)
    	{
    	    //dump($_POST);exit;
    		$model = D('Role');
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
        //取出所有的权限
        $priModel = D('privilege');
    	$priData = $priModel->getTree();
    	//dump($priData);exit;
		// 设置页面中的信息
		$this->assign(array(
		    'priData' => $priData,
			'_page_title' => '添加角色',
			'_page_btn_name' => '角色列表',
			'_page_btn_link' => U('lst'),
		));
		$this->display();
    }
    public function edit()
    {
    	$id = I('get.id');
    	if(IS_POST)
    	{
    		$model = D('Role');
    		//// 指定更新数据操作状态Model::MODEL_UPDATE（或者2）当没有指定的时候，系统根据数据源是否包含主键数据来自动判断
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
    	$model = M('Role');
    	$data = $model->find($id);
    	$this->assign('data', $data);

        //取出所有的权限
        $priModel = D('privilege');
        $priData = $priModel->getTree();

        //取出当前角色已经拥有的权限ID
        $rpModel = D('role_pri');
        $rpData = $rpModel->field('GROUP_CONCAT(pri_id) pri_id')->where(array(
            'role_id'=>array('eq',$id),
        ))->find();
        //dump($rpData);exit;array(1) ["pri_id"] => string(89) "1,2,3,4,31,32,5,6,7,8,9,23,24,25,26,27,28,29,30,38,10,11,12,13,14,15,16,17,18,19,20,21,22"
		// 设置页面中的信息
		$this->assign(array(
		    'rpData' => $rpData['pri_id'],
		    'priData' => $priData,
			'_page_title' => '修改角色',
			'_page_btn_name' => '角色列表',
			'_page_btn_link' => U('lst'),
		));
		$this->display();
    }
    public function delete()
    {
    	$model = D('Role');
    	if($model->delete(I('get.id', 0)) !== FALSE)
    	{
    		$this->success('删除成功！', U('lst', array('p' => I('get.p', 1))));
    		exit;
    	}
    	else 
    	{
    		$this->error($model->getError());
    	}
    }
    public function lst()
    {
    	$model = D('Role');
    	$data = $model->search();
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
    	));

		// 设置页面中的信息
		$this->assign(array(
			'_page_title' => '角色列表',
			'_page_btn_name' => '添加角色',
			'_page_btn_link' => U('add'),
		));
    	$this->display();
    }
}