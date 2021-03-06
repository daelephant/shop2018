<?php
namespace Admin\Model;
use Think\Model;
class CommentModel extends Model
{
    //评论时允许提交的字段
	protected $insertFields = array('star','content','goods_id');
	//发表评论时表单验证规则
	protected $_validate = array(
		array('goods_id', 'require', '参数错误！', 1),
		array('star', '1,2,3,4,5', '分值只能是1-5之间的数字！', 1, 'in'),
		//第四个参数表示一定验证
		//第六个参数：代表规则什么时候生效，1：仅添加时生效，2：修改时生效，3：所有情况生效
		array('content','1,200','内容必须是1-200个字符！',1,'length'),
	);

	// 添加前
	protected function _before_insert(&$data, $option)
	{
	    $memberId = session('m_id');
	    if(!$memberId){
	        $this->error = '必须先登录';
	        return FALSE;
        }
        $data['member_id'] = $memberId;
	    $data['addtime'] = date('Y-m-d H:i:s');

	    /*******************处理印象数据*************************/
	    $yxId = I('post.yx_id');//选择的旧印象
	    $yxModel = D('Yinxiang');
	    $yxName = I('post.yx_name');
	    //处理选择的旧印象
        if($yxId){
            foreach ($yxId as $k => $v)
                $yxModel->where(array('id'=>$v))->setInc('yx_count');
        }

	    //处理新添加的印象
        if($yxName){
            //处理，为英文逗号
            $yxName = str_replace('，',',',$yxName);
            $yxName = explode(',',$yxName);
            foreach ($yxName as $k=>$v){
                $v = trim($v);//去掉左右空格
                if(empty($v))
                    continue;
                //先判断这个印象是否已经存在了
                $has = $yxModel->where(array(
                    'goods_id' => $data['goods_id'],
                    'yx_name' => $v,
                ))->find();
                if($has)
                    $yxModel->where(array(
                        'goods_id' => $data['goods_id'],
                        'yx_name' => $v,
                    ))->setInc('yx_count');
                else
                    $yxModel->add(array(
                        'goods_id' => $data['goods_id'],
                        'yx_name' => $v,
                        'yx_count' => 1,
                    ));

            }
        }

	}

	/************************************ 其他方法 ********************************************/
	//取出一件商品的评论
	public function search($goodsId,$pageSize = 5){
	    //这里因为要做AJAX翻页，所以需要自己做翻页不能用TP自带的，自带的不是AJAX,一点击就跳转了
        $where['a.goods_id'] = array('eq',$goodsId);

        //取出总记录数
        $count = $this->alias('a')->where($where)->count();
        //计算总的页数
        $pageCount = ceil($count/$pageSize);
        //获取当前页
        $currentPage = max(1,(int)I('get.p',1));//确保结果是大于等于1的整数
        //计算limit上的第一个参数：偏移量
        $offset = ($currentPage-1)*$pageSize;

        //如果是获取第一页的评论就把好评率和印象数据也取出来
        if($currentPage == 1){
            //好评率
            //取出所有的分值
            $stars = $this->field('star')->where(array(
                'goods_id' => array('eq',$goodsId),
            ))->select();
            //循环所有分值进行统计
            $hao = $zhong = $cha = 0;
            foreach ($stars as $k=>$v){
                if($v['star'] ==3)
                    $zhong++;
                elseif ($v['star'] >3)
                    $hao++;
                else
                    $cha++;
            }
            $total = $hao + $zhong + $cha;//总的评论数
            $hao = round(($hao / $total)*100,2);
            $zhong = round(($zhong / $total)*100,2);
            $cha = round(($cha / $total)*100,2);

            //取印象
            $yxModel = D('Yinxiang');
            $yxData = $yxModel->where(array(
                'goods_id' => array('eq',$goodsId),
            ))->select();

        }

        //取数据
        $data = $this->alias('a')
            ->field('a.id,a.content,a.addtime,a.star,a.click_count,b.face,b.username,COUNT(c.id) reply_count')
            ->join('LEFT JOIN __MEMBER__ b ON a.member_id=b.id
                    LEFT JOIN __COMMENT_REPLY__ c ON a.id=c.comment_id')
            ->where($where)
            ->order('a.id DESC')
            ->limit("$offset,$pageSize")
            ->group('a.id')
            ->select();

        //循环每个评论再取回复
        $crModel = D('comment_reply');
        foreach ($data as $k=>&$v){
           $v['reply'] = $crModel->alias('a')
                ->field('a.*,b.username,b.face')
                ->join('LEFT JOIN __MEMBER__ b ON a.member_id=b.id')
                ->where(array(
                    'a.comment_id' => $v['id'],
                ))
                ->order('a.id ASC')
                ->select();
        }


        return array(
            'data' => $data,
            'pageCount' => $pageCount,
            'hao' => $hao,
            'zhong' => $zhong,
            'cha' => $cha,
            'yxData' => $yxData,
            'memberId' => (int)session('m_id'),
        );
    }
}