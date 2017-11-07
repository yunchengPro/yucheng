<?php
namespace app\sale\controller\Article;
use app\sale\ActionController;


use think\Config;

use app\lib\Model;

use app\lib\Img;

class IndexController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [articleListAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-01T09:47:15+0800
     * @return   [type]                   [description]
     */
    public function articleListAction(){
    	
        //$city_id = empty($this->params['city_id']) ? '440305' : $this->params['city_id'];
    	$mtoken = $this->mtoken;
    	$newstype = $this->params['newstype'];
        $title = '新闻资讯';
        if($newstype == 1){
            $title = '新闻资讯';
        }else if($newstype == 2){
            $title = '新闻资讯';
        }
        $viewData = [
            'title' => $title,
            'mtoken'=>$mtoken,
            'newstype'=>$newstype,
            'city_id'  => $city_id
        ];
        return $this->view($viewData);

    }

    /**
     * [getArticleListAction 获取文章列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-01T10:05:03+0800
     * @return   [type]                   [description]
     */
    public function getArticleListAction(){

        $city_id = empty($this->params['city_id']) ? '440305' : $this->params['city_id'];
        $area = Model::ins('SysArea')->getRow(['id'=>$city_id],'id,parentid,level');
        if($area['level'] == 2){
            $city_id = $area['id'];
        }else if($area['level'] == 3){
            $city_id = $area['parentid'];
        }else if($area['level'] == 1){
            $area = Model::ins('SysArea')->getRow(['parentid'=>$city_id,'level'=>2],'id,parentid,level');
            $city_id = $area['id'];
        }
    	$newstype = empty($this->params['newstype']) ?　1 : $this->params['newstype'];
        

    	$category = Model::ins('ArtCategory')->getList(['catetype'=>$newstype,'enable'=>1,'isdelete'=>0],'id,categoryname','sort asc,id desc');
        
        if($newstype == 2){
    	   $categoryid = empty($this->params['categoryid']) ? 0 : $this->params['categoryid'];
        }else if($newstype == 1){
            $categoryid = empty($this->params['categoryid']) ? $category[0]['id'] : $this->params['categoryid'];
        }else{
            $categoryid =  $category[0]['id'];
        }
      
        if($categoryid != 0)
            $article_top = Model::ins('ArtArticle')->getList(['categoryid'=>strval($categoryid),'isrelease'=>'1','newstype'=>strval($newstype),'istop'=>strval(1)],'id,title,shorttitle,thumb,citycode','addtime desc');

        if($newstype == 2){
           
            $where['$or'] = [
                ['citycode' => ""],
                ['citycode' => strval($city_id)],
            ];
            if($categoryid == 0){
               unset($where['$or']);
               $where['citycode'] = strval($city_id);
            }
            if($categoryid > 0)
                $where['categoryid'] = strval($categoryid);
            $where['istop'] = strval(0);
            $where['isrelease'] = '1';
            $where['newstype'] = strval($newstype);
          
    	    $articleList = Model::ins('ArtArticle')->pageList($where,'id,title,shorttitle,thumb,citycode','addtime desc');
           
        }else{
            $articleList = Model::ins('ArtArticle')->pageList(['categoryid'=>strval($categoryid),'istop'=>strval(0),'isrelease'=>'1','newstype'=>strval($newstype)],'id,title,shorttitle,thumb,citycode','addtime desc');
        }
       
        if(!empty($article_top))
    	   $articleList['list'] = array_merge($article_top,$articleList['list']);

    	$maxPage = ceil($articleList['total']/20);
    	$articleList['maxPage'] = $maxPage;
    	foreach ($articleList['list'] as $key => $value) {
    		$articleList['list'][$key]['thumb'] = Img::url($value['thumb']);
    	}

    	$data['article'] = $articleList;

        //print_r($category);
        $area_cate =[[
            'id' => 0,
            'categoryname' => '地区'
        ]];
        
        if($newstype ==2 ){
            $category = array_merge($area_cate,$category);
        }
    	$data['category'] = $category; 
    	$data['categoryid'] = $categoryid;
    	return $this->json(200,$data);
    }

    /**
     * [articleDetailAction 文章详情]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-01T11:25:59+0800
     * @return   [type]                   [description]
     */
    public function articleDetailAction(){
    	$mtoken = $this->mtoken;
    	$aid = $this->params['aid'];
    	$article = Model::ins('ArtArticle')->getRow(['id'=>$aid],'title,shorttitle,author,addtime');
    	if(empty($article)){
    		echo "文章不存在";
    		exit();
    	}
    	$article_content = Model::ins('ArtArticleContent')->getRow(['id'=>$aid],'content');

    	$article['content'] = imgStyleReplace(textimg($article_content['content']));
        $viewData = [
            'title' => "牛人牛语",
            'mtoken'=>$mtoken,
            'article'=>$article
        ];
        return $this->view($viewData);
    }


}
