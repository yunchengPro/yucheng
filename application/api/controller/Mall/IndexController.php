<?php
// +----------------------------------------------------------------------
// |  [ 2016-02-28 商城首页 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ISir <673638498@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller\Mall;

use app\api\ActionController;

//获取配置
use \think\Config;

use  app\model\Product\ProductModel;
use  app\model\ProCategoryModel;

use  app\model\Business\BusinessCategoryModel;
use  app\model\Mall\IndexModel;
use app\model\Business\BusinessModel;
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
     * [indexAction 商城首页]
     * @return [type] [description]
     */
    public function indexAction(){	

    	
       	//售卖方式
       	$saleWay = ProductModel::getSaleway(['where'=>['type'=>['<>',1]]]);

       	//广告列表 
		$brand = ProductModel::getTypeBanner(1);
        
       	//产品分类
       	$category = ProCategoryModel::getCateData();
    
       	//返回json数据
       	return $this->json('200',[
	       			'banner'=>$brand,
	       			'category'=>$category,
	       			'saleWay'=>$saleWay,
	       			'proData'=>$proData
       			]);

    }

    /**
     * [indexBannerAndSalewayAction 首页banner图和快捷方式接口]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-07T11:48:40+0800
     * @return   [type]                   [description]
     */
    public function indexBannerAndSalewayAction(){
        //售卖方式
        $saleWay = ProductModel::getSaleway(['type'=>1]);
      
        if(!$this->Version("2.0.0")){
            if(!is_array($saleWay[2])){
                $saleWay[2]->name = '牛豆专区';
                $saleWay[2]->thumb = 'https://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-21/1492768021ya339.png?x-oss-process=image/resize,m_lfit,w_50,h_50';
                 $saleWay[2]->bname  = '牛豆专区';
            }else{
                $saleWay[2]['name'] = '牛豆专区';
                $saleWay[2]['thumb'] = 'https://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-21/1492768021ya339.png?x-oss-process=image/resize,m_lfit,w_50,h_50';
                $saleWay[2]['bname'] = '牛豆专区';
            }
           
        }

        //广告列表 
        $brand = ProductModel::getTypeBanner(1);
            //返回json数据
        return $this->json('200',[
                    'banner'=>$brand,
                    'saleWay'=>$saleWay
                ]);

    }

    /**
     * [indexCategoryAction 首页获取所有分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-07T11:54:32+0800
     * @return   [type]                   [description]
     */
    public function indexCategoryAction(){

        $businessid = $this->params["businessid"];
        $showall = $this->params["showall"];

        if(!empty($businessid)){
            //产品分类
            $data = BusinessModel::getBusinessCategoryById($businessid);
            //print_r($data);
            foreach($data as $k=>$v){
                $data[$k]['name'] = $v['category_name'];

                foreach($v['sonCate'] as $key=>$value){
                    $data[$k]['sonCate'][$key]['name'] = $value['category_name'];
                }
            }

            return $this->json("200",$data);
        }else{
            

            if($this->Version("1.0.3")){

                $category = ProCategoryModel::getCateData($showall);

            }else{

                $category = ProCategoryModel::getCateData();

            }
            return $this->json('200',$category);
        }
        
        
    }

    /**
     * [indexGoodslistAction 首页所有商品列表信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-07T11:52:20+0800
     * @return   [type]                   [description]
     */
    public function indexGoodslistAction(){
        //商品列表
        $proData = ProductModel::ProductList(['where'=>['enable'=>1,'checksatus'=>1]]);//,'checksatus'=>1
        return $this->json('200',$proData);
    }

    /**
     * [indexAnnounAndActive 首页活动和公告接口]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-07T13:59:21+0800
     * @return   [type]                   [description]
     */
    public function indexAnnounAndActiveAction(){
        $citycode = $this->params["citycode"];
        $customerid = $this->userid;
        $announcement = IndexModel::announcement(1,$citycode);
        $goodweeklygoods = IndexModel::goodweeklygoods();
        $moduleBanner = IndexModel::activemodule(1);
        foreach ($goodweeklygoods as $key => $value) {
            $goodweeklygoods[$key]['publicurl'] = $value['publicurl'].'?customerid='.$customerid;
        }
        return $this->json('200',['announcement'=>$announcement,'goodweeklygoods'=>$goodweeklygoods,'modulebanner'=>$moduleBanner]);
    }



    /**
     * [categoryListAction 商品分类列表]
     * @return [type] [description]
     */
    public function categoryGoodsListAction(){

        //$data = BusinessCategoryModel::getCateData(1);
       
    	//print_r($this->params);//获取参数
    	$category = $this->params['cid'];//分类id
    	if(!empty($category)){
    		//商品信息
	    	$proData =  ProductModel::ProductList(["where"=>['categoryid'=>$category]]);//,"checksatus"=>1
	       	//print_r($proData);	
	       		//返回json数据
       		return $this->json('200',['prolist'=>$proData]);
       	}else{
       		//返回json数据
       		return $this->json('5001');
       	}
    }

    /**
     * [mallKeywordsAction 获取关键词]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-24T20:31:43+0800
     * @return   [type]                   [description]
     */
    public  function mallKeywordsAction(){
        $type = !empty($this->params['type']) ? $this->params['type'] : 1;
        $data = Model::ins('MallHotKeywords')->getRow(['type'=>$type]);
        $tmp_keywords = [];
        if(!empty($data['keywords'])){
            $tmp_keywords = explode(',', $data['keywords']);
        }
        $data['keywords'] = $tmp_keywords;
        return $this->json('200',$data);
    }

    
    /**
    * @user 首页每周好货
    * @param 
    * @author jeeluo
    * @date 2017年9月19日下午2:15:37
    */
//     public function goodweeklygoodsAction() {
//         $list = Model::ins("ProProductBuy")->sort();
        
//         $ProProductModel = Model::ins("ProProduct");

//         // 遍历获取商品的商品名称和thumb
//         if(!empty($list['list'])) {
//             foreach ($list['list'] as $k => $v) {
//                 $proproductInfo = $ProProductModel->getRow(["id"=>$v['goodsid']],"productname,thumb");
//                 $list['list'][$k]['productname'] = $proproductInfo['productname'];
//                 $list['list'][$k]['thumb'] = Img::url($proproductInfo['thumb']);
                
//                 $list['list'][$k]['saleprice'] = $v['saleprice'] > 0 ? DePrice($v['saleprice']) : '0.00';
//                 $list['list'][$k]['prouctprice'] = $v['prouctprice'] > 0 ? DePrice($v['prouctprice']) : '0.00';
                
//                 unset($list['list'][$k]['productstorage']);
//                 unset($list['list'][$k]['activeproductnum']);
//             }
//         } else {
//             $list['list'] = array();
//         }
        
//         return $this->json(200, $list);
//     }
    
    /**
    * @user 每周好货列表
    * @param 
    * @author jeeluo
    * @date 2017年9月19日下午2:40:30
    */
    public function goodsactivelistAction() {
        // 根据类型 来获取时间状态
        $type = !empty($this->params['type']) ? $this->params['type'] : 0;
        // 看是否收到商品id值
        $goodsid = !empty($this->params['goodsid']) ? $this->params['goodsid'] : 0;
        $customerid = !empty($this->params['customerid']) ? $this->params['customerid'] : 0;
        $list = Model::ins("ProProductBuy")->sort(["type"=>$type,"goodsid"=>$goodsid,"customerid"=>$customerid]);
        
        if(!empty($list['list'])) {
            $ProProductModel = Model::ins("ProProduct");
            foreach ($list['list'] as $k => $v) {
                $proproductInfo = $ProProductModel->getRow(["id"=>$v['goodsid']],"productname,thumb");
                $list['list'][$k]['productname'] = $proproductInfo['productname'];
                $list['list'][$k]['thumb'] = Img::url($proproductInfo['thumb']);
                $v['saleprice'] =  $v['saleprice'] + $v['bullamount'];
                $list['list'][$k]['saleprice'] = $v['saleprice'] > 0 ? DePrice($v['saleprice']) : '0.00';
                $list['list'][$k]['prouctprice'] = $v['prouctprice'] > 0 ? DePrice($v['prouctprice']) : '0.00';
                $list['list'][$k]['bullamount']  = $v['bullamount'] > 0 ? DePrice($v['bullamount']) : '0.00';
                $proportion = ($v['productstorage']-$v['productstorage_buy'])/$v['productstorage']*100;

                $roundPro = round($proportion,0);
                
                // 有种情况 ，假如参与活动的数量超过200个，而且库存等于1时，百分比会等于100% 可是还存在库存，所以得减去1
                if($roundPro == 100 && $v['productstorage_buy'] > 0) {
                    $roundPro -= 1;
                }
                
                $list['list'][$k]['roundPro'] = $roundPro;
                $list['list'][$k]['activeproductnum'] = $v['productstorage'];
                
                unset($list['list'][$k]['productstorage_buy']);
            }
        } else {
            $list['list'] = array();
        }
        
        return $this->json(200, $list);
    }
    
    /**
    * @user 修改提醒状态
    * @param 
    * @author jeeluo
    * @date 2017年9月23日下午6:27:24
    */
    public function updateRemindAction() {
        if(empty($this->params['buyId']) || empty($this->params['remindStatus']) || empty($this->params['customerid'])) {
            return $this->json(404);
        }
        // 获取数据
        $buyId = $this->params['buyId'];
        $remindStatus = $this->params['remindStatus'];
        
        $ProProductBuyModel = Model::ins("ProProductBuy");
        $proProductBuyInfo = $ProProductBuyModel->getRow(["id"=>$buyId,"enable"=>1,"starttime"=>[">=",getFormatNow()]],"id,productid,starttime,endtime");
        if(!empty($proProductBuyInfo)) {
            // 根据数据 查询记录
            $buyRemindInfo = Model::ins("ProProductBuyRemind")->getRow(["customerid"=>$this->params['customerid'],"product_buy_id"=>$buyId,"productid"=>$proProductBuyInfo['productid']],"id,status");
            if(!empty($buyRemindInfo['id'])) {
                if($buyRemindInfo['status'] == 0) {
                    if($remindStatus != -1) {
                        return $this->json(1001);
                    }
                } else if($buyRemindInfo['status'] == 1) {
                    return $this->json(1001);
                } else if($buyRemindInfo['status'] == -1) {
                    if($remindStatus != 1) {
                        return $this->json(1001);
                    }
                }
                if($remindStatus == 1) {
                    $remindStatus = 0;
                }
                // 修改状态数据
                $status = Model::ins("ProProductBuyRemind")->update(["status"=>$remindStatus],["id"=>$buyRemindInfo['id']]);
                if($status) {
                    return $this->json(200);
                }
                return $this->json(400);
            } else {
                // 新建数据
                $status = Model::ins("ProProductBuyRemind")->insert(["customerid"=>$this->params['customerid'],"product_buy_id"=>$buyId,"productid"=>$proProductBuyInfo['productid'],"starttime"=>$proProductBuyInfo['starttime'],
                    "endtime"=>$proProductBuyInfo['endtime'],"status"=>0]);
                if($status) {
                    return $this->json(200);
                }
                return $this->json(400);
            }
        } 
        return $this->json(1001);
    }
}