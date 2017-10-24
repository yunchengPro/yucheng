<?php
// +----------------------------------------------------------------------
// |  [ 实体店搜索页面 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-11
// +----------------------------------------------------------------------
namespace app\api\controller\Stobusiness;
use app\api\ActionController;	

use app\lib\Model;
use app\model\User\TokenModel;
use app\model\StoBusiness\StobusinessModel;
use app\lib\Img;

class SearchController extends ActionController {

	/**
	 * [indexAction 搜索接口]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-11T09:43:06+0800
	 * @return   [type]                   [description]
	 */
	public function indexAction(){
        
        if($this->Version("1.0.4")){
            
            $this->params['square'] = 1;
        }

        $this->params['keywords'] = trim($this->params['keywords']);
        //var_dump($this->params['keywords']);
        $gdata = StobusinessModel::MongoSearch($this->params);
        
        
    	$page = !empty($this->params['page']) ? $this->params['page'] : 1;

    	
    	$data=['business'=>$gdata['data']];
    	

    	if($gdata['code']== 200){
    		return $this->json(200,$data);
    	}else{
    		return $this->json($data['code']);
    	}

	}

    /**
     * [bannerListAction 获取banner图]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-21T14:49:21+0800
     * @return   [type]                   [description]
     */
    public function bannerListAction(){

        $city_id = $this->params['city_id'];

        if(empty($city_id)) 
            return $this->json('404');

        $data = [
            [
                "city_id" => "",
                "city" => "",
                "bname" => "",
                "thumb" => "",
                "urltype" => "",
                "url" => ""
            ],[
                "city_id" => "",
                "city" => "",
                "bname" => "",
                "thumb" => "",
                "urltype" => "",
                "url" => ""
            ]
        ];
        if(!empty($this->params['categoryid'])){
            $advParam['category_id'] = $this->params['categoryid'];
        }
        $advParam['type'] = 1;
        $advParam['city_id'] = $city_id;
       
        $banner = StobusinessModel::getBanner($advParam);
        
        if($banner['code'] == 200){
            $data = $banner['data'];
        }
       
        return $this->json(200,$data);
    }

    /**
     * [categoryListAction 获取分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-21T14:50:40+0800
     * @return   [type]                   [description]
     */
    public function categoryListAction(){

        $data  = [
            "categoryid" =>  "",
            "categoryname" =>  "",
            "soncate" => [
                [
                    "categoryid"  => "",
                    "categoryname"  => ""
                ],
                [
                    "categoryid" => "",
                    "categoryname"  => ""
                ]
            ]
        ];

        $category = StobusinessModel::categoryList();

        if($category['code'] == 200) 
            $data = $category['data'];
        return $this->json(200,$data);
    }

    /**
     * [getlocalcityAction 获取城市列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-21T14:53:36+0800
     * @return   [type]                   [description]
     */
    public function getlocalcityAction(){


        $city_id = $this->params['city_id'];

        if(empty($city_id)) 
            return $this->json('404');

        $data = [
            [
                "city_id" => "",
                "parentid" => "",
                "areaname" => ""
            ],[
                "city_id" => "",
                "parentid" => "",
                "areaname" => ""
            ]
        ];
        
        $city = StobusinessModel::getlocalcity($city_id);
        
        if($city['code'] == 200){
            $data = $city['data'];
        }

        return $this->json(200,$data);
    }

    /**
     * [localStoBusinessAction 获取附件的实体店]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-06T14:50:27+0800
     * @return   [type]                   [description]
     */
    public function localStoBusinessAction(){

        $lngx = $this->params['lngx']; //经度 113.9324073444
        $laty = $this->params['laty'];//纬度 22.5258841351
        $distance = $this->params['distance']; //距离
        $search_param = [
            'lngx' => $lngx,
            'laty' => $laty,
            'distance' => $distance
        ];
        $reslut = StobusinessModel::MongoSearch($search_param);
    }

}