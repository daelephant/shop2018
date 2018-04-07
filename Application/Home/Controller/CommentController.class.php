<?php
namespace Home\Controller;
use Think\Controller;
class CommentController extends Controller {
    //AJAX发表评论
   public function add(){
       if(IS_POST){
            $model = D('Admin/Comment');
            if($model->create(I('post.'),1)){
                if($model->add()){
                    $this->success(array(
                        'face' => session('face'),
                        'username' => session('m_username'),
                        'addtime' => date('Y-m-d H:i:s'),
                        'content' => I('post.content'),
                        'star' => I('post.star'),
                    ),'',TRUE);//因为是AJAX处理，所以这里设置成返回TRUE
                    exit;
                }
            }
            $this->error($model->getError(),'',TRUE);
       }
   }

}