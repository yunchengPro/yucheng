<?php
/**
 * 商家店铺
 */
namespace app\api\model;

use app\lib\Db;

use app\lib\Img;

class ProductModel
{   

    /**
     * 通用获取用户列表方法，适用于商品列表展示地方，如首页，分类商品列表，专区
     * @Author   zhuangqm
     * @DateTime 2017-03-02T13:59:28+0800
     * * @param [type] $param [
     *                    "where"
     *                    "fields"
     *                    "order"
     *             ]
     *   @return [
     *        "total" => 记录数
     *        "list"  => 记录列表
     *   ]
     */
    public static function ProductList($param){
        $list = self::ProductPageList([
                "where"     => $param['where'],
                "fields"    => "id as productid,productname,prouctprice/100,bullamount",
                "order"     => $param['order']!=""?$param['order']:"addtime desc",
            ]);

        foreach($list['list'] as $k=>$v){
            $list['list'][$k]['prouctprice'] = Img::url($v['prouctprice']);
        }

        return $list;
    }


    /**
     * [ProductPageList 获取商品列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-02-28 
     * @param [type] $param [description]
     */
    public static function ProductPageList($param){
       
        $where      =  !empty($param['where']) ? $param['where'] : '';

        $fields     =  !empty($param['fields']) ? $param['fields'] : '*';

        $order      =  !empty($param['order']) ? $param['order'] : '';
       
        //商品信息
        $ProProduct = Db::Model("Product");
        return $proData = $ProProduct->pageList($where,$fields,$order);

    }

    /**
     * [getProDetailById 通过id获取商品基本信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-01 
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function getProDetailById($id,$fields='*'){
         //商品信息
        $ProProduct = Db::Model("ProProduct");
        return $proData = $ProProduct->getRow(['id'=>$id],$fields);
    }

    /**
     * [getProPhoto 获取商品图片]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-01 
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function getProPhotosById($id){
        //商品图片
        $ProProductphoto = Db::Model("ProProductphoto");
        return $photoData = $ProProductphoto->getList(['productid'=>$id],"istop,productpic,sort",'sort desc',0,5);
    }

    /**
     * [getProSpec 获取商品所有规格]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-01 
     * @return [type] [description]
     */
    public static function getProSpecById($id){
            $spec = [];
            //商品规格
            $ProProductSpecValue = Db::Model("ProProductSpecValue");

            $spec_info = $ProProductSpecValue->getRow(['productid'=>$id]);
            //print_r($spec_info);
            
            //规格值
            $spec_name = json_decode($spec_info['spec_name'], true);
            //规格值
            $spec_value = json_decode($spec_info['spec_vlaue'], true);

            //商品规格
            $ProSpec = Db::Model("ProSpec");
            //print_r($spec_value);
            foreach($spec_value as $k=>$v){
                $imageProSpec=$ProSpec->getRow("id={$k} and isdelete=0");
               
                $value = array();
                foreach($v as $ik=>$iv){
                    $value[] = array("id"=>$ik,"parent_id"=>$k,"spec_value"=>$iv);
                }
                $spec[] = array(
                    "id"=>$k,
                    "f_images"=>(string)$imageProSpec['images'],
                    "spec_name"=>$spec_name[$k],
                    "value"=> $value 
                );
            } 
            //print_r($spec);
            return $spec;
    }


    /**
     * [getProBussinessById 获取商铺基本信息]
     * @return [type] [description]
     */
    public static function getProBusinessById($id,$fields="*"){
        //获取商家信息
        $BusBusiness = Db::Model("BusBusiness");
        return $business = $BusBusiness->getRow(['id'=>$id],$fields);
    }

    /**
     * [getProSkuById 获取商品所有sku]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-01 
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function getProSkuById($id){

            //商品规格
            $ProProductSpecValue = Db::Model("ProProductSpecValue");

            $spec_info = $ProProductSpecValue->getRow(['productid'=>$id]);
            //print_r($spec_info);
            
            //规格值
            $spec_name = json_decode($spec_info['spec_name'], true);
            //规格值
            $spec_value = json_decode($spec_info['spec_vlaue'], true);

             //组合商品sku 获取商品所以组合sku情况
            $ProProductspec = Db::Model('ProProductspec');
            $ProductSpecInfo = $ProProductspec->getList ('productid='.$id.' and isdelete = 0 ');
          
            //重组商品sku
            $sku_list = [];
            if(!empty($ProductSpecInfo) && is_array($ProductSpecInfo)){

                foreach ($ProductSpecInfo as $k => $v) {
                    
                    $sku_list[$k]['id'] = $v['id']; 
                    $sku_list[$k]['aotusku'] = $v['aotusku']; 
                    $sku_list[$k]['productid'] = $v['productid']; 
                    $sku_list[$k]['prouctprice'] = $v['prouctprice']; 
                    $sku_list[$k]['marketprice'] = $v['marketprice'];
                    $productspec = json_decode($v['productspec'],true); 
                    $gtproductspec = '';
                    if(!empty($productspec)){
                        foreach($spec_value as $sk=>$sv){
                           foreach($sv as $ssk=>$ssv){
                               foreach($productspec as $fk=>$fv){
                               if($ssk == $fk)
                                   $gtproductspec .= $fk . ",";
                               }
                           }

                        }
                        $gtproductspec = rtrim($gtproductspec, ",");
                    }

                    $sku_list[$k]['f_productspec'] = $gtproductspec;

                }

            }
            //print_r($sku_list);
            return $sku_list;
    }

    /**
     * [getProIntroById 获取商品图文详情]
     * @return [type] [description]
     */
    public static function getProIntroById($id){
        $ProProductIntro = Db::Model("ProProductIntro");
        return $IntroData = $ProProductIntro->getRow(['productid'=>$id]);
    }

    /**
     * [getCommentListById 商品详情返回评论信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-01 
     * @param  [type] $param [description]
     * @return [type]        [description]
     */
    public static function getCommentListById($id){
        $ProEvaluate = Db::Model('ProEvaluate');
        return $commentData = $ProEvaluate->getList(['productid'=>$id,"state"=>0],"id,content,addtime,frommembername,headpic","addtime desc",0,3);
    }

    /**
     * [getAllProComment 获取商品所有评论]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-01 
     * @return [type] [description]
     */
    public static function getAllProComment($id){
        
        $ProEvaluate = Db::Model('ProEvaluate');
        return $data =  $ProEvaluate->pageList(["productid"=>$id,"state"=>0],"id,productid,businessid,productname,scores,content,desccredit,frommemberid,frommembername,headpic,servicecredit,deliverycredit,evaluateauto,addtime","addtime desc");
    }

    /**
     * [searchProduct 搜索商品信息]
     * @param  [type] $param [description]
     * @return [type]        [description]
     */
    public static function searchProduct($param){
        $ProProduct = Db::Model("ProProduct");
        $where = !empty($param['where']) ? $param['where'] : "";
        
        $fields = !empty($param['fields']) ? $param['fields'] : "*";
        return $proData =  $ProProduct->pageList($where,$fields);
    } 
}
