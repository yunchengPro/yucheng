<?php
namespace app\sale\controller\User;
use app\sale\ActionController;
use app\model\ShoppingCart\CartModel;
use app\model\Product\ProductModel;

class ShopcartController extends ActionController{

		/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [indexAction 购物车列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-18T17:41:52+0800
     * @return   [type]                   [description]
     */
    public function indexAction(){
    	
        $viewData = [
            'tittle'=>"购物车列表"
        ];
        return $this->view($viewData);
    }


    /**
     * [goodslistAction 购物车商品列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-30T14:17:07+0800
     * @return   [type]                   [description]
     */
    public function goodslistAction(){

        $data = CartModel::getCartGoodsList($this->userid);
       
        if($data['code'] == 200){
            //返回json数据  
            return $this->json('200',$data['data']);
        }else{
            return $this->json($data['code']);
        }
    }

    /**
     * [addgoodsAction 添加购物车商品]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-30T14:05:36+0800
     * @return   [type]                   [description]
     */
    public function addgoodsAction(){
       
        $param['customerid'] = $this->userid;
        $param['skuid']  = $this->params['skuid'];
        $param['productid'] = $this->params['productid'];
        $param['productnum'] = $this->params['productnum'];

        $data = CartModel::addOderCart($param);
       
        //返回json数据
        return $this->json($data['code']);
    }

    /**
     * [chosespecAction 选择规格]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-30T15:59:43+0800
     * @return   [type]                   [description]
     */
    public function chosespecAction(){

        if(!empty($this->params['productid'])){
            //商品信息
            $proData = ProductModel::getProDetailById($this->params['productid'],"id,productname,prouctprice,bullamount,businessid,enable");
            $proData =  $proData['data'];
           
            if(!empty($proData)){
                
                $proData['productid'] = $proData['id'];
                 //商品规格
                $proData['prouctprice'] = DePrice($proData['prouctprice']);
                $proData['specs'] = '';
                $proData['sku'] = '';
                $proData['dspec']='';

                $spec = ProductModel::getProSpecById($proData['productid']);

                if($spec['code'] != 5015){

                  //商品sku 所有情况
                    $sku = ProductModel::getProSkuById($proData['productid']);
                    $sku_arr = [];
                 
                    foreach ($sku['sku'] as $key => $value) {
                        $sku_arr[$value['f_productspec']] = $value;
                    }
                    $proData['sku'] = $sku_arr;//$sku['sku'];  
                    $proData['spec'] =$sku['spec'];   

                    if(!empty($this->params['skuid'])){
                       
                        $specGoodsData = ProductModel::getRowProSpecById($this->params['skuid'],'productid,productimage,productname,prouctprice,bullamount,spec,productspec,productstorage');
                      
                            if(!empty($specGoodsData)){
                                $proData['productname'] = $specGoodsData['productname'];
                                $proData['productimage'] = Img::url($specGoodsData['productimage']);
                                $proData['prouctprice'] = DePrice($specGoodsData['prouctprice']);
                                $proData['bullamount'] = DePrice($specGoodsData['bullamount']);
                                $proData['productstorage'] = $specGoodsData['productstorage'];
                                
                                $specData = ProductModel::getProSpecById($specGoodsData['productid']);
                               

                                $spec = json_decode($specGoodsData['spec'],true);

                                //print_r($spec);
                                $productspec = json_decode($specGoodsData['productspec'],true);
                                //print_r($productspec);
                                $string = '';
                                foreach ($spec as $ka => $va) {

                                    $string .= $va['name'] . ":" . $va['value'] .";";
                                }
                                $strings =''; 
                                foreach ($productspec as $ko => $vo) {
                                    $strings .= $ko . "," ;
                                }

                                $proData['dspecid'] = rtrim($strings,',');
                                $proData['specs'] = rtrim($string,',');
                                $proData['dspec'] = $spec;
                            }
                    }
                }
                //print_r($proData);
                return $this->json('200',$proData);
            }else{
                return $this->json('5005');
            }
        }else{
            return $this->json('404');
        }
    }

    /**
     * [updatespecgoodsAction 编辑购物车单条信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-11-02T11:59:52+0800
     * @return   [type]                   [description]
     */
    public function updatespecgoodsAction(){
        $productnum = $this->params['productnum'];
        $productid = $this->params['productid'];
        $skuid = $this->params['skuid'];
        $oldcartid = $this->params['oldcartid'];
        $customerid = $this->userid;
        $param = [
            'productnum' => $productnum,
            'productid'  => $productid,
            'skuid'      => $skuid,
            'oldcartid'  => $oldcartid,
            'customerid' => $customerid
        ];
        
        $data = CartModel::updateSpecOderCart($param);
       
        //返回json数据
        return $this->json($data['code']);
    }
}