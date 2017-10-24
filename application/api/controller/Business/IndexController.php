<?php
namespace app\api\controller\Business;
use app\api\ActionController;

use app\model\Business\BusinessModel;
use app\model\Product\ProductModel;
use think\Config;
use app\lib\Img;

use app\model\User\TokenModel;
use app\model\User\CollectionModel;

class IndexController extends ActionController
{
    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }   

    /**
     * 店铺首页
     * @Author   zhuangqm
     * @DateTime 2017-02-28T17:46:09+0800
     * @return   [type]                   [description]
     */
    public function homeAction(){


        $businessid     = $this->params['businessid'];
        $mtoken =   $this->params['mtoken'];
        $page           = empty($this->params['page']) ? 1 : $this->params['page'];

        if(empty($businessid))
            return $this->json("404");

        $business = [];
        
        $business       = BusinessModel::getBusinessInfoById($businessid);
        if(!empty($business)){
            $check = false;
            if(!empty($mtoken)){
                $tokenModel = new TokenModel();
                $userId = $tokenModel->getTokenId($mtoken);
                
                $params = [
                    'type' => 2,
                    'obj_id' => $businessid,
                    'userid' => $userId['id']
                ];
               
                $check = CollectionModel::checkCollectcount($params);
               
            }

            if($check){
                $business['iscollect'] = 1;
            }else{
                $business['iscollect'] = -1;
            }
        }
        $productlist    = ProductModel::ProductList([
                "where" => ["businessid"=>$businessid,'enable'=>1,'checksatus'=>1]//,"checksatus"=>1
            ]);

          
         
        if($page == 1){
            
            $arr['url'] =  Config::get('shareparma.business_url').$business['id'];
            $arr['title'] =  $business['businessname'];
            $arr['description'] = $business['businessname'];
            $arr['image'] = Img::url($business['businesslogo'],200,200);
            

            $data = [
                "business"=>$business,
                "productlist"=>$productlist,
                "sharecontent" =>  $arr
            ];
        }else{
              $data = [
                "productlist"=>$productlist,
            ];
        }

        return $this->json("200",$data);
    }

    /**
     * [goodsListAction 商品列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-09T19:45:26+0800
     * @return   [type]                   [description]
     */
    public function goodsListAction(){
        $businessid     = $this->params['businessid'];

        if(empty($businessid))
            return $this->json("404");

        $cid     = $this->params['cid'];
        $where['where'] = ["businessid"=>$businessid,'enable'=>1,'checksatus'=>1];//,'checksatus'=>1
        if(!empty($cid))
             $where['where'] = ["businesscategoryid"=>$cid,"businessid"=>$businessid,'enable'=>1,'checksatus'=>1];//,'checksatus'=>1
       

        $productlist    = ProductModel::ProductList($where);

        return $this->json("200",$productlist);
    }

    /**
     * [searchGoodsAction 搜索商品]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-09T20:06:28+0800
     * @return   [type]                   [description]
     */
    public function searchGoodsAction(){

        $businessid     = $this->params['businessid'];
        $keywords = $this->params['keywords'];
        $order = $this->params['order'];
        $cid     = $this->params['cid'];

        if(empty($businessid))
            return $this->json("404"); 
        $where['where'] = ["businessid"=>$businessid,'enable'=>1,'checksatus'=>1];//,'checksatus'=>1

        if(!empty($cid))
            $where['where'] = ["businesscategoryid"=>$cid,"businessid"=>$businessid,'enable'=>1,'checksatus'=>1];//,'checksatus'=>1

        if(!empty($keywords))
            $where['where']['productname'] = ["like","%".$keywords."%"];

        if(!empty($order))
            $where['order'] = $order;
        
        $productlist    = ProductModel::ProductList($where);

        return $this->json("200",$productlist);
    }
}
