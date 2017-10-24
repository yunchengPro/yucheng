<?php
// +----------------------------------------------------------------------
// |  [ 资讯管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年8月28日18:17:10}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Article;
use app\superadmin\ActionController;

use app\lib\Db;
use app\lib\Model;
use app\model\Sys\AreaModel;
use think\Config;

class ArticleController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * [listAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-28T20:28:30+0800
     * @return   [type]                   [description]
     */
    public function listAction(){
       
    	$type = $this->getParam('type');
        $type_arr = [1,2];
        if(!in_array($type, $type_arr)){
            $this->showError('资讯类型不存在');
        }
        $where['isdelete'] = 0;
        $where['newstype']= $type;
        $where = $this->searchWhere([
                "title"=>"like",
                "isrelease"=>"=",
                "categoryid"=>"=",
                "addtime" => "times",
            ],$where);
        $list = Model::ins('ArtArticle')->pageList($where,'id,title,thumb,categoryid,istop,sort,citycode,isrelease,addtime,newstype','sort desc,addtime desc');
       	$newstype = [
       		'1'=>'牛品资讯',
       		'2'=>'牛店资讯'
       	];
       	$release_arr = [
       		'0'=>'未发布',
       		'1'=>'已发布',
       		'2'=>'已下线'
       	];
       	$category_arr = [];
        $category = Model::ins('ArtCategory')->getList(['catetype'=>$type],'id,categoryname');
        foreach ($category as $key => $value) {
        	$category_arr[$value['id']] = $value['categoryname'];
        }
        foreach ($list['list'] as $key => $value) {

        	$area = Model::ins('SysArea')->getRow(['id'=>$value['citycode']],'areaname');
        	
        	if(!empty($area['areaname'])){
        		$list['list'][$key]['citycode'] = $area['areaname'];
        	}else{
        		$list['list'][$key]['citycode'] = '全网';
        	}
        }
        $viewData =[ 
                'category_arr' => $category_arr,
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
                'paramString' => $paramString,
                'release_arr'=>$release_arr,
                'newstype' => $newstype,
                'type'=>$type,
                'mobiledomain'=>$mobiledomain
            ];

        return $this->view($viewData);
    }

    /**
     * [addarticleAction 添加文章]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-28T20:52:02+0800
     * @return   [type]                   [description]
     */
    public function addarticleAction(){

        $areaOBJ =  new AreaModel();
        $city = $areaOBJ->getCity();
        $city_arr = [];
        foreach ($city as $key => $value) {
            $city_arr[$key] = $value['areaname'];
        }

    	$type = $this->getParam('type');
        $type_arr = [1,2];
        if(!in_array($type, $type_arr)){
            $this->showError('资讯类型不存在');
        }
        $category_arr = [];
        $category = Model::ins('ArtCategory')->getList(['catetype'=>$type,'enable'=>1,'isdelete'=>0],'id,categoryname');
        foreach ($category as $key => $value) {
        	$category_arr[$value['id']] = $value['categoryname'];
        }
        $formtoken = $this->Btoken('Article-Article-addarticle');
        $viewData = [
            'title' =>'添加资讯',
            'action' => '/Article/Article/doaddoreditarticle',
            'type' => $type,
            'category_arr'=>$category_arr,
            'formtoken'=>$formtoken,
            "city" => $city_arr
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

        $areaOBJ =  new AreaModel();
        $city = $areaOBJ->getCity();
        $city_arr = [];
        foreach ($city as $key => $value) {
            $city_arr[$key] = $value['areaname'];
        }

    	$id = $this->getParam("id");
    	$action = '/Article/Article/doaddoreditarticle';
        $type = $this->getParam('type');
        $type_arr = [1,2];
        if(!in_array($type, $type_arr)){
            $this->showError('资讯类型不存在');
        }
        $category_arr = [];
        $category = Model::ins('ArtCategory')->getList(['catetype'=>$type,'enable'=>1,'isdelete'=>0],'id,categoryname');
        foreach ($category as $key => $value) {
        	$category_arr[$value['id']] = $value['categoryname'];
        }

        $article =  Model::ins('ArtArticle')->getRow(['id'=>$id]);
        $articleContent =  Model::ins('ArtArticleContent')->getRow(['id'=>$id]);
        $categoryArt = Model::ins('ArtCategory')->getRow(['id'=>$article['categoryid']],'categoryname');
        //print_r($article);
        if(!empty($articleContent)){
            $Article = array_merge($article,$articleContent);
            //var_dump($Article);
            $Article['content'] = textimg($Article['content']);
        }else{
            $Article = $article;
        }
       
        $formtoken = $this->Btoken('Article-Article-editarticle');
    	$viewData = [
    		'formtoken'=>$formtoken,
    		'action' => $action ,
    		'category_arr' => $category_arr,
    		'Article'=>$Article,
            'type' => $type,
            "city" => $city_arr,
            "categoryArt"=> $categoryArt
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
            $type = $post['type'];
            $type_arr = [1,2];
            if(!in_array($type, $type_arr)){
                $this->showError('文章类型不存在');
            }
	    	$id = $post['id'];
            $isrelease = $post['isrelease'];
           
            if(empty($post['title']))
                $this->showError('文章标题不能为空');

            if(empty($post['shorttitle']))
                $this->showError('文章副标题不能为空');

            if(empty($post['categoryid']))
                $this->showError('请选择文章分类');

            if(empty($post['thumb']))
                $this->showError('文章缩略图不能为空');
           
            if(empty($post['content']))
                $this->showError('文章内容不能为空');

            $category = Model::ins('ArtCategory')->getRow(['id'=>$post['categoryid']],'categoryname');

            // if($category['categoryname'] =='地区'){

            //     if(empty($post['citycode']))
            //          $this->showError('请选择所在城市');
            // }
	    	if(empty($id)){
                //文章标题不能重复
                $Article = Model::ins('ArtArticle')->getRow(['title'=>$post['title']],'id');
                if(!empty($Article['id']))
                    $this->showError('文章标题不能重复');
               
                $article_insert = [    
                    'title' => $post['title'],
                    'shorttitle' => $post['shorttitle'],  
                    'categoryid' => $post['categoryid'],
                    'thumb' => $post['thumb'],
                    'addtime' => date('Y-m-d H:i:s'),
                    'createbyid'=> $this->customerid,
                    'isrelease' => $isrelease,
                    'newstype' => $type,
                    'author' => empty($post['author']) ? '' : $post['author']
                ];
                if(!empty($post['citycode']))
                    $article_insert['citycode'] = $post['citycode'];
                $ret = Model::ins('ArtArticle')->insert($article_insert);
               
                if($ret > 0){
                    $article_content_insert = [
                        'id' => $ret,
                        'content' => $post['content']
                    ];

                    Model::ins('ArtArticleContent')->insert($article_content_insert);
                   	$article_row = Model::ins('ArtArticle')->getRow(['id'=>$ret]);
                	$article_int['sort'] = $article_row['sort'];
                	$article_int['hits'] = $article_row['hits'];
                    
                    Model::Mongo('ArtArticle')->insert($article_row,$article_int);
                   
                    $this->showSuccess('添加成功');
                }else{
                    $this->showError('操作错误请重新添加');
                }
	    	}else{
                //文章标题不能重复
                $Article = Model::ins('ArtArticle')->getRow(['title'=>$post['title'],'id'=>['<>',$id]],'id');
                if(!empty($Article['id']))
                    $this->showError('文章标题不能重复');
               
                $article_update = [    
                    'title' => $post['title'],
                    'shorttitle' => $post['shorttitle'],  
                    'categoryid' => $post['categoryid'],
                    'thumb' => $post['thumb'],
                    'isrelease' => $isrelease,
                    'author' => empty($post['author']) ? '' : $post['author']
                ];

               
                if(!empty($post['citycode']))
                    $article_update['citycode'] = $post['citycode'];
            
                $ret = Model::ins('ArtArticle')->update($article_update,['id'=>$id]);
                

                // if($ret > 0){
                    $article_content_update = [
                        'content' => $post['content']
                    ];
                    $content = Model::ins('ArtArticleContent')->getRow(['id'=>$id],'id');
                    
                    if(!empty($content)){
                        Model::ins('ArtArticleContent')->update($article_content_update,['id'=>$id]);
                    }else{
                        $article_content_update['id'] = $id;
                        Model::ins('ArtArticleContent')->insert($article_content_update);
                    }

                    $article_mg = Model::Mongo('ArtArticle')->getRow(['id'=>$id],'id');
                    $article_row = Model::ins('ArtArticle')->getRow(['id'=>$id]);
                	$article_int['sort'] = $article_row['sort'];
                	$article_int['hits'] = $article_row['hits'];
                   

                    if(!empty($article_mg)){
                        Model::Mongo('ArtArticle')->update(['id'=>$id],$article_row,$article_int);
                    }else{
                        Model::Mongo('ArtArticle')->insert($article_row,$article_int);
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
     * [setarticletop 置顶文章]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-29T12:01:47+0800
     * @return   [type]                   [description]
     */
    public function setarticletopAction(){

        $ids = $this->getParam('ids');
        $top = empty($this->getParam('top')) ? 0 : $this->getParam('top');

        if($top ==1){
            $updata = [
                'istop' => 1,
            ];
        }else if($top == -1){
            $updata = [
                'istop' => 0,
            ]; 
        }
        $ids = $this->getParam('ids');
        $id_arr = explode(',', $ids);

        foreach ($id_arr as $key => $value) {
            $row = Model::ins('ArtArticle')->getRow(['id'=>$value],'id,isdelete');
            if(empty($row['id']) || $row['isdelete'] == 1){
                $this->showError('id为'.$value.'的文章不存在或已经被删除');
            }
            $ret = Model::ins('ArtArticle')->update($updata,['id'=>$value]);
            Model::Mongo('ArtArticle')->update(['id'=>$value],$updata);
        }
      
        $this->showSuccess('操作成功');
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
            $row = Model::ins('ArtArticle')->getRow(['id'=>$value],'id,isdelete');
            if(empty($row['id']) || $row['isdelete'] == 1){
                $this->showError('id为'.$value.'的文章不存在或已经被删除');
            }
            Model::ins('ArtArticle')->update(['isdelete'=> 1],['id'=>$value]);
            Model::Mongo('ArtArticle')->delete(['id'=>$value]);
        }
        $this->showSuccess('成功删除');
    }

    /**
     * [setarticlereleaseAction 发布文章]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-29T12:21:18+0800
     * @return   [type]                   [description]
     */
    public function setarticlereleaseAction(){

     
        $release = empty($this->getParam('release')) ? 0 : $this->getParam('release');
        $updata = [
            'isrelease' => $release,
        ];
        $ids = $this->getParam('ids');
        $id_arr = explode(',', $ids);

        foreach ($id_arr as $key => $value) {
            $row = Model::ins('ArtArticle')->getRow(['id'=>$value],'id,isdelete');
            if(empty($row['id']) || $row['isdelete'] == 1){
                $this->showError('id为'.$value.'的文章不存在或已经被删除');
            }
            $ret = Model::ins('ArtArticle')->update($updata,['id'=>$value]);
            Model::Mongo('ArtArticle')->update(['id'=>$value],$updata);
        }
       
        $this->showSuccess('操作成功');
    }
   	
     /**
     * [lookAction 预览]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-01T21:06:26+0800
     * @return   [type]                   [description]
     */
    public function lookAction(){
        $url = Config::get('stobusiness');
        $mobiledomain = $url['mobiledomain'];
        $aid = $this->params['aid'];
        if(empty($aid))
            $this->showError('请选择需要查看的资讯');
        echo '<div style="max-width:450px;margin:0 auto;"><iframe  src="'.$mobiledomain.'/article/index/articleDetail?aid='.$aid.'" style="margin:0 auto;border:none;width:450px;height:100%;"></iframe></div>';
        exit();
    }
}