<?php
// +----------------------------------------------------------------------
// |  [ 招聘管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年8月8日15:16:08}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Company;
use app\superadmin\ActionController;

use app\lib\Db;
use app\model\User\AmountModel;
use app\lib\Model;

class CareersController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * [careerslistAction 招聘列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-08T15:26:06+0800
     * @return   [type]                   [description]
     */
    public function careerslistAction(){

    	$where['isdelete'] = 0;
    	$where = $this->searchWhere([
                "position"=>"like"
            ],$where);
        $sort = ' sort desc,addtime desc';
       
        $list = Model::ins("ComCareers")->pageList($where,'*',$sort);

        $viewData =[ 
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'],
             
            ];
        return $this->view($viewData);
    }

    /**
     * [addcareersAction 添加招聘]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-08T15:27:32+0800
     * @return   [type]                   [description]
     */
    public function addcareersAction(){

    	$action = '/Company/Careers/doaddoreditcareers';

       
        $formtoken = $this->Btoken('Company-Careers-addcareers');
    	$viewData = [
    		'formtoken'=>$formtoken,
    		'action' => $action 
    	];
    	return $this->view($viewData);
    }

    /**
     * [editcareersAction 修改招聘]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-08T15:28:03+0800
     * @return   [type]                   [description]
     */
    public function editcareersAction(){
    	$id = $this->getParam('id');
    	$action = '/Company/Careers/doaddoreditcareers';

       	$careers = Model::ins("ComCareers")->getRow(['id'=>$id]);
        $formtoken = $this->Btoken('Company-Careers-editcareers');
    	$viewData = [
    		'formtoken'=>$formtoken,
    		'action' => $action,
    		'careers' => $careers
    	];
    	return $this->view($viewData);
    }

    /**
     * [doaddoreditcareers 添加修改招聘信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-08T15:31:21+0800
     * @return   [type]                   [description]
     */
    public function doaddoreditcareersAction(){
    	if($this->Ctoken()){
    		
    		$post = $this->params;
    		$id = $post['id'];

    		if(empty($post['position']))
    			$this->showError('招聘职位不能为空');

    		if(empty($id)){
    			//分类名称不能重复
    			$careers = Model::ins('ComCareers')->getRow(['position'=>$post['position']],'id');
    			if(!empty($careers['id']))
    				$this->showError($post['position'].'的职位已存在');
    			$insert = [
    				'position' => $post['position'],
    				'position_en' => $post['position_en'],
    				'responsibility' => $post['responsibility'],
    				'requirement' => $post['requirement'],
    				'sort' => empty($post['sort']) ? 0 : $post['sort'],
    				'addtime' => date('Y-m-d H:i:s')
    			];
    			print_r($insert);

    			$ret = Model::ins('ComCareers')->insert($insert);

    		}else{
    		
    			$hascareers = Model::ins('ComCareers')->getRow(['id'=>$id],'id,isdelete');
    			if(empty($hascareers['id']) || $hascareers['isdelete'] == 1)
    				$this->showError('招聘职位不存在或已被删除');
    			//分类名称不能重复
    			$category = Model::ins('ComCareers')->getRow(['position'=>$post['position'],'id'=>['<>',$id]],'id');
    			if(!empty($category['id']))
    				$this->showError($post['position'].'的职位已存在');

    			$updata = [
    				'position' => $post['position'],
    				'position_en' => $post['position_en'],
    				'responsibility' => $post['responsibility'],
    				'requirement' => $post['requirement'],
    				'sort' => empty($post['sort']) ? 0 : $post['sort']
    			];
    			$ret = Model::ins('ComCareers')->update($updata,['id'=>$id]);
    		}

    		if($ret > 0){
    			$this->showSuccess('操作成功');
    		}else{
    			$this->showError('操作错误请重新添加');
    		}
    	}else{
            $this->showError('token错误，禁止操作');
        }
    }

    /**
     * [sortcareersAction 排序]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-08T16:04:02+0800
     * @return   [type]                   [description]
     */
    public function sortcareersAction(){
    	$ids = $this->getParam('id');
        $sort = $this->getParam('sort');
        $id_arr = explode(',', $ids);
        //print_r($id_arr);
        $sort_arr = explode(',', $sort);
        //print_r($sort_arr);
        foreach ($id_arr as $key => $value) {

           Model::ins('ComCareers')->update(['sort'=> (int) $sort_arr[$key]],['id'=>$value]);
        
        }
        
        $this->showSuccess('成功排序');
    }

    /**
     * [delcareersAction 删除]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-08T16:04:54+0800
     * @return   [type]                   [description]
     */
    public function delcareersAction(){
    	$ids = $this->getParam('ids');
        $id_arr = explode(',', $ids);

        foreach ($id_arr as $key => $value) {
            $row = Model::ins('ComCareers')->getRow(['id'=>$value],'id,isdelete');
            if(empty($row['id']) || $row['isdelete'] == 1){
                $this->showError('id为'.$value.'的招聘不存在或已经被删除');
            }
            Model::ins('ComCareers')->update(['isdelete'=> 1],['id'=>$value]);
        }
        $this->showSuccess('成功删除');
    }
}