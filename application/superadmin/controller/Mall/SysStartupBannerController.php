<?php
// +----------------------------------------------------------------------
// |  [ 启动页广告管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017-6-22 15:32:32}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Mall;
use app\superadmin\ActionController;
use app\lib\Db;
use app\lib\Model;
use \think\Config;

class SysStartupBannerController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }


    public function indexAction(){
        
        
        $skipurl = Config::get('skipurl');
        $where = $this->searchWhere([
                "bname"=>"like",
            ],$where);
        
        //print_r($where);
        $list  = Model::ins('SysStartupBanner')->pageList($where,'*','sort asc');

        $viewData =[ 
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
               	"skipurl" => $skipurl
            ];

        return $this->view($viewData);
    }

    /**
     * [addThumbAction 添加图片]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-22T15:49:58+0800
     */
    public function addBannerAction(){
        $skipurl = Config::get('skipurl');
        
        $action = '/Mall/SysStartupBanner/doaddOreditBanner';
        //form验证token
        $formtoken = $this->Btoken('Mall-SysStartupBanner-addBanner');
        $viewData = array(
                "title"=>"添加广告",
                'formtoken'=>$formtoken,
                'skipurl' => $skipurl,
                "action"=>$action
            );
        return $this->view($viewData);
    }   

    /**
     * [editBannerAction 修改图片]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-22T16:00:28+0800
     * @return   [type]                   [description]
     */
    public function editBannerAction(){
        
        $id = $this->getParam('id');
        $skipurl = Config::get('skipurl');
        $bannerData =  Model::ins('SysStartupBanner')->getRow(['id'=>$id]);

        $action = '/Mall/SysStartupBanner/doaddOreditBanner';
        //form验证token
        $formtoken = $this->Btoken('Mall-SysStartupBanner-editBanner');
        $viewData = array(
                "title"=>"添加广告",
                'formtoken'=>$formtoken,
                'skipurl' => $skipurl,
                "action"=>$action,
                "bannerData"=>$bannerData
            );
        return $this->view($viewData);
    }

    /**
     * [doaddOreditBanner description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-22T15:52:06+0800
     * @return   [type]                   [description]
     */
    public function doaddOreditBannerAction(){
        
        if($this->Ctoken()){

            $post = $this->params;
            $id = $post['id'];

           
            $post = Model::ins('SysStartupBanner')->_facade($post);

            if(empty($post['bname']))
                $this->showError('图片标题不能为空');
            if(empty($post['thumb'])) 
                $this->showError('图片不为空');    

            if(empty($id)){
                $bannerData = Model::ins('SysStartupBanner')->getRow(['bname'=>$post['bname']],'id');
                if(!empty($bannerData))
                    $this->showError('图片标题已存在');

                $post['addtime'] = date('Y-m-d H:i:s');
                $data = Model::ins('SysStartupBanner')->insert($post);  
            }else{
                $bannerData = Model::ins('SysStartupBanner')->getRow(['bname'=>$post['bname'],'id'=>['<>',$id]],'id');
                if(!empty($bannerData))
                    $this->showError('图片标题已存在');
                $data = Model::ins('SysStartupBanner')->update($post,['id'=>$id]);  
            }
          
           
            if($data > 0){
                $this->showSuccess('操作成功');
            }else{
                $this->showError('操作错误，请联系管理员');
            }
            

        }else{
            $this->showError('token错误，禁止操作');
        }
    }

    /**
     * [sortAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-06T13:08:26+0800
     * @return   [type]                   [description]
     */
    public function sortAction(){
        $ids = $this->getParam('id');
        $sort = $this->getParam('sort');
        $id_arr = explode(',', $ids);
        //print_r($id_arr);
        $sort_arr = explode(',', $sort);
        //print_r($sort_arr);
        foreach ($id_arr as $key => $value) {

           Model::ins('SysStartupBanner')->update(['sort'=> (int) $sort_arr[$key]],['id'=>$value]);
        
        }
        
        $this->showSuccess('成功排序');
    }


       /**
     * [delBrandAction 删除品牌]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-15T14:16:26+0800
     * @return   [type]                   [description]
     */
    public function delBannerAction(){

        $ids = $this->getParam('ids');

        if(empty($ids)){
            $this->showError('请选择需要删除的图片');
        }

        $ids = explode(',', $ids);
        $i = 0;
     
        //批量删除用户
        foreach ($ids as $value) {
            
            $bannerData = Model::ins('SysStartupBanner')->getRow(['id'=>$value],'id');

            if(empty($bannerData))
                $this->showError('需要删除的图片图片不存在');
          
            
            if(!empty($bannerData)){

                $goodsData = Model::ins('SysStartupBanner')->delete(['id'=>$value]);
           
                $i ++;
            }

        }
    
        $this->showSuccess('成功删除'.$i.'条记录');
    }

    /**
     * [enableBanner 启用]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-20T15:04:35+0800
     * @return   [type]                   [description]
     */
    public function enableBannerAction(){
        $id = $this->getParam('id');
        $enbale  = $this->getParam('enable');
      
        if($enbale == 1){
           
            Model::ins('SysStartupBanner')->update(['enable'=>-1],['enable'=>1]);
            Model::ins('SysStartupBanner')->update(['enable'=>1],['id'=>$id]);
        }else{
            Model::ins('SysStartupBanner')->update(['enable'=>-1],['id'=>$id]);
        }
       
        $this->showSuccess('操作成功');
    }

}