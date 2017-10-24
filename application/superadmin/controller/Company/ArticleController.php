<?php
// +----------------------------------------------------------------------
// |  [ 公司新闻管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年8月4日14:42:13}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Company;
use app\superadmin\ActionController;

use app\lib\Db;
use app\model\User\AmountModel;
use app\lib\Model;

class ArticleController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * [categorylistAction 分类列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-04T14:43:52+0800
     * @return   [type]                   [description]
     */
    public function categorylistAction(){

    	if(empty($this->params['categoryname']))
    		$where['parentid'] = 0;

    	$where['isdelete'] = 0;
    	$where = $this->searchWhere([
                "categoryname"=>"like",
            ],$where);
        $sort = ' sort asc,id asc';
        $pagelist = [];
        $list = Model::ins("ComNewsCategory")->getList($where,'*',$sort);

        foreach ($list as $key => $value) {
        	$value['parentid'] = '无';
        	

        	if(empty($value['category_icon']))
        		$value['category_icon'] = '无';

        	$pagelist[$value['id']] = $value;

        	$soncate = Model::ins("ComNewsCategory")->getList(['parentid'=>$value['id']]);

        	foreach ($soncate as $sonk => $sonv) {

        		if(empty($sonv['category_icon']))
        			$sonv['category_icon'] = '无';

        		$sonv['parentid'] = $value['categoryname'];
        		$sonv['categoryname'] = '&nbsp;&nbsp;&nbsp;&nbsp;|__' . $sonv['categoryname'];
        		$pagelist[$sonv['id']] = $sonv;
        	}
        }

        $viewData =[ 
                "pagelist"=>$pagelist, //列表数据
                //"total"=>$list['total']
            ];
        return $this->view($viewData);
    }

    /**
     * [addcategoryAction 添加分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-04T14:56:23+0800
     * @return   [type]                   [description]
     */
    public function addcategoryAction(){

    	$action = '/Company/Article/doaddoreditcategory';

        $topcate = Model::ins('ComNewsCategory')->getList(['parentid'=>0,'isdelete'=>0],'id,categoryname');

        foreach ($topcate as $key => $value) {
        	$soncate = Model::ins('ComNewsCategory')->getList(['parentid'=>$value['id']],'id,categoryname');
        	foreach ($soncate as $sonk => $sonv) {
        		$soncate[$sonk]['categoryname'] = '&nbsp;&nbsp;&nbsp;&nbsp;|__'.$sonv['categoryname'];
        	}
        	$topcate[$key]['soncate'] = $soncate;
        }

        $topCategory = [];
      
        foreach ($topcate as $key => $value) {
        	$topCategory[$value['id']] = $value['categoryname'];
        	foreach ($value['soncate'] as $sonk => $sonv) {
        		$topCategory[$sonv['id']] = $sonv['categoryname'];
        	}
        }

        $formtoken = $this->Btoken('Company-Article-addcategory');
    	$viewData = [
    		'formtoken'=>$formtoken,
    		'action' => $action ,
    		'topCategory' => $topCategory
    	];
    	return $this->view($viewData);
    }

    /**
     * [editcategoryAction 修改分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-04T15:00:30+0800
     * @return   [type]                   [description]
     */
    public function editcategoryAction(){
    	$id = $this->getParam('id');
    	$action = '/Company/Article/doaddoreditcategory';
        
        $topcate = Model::ins('ComNewsCategory')->getList(['parentid'=>0,'isdelete'=>0],'id,categoryname');

        foreach ($topcate as $key => $value) {
        	$soncate = Model::ins('ComNewsCategory')->getList(['parentid'=>$value['id']],'id,categoryname');
        	foreach ($soncate as $sonk => $sonv) {
        		$soncate[$sonk]['categoryname'] = '&nbsp;&nbsp;&nbsp;&nbsp;|__'.$sonv['categoryname'];
        	}
        	$topcate[$key]['soncate'] = $soncate;
        }

        $topCategory = [];
      
        foreach ($topcate as $key => $value) {
        	$topCategory[$value['id']] = $value['categoryname'];
        	foreach ($value['soncate'] as $sonk => $sonv) {
        		$topCategory[$sonv['id']] = $sonv['categoryname'];
        	}
        }

        $formtoken = $this->Btoken('Company-Article-editcategory');
        $Category = Model::ins('ComNewsCategory')->getRow(['id'=>$id]);
    	$viewData = [
    		'formtoken'=>$formtoken,
    		'action' => $action ,
    		'Category'=>$Category,
    		'topCategory'=>$topCategory
    	];
    	return $this->view($viewData);
    }

    /**
     * [doaddoreditcategoryAction 添加或修改分类动作]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-04T14:58:14+0800
     * @return   [type]                   [description]
     */
    public function doaddoreditcategoryAction(){
    	
    	if($this->Ctoken()){
    		
    		$post = $this->params;
    		$id = $post['id'];

    		if(empty($post['categoryname']))
    			$this->showError('分类名称不能为空');

    		if(empty($id)){
    			//分类名称不能重复
    			$category = Model::ins('ComNewsCategory')->getRow(['categoryname'=>$post['categoryname'],'parentid'=>$post['parentid']],'id');
    			if(!empty($category['id']))
    				$this->showError($post['categoryname'].'的分类已存在');
    			$insert = [
    				'parentid' => empty($post['parentid']) ? 0 : $post['parentid'],
    				'categoryname' => $post['categoryname'],
    				'category_icon' => $post['category_icon'],
    				'sort' => empty($post['sort']) ? 0 : $post['sort']
    			];
    			$ret = Model::ins('ComNewsCategory')->insert($insert);

    		}else{
    			if($id == $post['parentid'])
    				$this->showError('不能选择自己为上级分类');
    			//分类是否存在
    			$hascate = Model::ins('ComNewsCategory')->getRow(['id'=>$id],'id,isdelete');
    			if(empty($hascate['id']) || $hascate['isdelete'] == 1)
    				$this->showError('分类不存在或已被删除');
    			//分类名称不能重复
    			$category = Model::ins('ComNewsCategory')->getRow(['categoryname'=>$post['categoryname'],'id'=>['<>',$id],'parentid'=>$post['parentid']],'id');
    			if(!empty($category['id']))
    				$this->showError($post['categoryname'].'的分类已存在');

    			$updata = [
    				'parentid' => empty($post['parentid']) ? 0 : $post['parentid'],
    				'categoryname' => $post['categoryname'],
    				'category_icon' => $post['category_icon'],
    				'sort' => empty($post['sort']) ? 0 : $post['sort']
    			];
    			$ret = Model::ins('ComNewsCategory')->update($updata,['id'=>$id]);
    		}
    	
    		if($ret > 0){
    			$this->showSuccess('添加成功');
    		}else{
    			$this->showError('操作错误请重新添加');
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
    public function sortcategoryAction(){
        $ids = $this->getParam('id');
        $sort = $this->getParam('sort');
        $id_arr = explode(',', $ids);
        //print_r($id_arr);
        $sort_arr = explode(',', $sort);
        //print_r($sort_arr);
        foreach ($id_arr as $key => $value) {

           Model::ins('ComNewsCategory')->update(['sort'=> (int) $sort_arr[$key]],['id'=>$value]);
        
        }
        
        $this->showSuccess('成功排序');
    }

    /**
     * [delcategoryAction 删除分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-04T17:26:25+0800
     * @return   [type]                   [description]
     */
    public function delcategoryAction(){
    	$ids = $this->getParam('ids');
    	$id_arr = explode(',', $ids);

    	foreach ($id_arr as $key => $value) {
            $arc = Model::ins('ComNewsArticle')->getRow(['categoryid'=>$value],'count(*) as count');
            if($arc['count'] > 0)
                $this->showError('当前分类已经发布过文章，请先删除该分类里文章');
            $row = Model::ins('ComNewsCategory')->getRow(['id'=>$value],'id,isdelete');
            if(empty($row['id']) || $row['isdelete'] == 1){
                $this->showError('id为'.$value.'的分类不存在或已经被删除');
            }
    		Model::ins('ComNewsCategory')->update(['isdelete'=> 1],['id'=>$value]);
    	}
    	$this->showSuccess('成功删除');
    }


    /**
     * [articlelistAction 文章列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-04T17:04:48+0800
     * @return   [type]                   [description]
     */
    public function articlelistAction(){


    	$where['isdelete'] = 0;
    	$where = $this->searchWhere([
                "title"=>"like",
                "categoryid"=>"="
            ],$where);
        $sort = ' sort desc,addtime desc';
       
        $list = Model::ins("ComNewsArticle")->pageList($where,'*',$sort);

        $topcate = Model::ins('ComNewsCategory')->getList(['parentid'=>0,'isdelete'=>0],'id,categoryname');

        foreach ($topcate as $key => $value) {
        	$soncate = Model::ins('ComNewsCategory')->getList(['parentid'=>$value['id']],'id,categoryname');
        	foreach ($soncate as $sonk => $sonv) {
        		$soncate[$sonk]['categoryname'] = '&nbsp;&nbsp;&nbsp;&nbsp;|__'.$sonv['categoryname'];
        	}
        	$topcate[$key]['soncate'] = $soncate;
        }

        $topCategory = [];
      
        foreach ($topcate as $key => $value) {
        	$topCategory[$value['id']] = $value['categoryname'];
        	foreach ($value['soncate'] as $sonk => $sonv) {
        		$topCategory[$sonv['id']] = $sonv['categoryname'];
        	}
        }

        $viewData =[ 
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'],
                "topCategory"=>$topCategory
            ];
        return $this->view($viewData);
    }

    /**
     * [addarticleAction 添加文章]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-04T17:10:41+0800
     * @return   [type]                   [description]
     */
    public function addarticleAction(){

    	$action = '/Company/Article/doaddoreditarticle';

        $topcate = Model::ins('ComNewsCategory')->getList(['parentid'=>0,'isdelete'=>0],'id,categoryname');

        foreach ($topcate as $key => $value) {
        	$soncate = Model::ins('ComNewsCategory')->getList(['parentid'=>$value['id']],'id,categoryname');
        	foreach ($soncate as $sonk => $sonv) {
        		$soncate[$sonk]['categoryname'] = '&nbsp;&nbsp;&nbsp;&nbsp;|__'.$sonv['categoryname'];
        	}
        	$topcate[$key]['soncate'] = $soncate;
        }

        $topCategory = [];
      
        foreach ($topcate as $key => $value) {
        	$topCategory[$value['id']] = $value['categoryname'];
        	foreach ($value['soncate'] as $sonk => $sonv) {
        		$topCategory[$sonv['id']] = $sonv['categoryname'];
        	}
        }

        $formtoken = $this->Btoken('Company-Article-addarticle');
    	$viewData = [
    		'formtoken'=>$formtoken,
    		'action' => $action ,
    		'topCategory' => $topCategory
    	];
    	return $this->view($viewData);

    }

    /**
     * [editarticleAction 修改文章]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-04T17:11:37+0800
     * @return   [type]                   [description]
     */
    public function editarticleAction(){
    	$id = $this->getParam("id");
    	$action = '/Company/Article/doaddoreditarticle';

        $topcate = Model::ins('ComNewsCategory')->getList(['parentid'=>0,'isdelete'=>0],'id,categoryname');

        foreach ($topcate as $key => $value) {
        	$soncate = Model::ins('ComNewsCategory')->getList(['parentid'=>$value['id']],'id,categoryname');
        	foreach ($soncate as $sonk => $sonv) {
        		$soncate[$sonk]['categoryname'] = '&nbsp;&nbsp;&nbsp;&nbsp;|__'.$sonv['categoryname'];
        	}
        	$topcate[$key]['soncate'] = $soncate;
        }

        $topCategory = [];
      
        foreach ($topcate as $key => $value) {
        	$topCategory[$value['id']] = $value['categoryname'];
        	foreach ($value['soncate'] as $sonk => $sonv) {
        		$topCategory[$sonv['id']] = $sonv['categoryname'];
        	}
        }

        $article =  Model::ins('ComNewsArticle')->getRow(['id'=>$id]);
        $articleContent =  Model::ins('ComNewsArticleContent')->getRow(['id'=>$id]);
        //print_r($article);
        if(!empty($articleContent)){
            $Article = array_merge($article,$articleContent);
            //var_dump($Article);
            $Article['content'] = textimg($Article['content']);
        }else{
            $Article = $article;
        }
       
        $formtoken = $this->Btoken('Company-Article-editarticle');
    	$viewData = [
    		'formtoken'=>$formtoken,
    		'action' => $action ,
    		'topCategory' => $topCategory,
    		'Article'=>$Article
    	];
    	return $this->view($viewData);

    }

    /**
     * [doaddoreditarticleAction 添加或修改文章操作]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-04T17:11:23+0800
     * @return   [type]                   [description]
     */
    public function doaddoreditarticleAction(){

    	// if($this->Ctoken()){
	    	$post = $this->params;
	    	$id = $post['id'];

            if(empty($post['title']))
                $this->showError('文章标题不能为空');

            if(empty($post['categoryid']))
                $this->showError('请选择文章分类');

            if(empty($post['thumb']))
                $this->showError('文章缩略图不能为空');

            if(empty($post['description']))
                $this->showError('文章描述不能为空');

            if(empty($post['content']))
                $this->showError('文章内容不能为空');

	    	if(empty($id)){
                //文章标题不能重复
                $Article = Model::ins('ComNewsArticle')->getRow(['title'=>$post['title']],'id');
                if(!empty($Article['id']))
                    $this->showError('文章标题不能重复');
                $category = Model::ins('ComNewsCategory')->getRow(['id'=>$post['categoryid']],'categoryname');
                $article_insert = [    
                    'title' => $post['title'],
                    'categoryid' => $post['categoryid'],
                    'categoryname' => $category['categoryname'],
                    'thumb' => $post['thumb'],
                    'author' => $post['author'],
                    'addtime' => date('Y-m-d H:i:s'),
                    'sort' => empty($post['sort']) ? 0 : $post['sort']
                ];
               
                $ret = Model::ins('ComNewsArticle')->insert($article_insert);
               
                if($ret > 0){
                    $article_content_insert = [
                        'id' => $ret,
                        'keywords' => $post['keywords'],
                        'description' => $post['description'],
                        'content' => $post['content']
                    ];

                    Model::ins('ComNewsArticleContent')->insert($article_content_insert);
                    $article_insert['id'] = $ret;
                    $article_insert['description'] =  $post['description'];
                    $article_int['sort'] = intval($article_insert['sort']);
                    Model::Mongo('ComNewsArticle')->insert($article_insert,$article_int);
                   
                    $this->showSuccess('添加成功');
                }else{
                    $this->showError('操作错误请重新添加');
                }
	    	}else{
                //文章标题不能重复
                $Article = Model::ins('ComNewsArticle')->getRow(['title'=>$post['title'],'id'=>['<>',$id]],'id');
                if(!empty($Article['id']))
                    $this->showError('文章标题不能重复');
                $category = Model::ins('ComNewsCategory')->getRow(['id'=>$post['categoryid']],'categoryname');
                $article_update = [    
                    'title' => $post['title'],
                    'categoryid' => $post['categoryid'],
                    'categoryname' => $category['categoryname'],
                    'thumb' => $post['thumb'],
                    'author' => $post['author'],
                    'sort' => $post['sort']
                ];
                $ret = Model::ins('ComNewsArticle')->update($article_update,['id'=>$id]);

                // if($ret > 0){
                    $article_content_update = [
                        'keywords' => $post['keywords'],
                        'description' => $post['description'],
                        'content' => $post['content']
                    ];
                    $content = Model::ins('ComNewsArticleContent')->getRow(['id'=>$id],'id');
                    
                    if(!empty($content)){
                        Model::ins('ComNewsArticleContent')->update($article_content_update,['id'=>$id]);
                    }else{
                        $article_content_update['id'] = $id;
                        Model::ins('ComNewsArticleContent')->insert($article_content_update);
                    }

                    $article_mg = Model::Mongo('ComNewsArticle')->getRow(['id'=>$id],'id');
                    if(!empty($article_mg)){
                        $article_update['description'] = $post['description'];
                        $article_int['sort'] = intval($article_update['sort']);
                        Model::Mongo('ComNewsArticle')->update(['id'=>$id],$article_update,$article_int);
                    }else{
                        $article_row = Model::ins('ComNewsArticle')->getRow(['id'=>$id]);
                        //var_dump($article_row);
                        $article_row['description'] = $post['description'];
                        $article_int['sort'] = intval($article_row['sort']);
                        Model::Mongo('ComNewsArticle')->insert($article_row,$article_int);
                    }
                   
                    //$this->showSuccess('修改成功');
                // }

                $this->showSuccess('修改成功');
                // else{
                //     $this->showError('操作错误请重新修改');
                // }
	    	}

    	// }else{
    	// 	$this->showError('token错误，禁止操作');
    	// }
    }

    /**
     * [sortarticleAction 新闻排序]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-04T18:17:05+0800
     * @return   [type]                   [description]
     */
    public function sortarticleAction(){
        $ids = $this->getParam('id');
        $sort = $this->getParam('sort');
        $id_arr = explode(',', $ids);
        //print_r($id_arr);
        $sort_arr = explode(',', $sort);
        //print_r($sort_arr);
        foreach ($id_arr as $key => $value) {

            Model::ins('ComNewsArticle')->update(['sort'=> (int) $sort_arr[$key]],['id'=>$value]);
            $article_int['sort'] = intval($sort_arr[$key]);
            Model::Mongo('ComNewsArticle')->update(['id'=>$value],$article_int,$article_int);
        }
        
        $this->showSuccess('成功排序');
    }

    /**
     * [delarticleAction 删除文章]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-04T18:18:13+0800
     * @return   [type]                   [description]
     */
    public function delarticleAction(){
        $ids = $this->getParam('ids');
        $id_arr = explode(',', $ids);

        foreach ($id_arr as $key => $value) {
            $row = Model::ins('ComNewsArticle')->getRow(['id'=>$value],'id,isdelete');
            if(empty($row['id']) || $row['isdelete'] == 1){
                $this->showError('id为'.$value.'的文章不存在或已经被删除');
            }
            Model::ins('ComNewsArticle')->update(['isdelete'=> 1],['id'=>$value]);
            Model::Mongo('ComNewsArticle')->delete(['id'=>$value]);
        }
        $this->showSuccess('成功删除');
    }

    /**
     * [setarticletopAction 置顶取消置顶操作]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-09T09:40:27+0800
     * @return   [type]                   [description]
     */
    public function setarticletopAction(){
        $ids = $this->getParam('ids');
        $type = empty($this->getParam('type')) ? 0 : $this->getParam('type');

        if($type ==1){
            $updata = [
                'istop' => 1,
            ];
        }else if($type == -1){
            $updata = [
                'istop' => 0,
            ]; 
        }
        $ret = Model::ins('ComNewsArticle')->update($updata,['id'=>$ids]);
        Model::Mongo('ComNewsArticle')->update(['id'=>$ids],$updata);
        $this->showSuccess('操作成功');
    }

}