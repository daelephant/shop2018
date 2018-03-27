<?php
namespace Home\Controller;
use Think\Controller;
class CartController extends Controller {
   public function add(){
       if(IS_POST){
           //dump($_POST);exit;ok
           $cartModel = D('Cart');
           if($cartModel->create(I('post.'),1)){
               if($cartModel->add()){
                   $this->success('添加成功！',U('lst'));
                   exit;
               }
           }
           $this->error('添加失败，原因：'.$cartModel->getError());
       }
   }
   public function lst(){
       dump($_COOKIE);
   }
}