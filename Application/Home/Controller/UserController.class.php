<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
    //qq登录的回调地址
    public function callback(){
        require_once("./Connectqq/API/qqConnectAPI.php");
        $qc = new \QC();
        $model = D('Admin/Member');
        //获取access token
        $access_token =  $qc->qq_callback();
        //获取openid
        $openid = $qc->get_openid();
        //重新实例化QC，传递$access_token，$openid
        $qc = new \QC($access_token,$openid);
        //获取用户基本信息
        $rst = $qc -> get_user_info();
        //var_dump($rst);
        //查询当前openid是否在数据表中存在
        $exist = $model -> where("openid = '$openid'") -> find();
        //判断是否存在
        if($exist){
            //这个用户是老用户
            session('m_id',$exist['id']);
            session('m_username',$exist['username']);
        }else{
            //新用户
            //记录字段	openid、nickname（user_name）、gender（user_sex）
            $data['openid'] = $openid;
            $data['username'] = $rst['nickname'];
            $data['user_sex'] = $rst['gender'];
            //写入数据表$model = D('Admin/Member');
            $uid = $model -> add($data);
            //持久化处理
            session('m_id',$openid);
            session('m_username',$rst['nickname']);
        }
        echo "<script>opener.location='/';window.close();</script>";
    }

}