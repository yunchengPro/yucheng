<?php
namespace app\sale\controller\Product;
use app\sale\ActionController;

use app\lib\Model;
use app\model\Product\ProductModel;

class IndexController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    

    public function indexAction(){

       

        $viewData = [
            
        ];
        return $this->view($viewData);

    }

    /**
     * [categoryListAction 分类商品列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-10T09:56:04+0800
     * @return   [type]                   [description]
     */
    public function categoryGoodsListAction(){
        $viewData = [
            'title'=>'商品列表'
        ];
        return $this->view($viewData);
    }

    /**
     * [goodsListAction 商品列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-09T17:04:57+0800
     * @return   [type]                   [description]
     */
    public function goodsListAction(){
        $categoryid = $this->getParam('categoryid');
        if($categoryid>0){
            $param['where']['categoryid'] = $categoryid;
        }
        $param['where']['enable'] = 1;
        $param['where']['checksatus'] = 1;

       
        $list = ProductModel::ProductList($param);
        $maxPage = ceil($list['data']['total']/20);
        $list['data']['maxPage'] =  $maxPage;
        return $this->json($list['code'],$list['data']);
    }

    /**
     * [goodsDetailAction 商品详情]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-10T14:45:44+0800
     * @return   [type]                   [description]
     */
    public function goodsDetailAction(){
        $goods_id = $this->getParam('goods_id');

        if(empty($goods_id))
            return $this->json(404);

        $sku_data =  ProductModel::getProSkuById($goods_id);
      
        
        $viewData = [
            'title'=>'商品详情',
            'goods_id'=>$goods_id,
            'sku_data'=>$sku_data
        ];
        return $this->view($viewData);
    }

    /**
     * [goodsDetailAction 商品详情]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-09T17:23:57+0800
     * @return   [type]                   [description]
     */
    public function goodsDetailDataAction(){
        $goods_id = $this->getParam('goods_id');
        $goods_detail = ProductModel::getProDetailById($goods_id);
        $goods_detail['data']['description'] = textimg($goods_detail['data']['description']);
       
        return $this->json($goods_detail['code'],$goods_detail['data']);
    }



     /**
     * [detailAction 商品详细介绍]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-22T17:42:44+0800
     * @return   [type]                   [description]
     */
    public function detailAction(){

        $goodsid=$this->getParam('goodsid');

        $data =  Model::ins('ProProductInfo')->getRow(['id'=>$goodsid]);
        $data['description'] = imgLazyload(textimg($data['description']));

        $viewData = [
            'data'=>$data
        ];
        return $this->view($viewData);
    }
    
    /**
     * [detailParamsAction 商品详细参数]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-22T17:42:59+0800
     * @return   [type]                   [description]
     */
    public function detailParamsAction(){

        $goodsid=$this->getParam('goodsid');

        $proData = Model::ins('ProProduct')->getRow(['id'=>$goodsid],'id,addtime,brandid');

        if($proData['brandid'] > 0){
            $brand = Model::ins('ProBrand')->getRow(['id'=>$proData['brandid']],'brandname');
        }else{
            $brand = '';
        }

        $data =  Model::ins('ProProductSpecValue')->getRow(['productid'=>$goodsid]);
        
        $spec_name = json_decode($data['spec_name'],true);
        $spec_vlaue = json_decode($data['spec_vlaue'],true);

        //组合规格信息
        $arr2 = [];
        foreach ($spec_vlaue as $key => $value) {
            
            foreach ($value as $k => $v) {
                $arr2[$spec_name[$key]] .=  $v . ',';
            }
        }
      
        $sku = ProductModel::getProSkuById($goodsid);

         
        $spec = $sku['spec'];
        
       

        //商品sku 所有情况
        $skus = $sku['sku']; 
        
       
        foreach ($skus as $key => $value) {
             $sku_arr[] = explode(',',$value['f_productspec']);
        }

        foreach ($sku_arr as $key => $value) {
            foreach ($value as $ka => $va) {
                    $string[$ka][] =  $va ;
            }
        }
     
      
        foreach ($spec as $sk => $sv) {

            foreach ($sv['value'] as $skv => $svv) {
                if(!in_array($svv['id'], $string[$sk])){
                    unset($spec[$sk]['value'][$skv]);
                }
            }
        }

        foreach ($spec as $key => $value) {
            sort($spec[$key]['value']);
        }
        //print_r($spec);


        $viewData = [
            'addtime'=>$proData['addtime'],
            'brand'=>$brand['brandname'],
            'spec'=>$arr2,
            'specs'=>$spec
        ];
        return $this->view($viewData);
    } 
    

    
}
