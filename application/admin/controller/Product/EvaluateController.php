<?php
// +----------------------------------------------------------------------
// |  [ 评价管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-04-11
// +----------------------------------------------------------------------
namespace app\admin\controller\Product;

use app\admin\ActionController;
use app\lib\Model;

class EvaluateController extends ActionController {

	
	public function __construct() {
        parent::__construct();
    }

    /**
     * [listAction 评价管理]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-11T14:16:21+0800
     * @return   [type]                   [description]
     */
    public function listAction(){

    	$where['businessid'] = $this->businessid; 

        $where = $this->searchWhere([
                "orderno"=>"=",
                "productname"=>"like",
                "frommembername"=>"like",
                "enable" => "=",
                "addtime" => "times",
            ],$where);
        
        //商品评价列表
        $list = Model::ins("ProEvaluate")->pageList($where,'*','id desc');
      
   
        //var_dump($list); exit();
        
        $viewData =[ 
               
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
            ];

        return $this->view($viewData);
    }

    /**
     * [complaintsAction 申述]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-11T14:41:40+0800
     * @return   [type]                   [description]
     */
    public function complaintsAction(){
    	$id = $this->getParam('id');
    	$viewData = [
    		'title'=>'申述评价',
    		'id'=>$id,
    		'action'=>'/Product/Evaluate/doComplaints'
    	];
    	return $this->view($viewData);
    }

    /**
     * [doComplaintsAction 添加申述内容]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-11T15:00:07+0800
     * @return   [type]                   [description]
     */
    public function doComplaintsAction(){
    	$id = $this->getParam('id');
    	$complaints = $this->getParam('complaints');;
    	if(empty($id))
    		$this->showError('请选择需要申述的评价');
        if(empty($complaints))
            $this->showError('申述内容不能为空');
    	$where['id'] = $id;

        $data = Model::ins("ProEvaluate")->getRow($where,'id');
        if(empty($data))
        	$this->showError('评价内容不存在');

        $updata = [
        	'complaints'=>$complaints,
        	'evaluateauto'=>3
        ];

        $update = Model::ins("ProEvaluate")->update($updata,['id'=>$id]);
        if($update > 0){
        	$this->showSuccess('成功申述');
        }else{
        	$this->showError('提交错误,请重新申述');
        }
    }

    /**
     * [replyAction 回复]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-02T17:22:20+0800
     * @return   [type]                   [description]
     */
    public function replyAction(){
        $id = $this->getParam('id');
        $viewData = [
            'title'=>'申述评价',
            'id'=>$id,
            'action'=>'/Product/Evaluate/doReply'
        ];
        return $this->view($viewData);
    }

    /**
     * [doReplyAction 回复动作]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-02T17:34:58+0800
     * @return   [type]                   [description]
     */
    public function doReplyAction(){
        $id = $this->getParam('id');
        $reply = $this->getParam('reply');
        if(empty($id))
            $this->showError('请选择需要回复的评价');
        if(empty($reply))
            $this->showError('回复内容不能为空');
        $where['id'] = $id;
        $topWhere['parentid'] = $id;
        $data = Model::ins("ProEvaluate")->getRow($where,'id,productid,productname,scores,isanonymous,frommemberid,frommembername,state');
       
        if(empty($data))
            $this->showError('评价内容不存在');
        $insert = [
            'productid'=>$data['productid'],
            'productname'=>$data['productname'],
            'scores' => $data['scores'],
            'isanonymous' => $data['isanonymous'],
            'frommemberid' => $this->businessid,
            'frommembername' => $data['frommembername'],
            'state' => $data['state'],
            'parentid' => $data['id'],
            'rootid' => $data['id'],
            'content' => $reply,
            'addtime' => date('Y-m-d H:i:s')
        ];
        $repData = Model::ins("ProEvaluate")->getRow($topWhere,'id');

        if(empty($repData)){
             Model::ins("ProEvaluate")->insert($insert);
            $this->showSuccess('回复成功');
        }else{
            $this->showError('该评论已回复');
        }
    }
}