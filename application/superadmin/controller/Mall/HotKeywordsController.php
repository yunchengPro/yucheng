<?php
// +----------------------------------------------------------------------
// |  [ 热词管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017-5-25 14:17:02}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Mall;
use app\superadmin\ActionController;

use \think\Config;
use app\lib\Db;
use app\lib\Model;


class HotKeywordsController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [listAction 热词列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-25T14:18:34+0800
     * @return   [type]                   [description]
     */
    public function listAction(){
    	
        
        $where = [];      

        $where = $this->searchWhere([
                "name"=>"like"
            ],$where);

        $list = Model::ins("MallHotKeywords")->getList($where, '*', 'addtime asc');
     
        
        $viewData = array(
            "pagelist"=>$list, //列表数据
        );
      

        return $this->view($viewData);
    }

    /**
     * [addkeywordsAction 添加热词]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-25T14:29:39+0800
     * @return   [type]                   [description]
     */
    public function addkeywordsAction(){
    	$action = '/Mall/HotKeywords/doaddOreditKeywords';

        //form验证token
        $formtoken = $this->Btoken('Mall-HotKeywords-addkeywords');
        $viewData = array(
                "title"=>"添加热词",
                'formtoken'=>$formtoken,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [editkeywordsAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-25T15:20:45+0800
     * @return   [type]                   [description]
     */
    public function editkeywordsAction(){
        $action = '/Mall/HotKeywords/doaddOreditKeywords';
        //form验证token
        $id = $this->getParam('id') ;
        $formtoken = $this->Btoken('Mall-HotKeywords-editkeywords');
        $keywordsData = Model::ins("MallHotKeywords")->getRow(['id'=>$id], '*');
        $viewData = array(
                "title"=>"添加热词",
                'formtoken'=>$formtoken,
                "action"=>$action,
                "keywordsData" => $keywordsData
            );
        return $this->view($viewData);
    }

    /**
     * [doaddOreditKeywordsAction 添加或者修改热词]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-25T15:23:59+0800
     * @return   [type]                   [description]
     */
    public function doaddOreditKeywordsAction(){

        $post = $this->params;
        $id = $post['id'];
        if($this->Ctoken()){

           

            // if(empty($post['name']))
            //     $this->showError('输入框热词不能为空');

            // if(empty($post['keywords']))
            //     $this->showError('列表热词不能为空');

            if(empty($post['type']))
                $this->showError('请选择页面类型');


            $keywords_arr = [];
            $keywords_str = '';

            if(!empty($post['keywords'])){
                $keywords_arr = explode(',',$post['keywords']);
                //print_r($keywords_arr);
               
                if(!empty($keywords_arr) && is_array($keywords_arr)){

                    for($i=0;$i<10;$i++){
                        if(!empty($keywords_arr[$i]))
                            $keywords_str .= mb_substr($keywords_arr[$i],0,8,'utf-8') .',';
                    }
                }
            }

            $keywords_str  = rtrim($keywords_str,',');

            if(!empty($post['name']))
               $post['name'] =  mb_substr($post['name'],0,15,'utf-8');

            if($post['type'] == 1){
                if(empty($post['name'])){
                    $post['name'] = '店铺名称或分类、商圈';
                }
            }

            if($post['type'] == 2){
               if(empty($post['name'])){
                    $post['name'] = '商品名称或分类';
                } 
            }

            $data = [
                'name' => $post['name'],
                'keywords' => $keywords_str,
                'type' => $post['type'],
            ];
           
            if(empty($id)){
                $keywordsData = Model::ins("MallHotKeywords")->getRow(['type'=>$post['type']]);
                $typename = '';

                if($post['type'] == 1){
                    $typename = '牛店页面';
                }else if($post['type'] == 2){
                    $typename = '牛品页面';
                }

                if(!empty($keywordsData))
                    $this->showError($typename.'热词已存在，请编辑');
                $data['addtime'] = date('Y-m-d H:i:s');
                $ret  = Model::ins("MallHotKeywords")->insert($data);
            }else{
                $ret =  Model::ins("MallHotKeywords")->update($data,['id'=>$id]);
            }
            
            if($ret){
                $this->showSuccess('操作成功');
            }else{
                $this->showError('添加或修改错误，请重新操作');
            }

        }else{

            $this->showError('token错误，禁止操作');

        }
    }

    /**
     * [delkeywordsAction 刪除熱詞]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-08T20:47:54+0800
     * @return   [type]                   [description]
     */
    public function delkeywordsAction(){
        $ids = $this->getParam('ids');

        if(empty($ids)){
            $this->showError('请选择需要删除的热词');
        }

        $id_arr = explode(',', $ids);
        $i = 0;
       
        //批量删除用户
        foreach ($id_arr as $value) {
        
           
            Model::ins("MallHotKeywords")->delete(['id'=>$value]);
            $i ++;
            

        }
      
        $this->showSuccess('成功删除'.$i.'条记录');
    }
}