<?php
// +----------------------------------------------------------------------
// |  [ 消息管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{datetime}}
// +----------------------------------------------------------------------

namespace app\superadmin\controller\System;
use app\superadmin\ActionController;
use app\lib\Db;
use app\lib\Model;

class SysmsgController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }


      /**
     * [indexAction 模型]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-14T21:21:02+0800
     * @return   [type]                   [description]
     */
    public function listAction(){  
        
       
        $where = $this->searchWhere([
                "title"=>"like",
                "addtime" => "times",
            ],$where);
        $sort = 'addtime desc';
        //商品列表
        $list = Model::ins("SysMsg")->pageList($where,'*',$sort);
     
       
        
        $viewData =[ 
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total']
            ];

        return $this->view($viewData);

    }


      /**
     * [delBrandAction 删除品牌]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-15T14:16:26+0800
     * @return   [type]                   [description]
     */
    public function deleteAction(){

        $ids = $this->getParam('ids');

        if(empty($ids)){
            $this->showError('请选择需要删除的消息');
        }

        $ids = explode(',', $ids);
        $i = 0;
        $errorName = '';
        //批量删除用户
        foreach ($ids as $value) {
        

            $goodsData = Model::ins('SysMsg')->delete(['id'=>$value]);

        
            $i ++;
      

        }

      	
        $this->showSuccess('成功删除'.$i.'条记录');
    }

    /**
     * [sendMsgAction 发送消息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-07T11:32:45+0800
     * @return   [type]                   [description]
     */
    public function sendMsgAction(){

        $action = '/System/Sysmsg/doSendMsg';
       
          //form验证token
        $formtoken = $this->Btoken('System-Sysmsg-sendMsg');

        $viewData =[ 
            "action"=>$action,
            'formtoken'=>$formtoken,

        ];

        return $this->view($viewData);
    }

    /**
     * [editSendMsgAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-07T13:55:22+0800
     * @return   [type]                   [description]
     */
    public function editSendMsgAction(){
        $id = $this->getParam('ids');

        $msg = Model::ins('SysMsg')->getRow(['id'=>$id]);
       

        $action = '/System/Sysmsg/doSendMsg';
        
          //form验证token
        $formtoken = $this->Btoken('System-Sysmsg-sendMsg');

        $viewData =[ 
            "action"=>$action,
            'formtoken'=>$formtoken,
            "msg" => $msg
        ];

        return $this->view($viewData);
    }

    public function doSendMsgAction(){

        // if($this->Ctoken()){
       
            $post = $this->params;
            $id = $post['id'];

            if(empty($post['title']))
                $this->showError('标题不能为空');
           
            $post = Model::ins('SysMsg')->_facade($post);
            //var_dump($post); exit();

            if(empty($post['urltype']))
                $post['urltype'] = 0;
                           
            if(empty($id)){
              
                $post['addtime'] = date('Y-m-d H:i:s');
                $data = Model::ins('SysMsg')->insert($post);  
            }else{
                $data = Model::ins('SysMsg')->update($post,['id'=>$id]);  
            }
          
           
            if($data > 0){
               //var_dump(Model::new("Msg.Msg")->sendSystemMsg(['id'=>$data]));
                 Model::new("Sys.Mq")->add([
                    // "url"=>"Msg.SendMsg.orderfahuo",
                    "url"=>"Msg.Msg.sendSystemMsg",
                    "param"=>[
                        "id"=>$data
                    ],
                ]);
                Model::new("Sys.Mq")->submit();
                //exit();
                $this->showSuccess('操作成功');
            }else{
                $this->showError('操作错误，请联系管理员');
            }
        

        // }else{
        //     $this->showError('token错误，禁止操作');
        // }
    }

    /**
     * [sendOneSystemMsg 单条逐个发送系统消息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-13T15:04:30+0800
     * @return   [type]                   [description]
     */
    public function sendOneSystemMsgAction(){
        // exit();
        // var_dump(Model::new("Msg.Msg")->sendOneSystemMsg());
        // exit();
        Model::new("Sys.Mq")->add([
            "url"=>"Msg.Msg.sendOneSystemMsg",
            "param"=>['ok'=>1],
        ]);
        print_r(Model::new("Sys.Mq")->submit());
      
        echo 'ok';
        exit();
    }

    /**
     * [toSendAction 广播消息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-07T14:05:08+0800
     * @return   [type]                   [description]
     */
    public function toSendAction(){
        
        $id = $this->getParam('ids');
        
        $id_arr = explode(',',$id);
       
        foreach ($id_arr as $key => $value) {
            //var_dump(Model::new("Msg.Msg")->sendSystemMsg(['id'=>$value]));
            if(!empty($value) && $value > 0){
           
                Model::new("Sys.Mq")->add([
                    // "url"=>"Msg.SendMsg.orderfahuo",
                    "url"=>"Msg.Msg.sendSystemMsg",
                    "param"=>[
                        "id"=>$value
                    ],
                ]);
                Model::new("Sys.Mq")->submit();
            }

        }
        $this->showSuccess('操作成功');
    }

}