<?php
// +----------------------------------------------------------------------
// |  [ 商品管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-16
// +----------------------------------------------------------------------
namespace app\admin\controller\Product;
use app\admin\ActionController;

use \think\Config;

use app\lib\Db;
use app\lib\Img;
use app\lib\Model;
use app\model\Business\BusinessCategoryModel;
use app\model\Product\ProductModel;
use app\form\ProProduct\ProProductAdd;
use app\form\ProProductSpecValue\ProProductSpecValueAdd;
use app\form\ProProductSpec\ProProductSpecAdd;
use app\model\OrdTransportExtendModel;

class IndexController extends ActionController{

    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [indexAction 模型]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-14T21:21:02+0800
     * @return   [type]                   [description]
     */
    public function listAction(){     

        $params = $this->params;
        foreach ($params as $k => $v) {
            $paramString .= "&$k=$v";
        }
  
        $categoryid         = $this->getParam('categoryid');
        $businesscategoryid = $this->getParam('businesscategoryid');


        //获取店铺分类id 根据店铺分类id 查找商品
        if(!empty($businesscategoryid)){

            $buscateData  = Model::ins('BusBusinessCategory')->getRow(['id'=>$businesscategoryid,'businessid'=>$this->businessid],'id,parent_id');
            if($buscateData['parent_id'] != 0){
                $where['businesscategoryid'] = $buscateData['id'];
            }else{
                $ids = '';

                $soncateData = Model::ins('BusBusinessCategory')->getList(['parent_id'=>$businesscategoryid],'id');
                
                foreach ($soncateData as $key => $value) {
                    if(!empty( $value['id']))
                        $ids .= $value['id'] . ',';
                }
                $ids = rtrim($ids,',');

                $where['businesscategoryid'] = array('in',$ids);
            }
        }

        //获取分类数据
        //$categoryArr
        
        //获取店铺分类数据
        $businessCategory = BusinessCategoryModel::formart_category($this->businessid);
        //var_dump($businessCategory); exit();
        $businessCategoryArr = [];
                 
        foreach ($businessCategory as $key => $value) {        
            $businessCategoryArr[$value['id']] = $value['_category_name'];
        }
        
        $category_top = $this->getParam('category_top');
        $category_son = $this->getParam('category_son');

        if(!empty($category_top)){
            if(empty($category_son)){
                $ids = '';

                $soncateData = Model::ins('ProCategory')->getList(['parent_id'=>$category_top],'id');
                
                foreach ($soncateData as $key => $value) {
                    if(!empty( $value['id']))
                        $ids .= $value['id'] . ',';
                }
                $ids = rtrim($ids,',');

                $where['categoryid'] = array('in',$ids);
            }else{
                $where['categoryid'] = $category_son;
            }
        }

        //查询
        $where['businessid'] = $this->businessid;
        

        
        $where = $this->searchWhere([
                "productname"=>"like",
                "enable" => "=",
                "checksatus" => "=",
                "addtime" => "times",
            ],$where);

      
        
        //商品列表
        $list = Model::ins("ProProduct")->pageList($where,'*','id desc');
      
        foreach ($list['list']  as $pid => $product){
       
            $proBusinessCategory  = Model::ins("BusBusinessCategory")->getById($product['businesscategoryid']);
           
            $list['list'][$pid]['businesscategoryname'] = $proBusinessCategory['category_name'];
        }
 
        
        $viewData =[ 
                'businessCategoryArr' => $businessCategoryArr,
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
                'paramString' => $paramString
            ];

        return $this->view($viewData);

    }

    /**
     * [editProductAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-17T19:24:51+0800
     * @return   [type]                   [description]
     */
    public function editProductAction(){

        $goodsid=$this->getParam('goodsid');

        if(empty($goodsid))
            $this->showError('请选择需要编辑的商品');
        $res=array();

        //商品基础信息
        $ProProduct = Model::ins('ProProduct');
        $productinfo = $ProProduct->getRow(['id'=>$goodsid]);
      

        $ProProductInfo = Model::ins('ProProductInfo');
        $productinfo['description'] = $ProProductInfo->getRow(['id'=>$goodsid],'description')['description'];
        $productinfo['description'] = textimg($productinfo['description']);
       
        //商品规格数据
        $ProProductSpec = Model::ins('ProProductSpec');
        //规格数据
        $spec_array = $ProProductSpec->getList(["productid"=>$goodsid]);

        $sp_value = array();
        $sp_name=array();
        $ProProductPhoto = Model::ins('ProProductPhoto');
        $photos_arr = '';
        $photos_data = $ProProductPhoto->getList(['productid'=>$goodsid],'productpic');
        //print_r($photos_data);
        foreach ($photos_data as $key => $value) {
            $photos_arr .= $value['productpic'].',';
        }
        $photos_arr = rtrim($photos_arr,',');

          $moduleid=$productinfo['moduleid'];

        if(!isset($moduleid)||empty($moduleid))
        {
            return json_encode('产品添加需要先选好商品类型');
        }
        
        //查询上一步选中的类型
        $mo_where['id']=$moduleid;
        $moduleData=Model::ins("ProModule")->getRow($mo_where);
        //$res['module']=$moduleData;

        //查询关联的规格
        //$mor_sp_where['type']=1;
        $mor_sp_where['module_id']=$moduleid;
        $spec=array();
        $attribute=array();
        $specCount=0;
        $moduleRelationData=Model::ins("ProModuleRelation")->getList($mor_sp_where);
     
        //print_r($moduleRelationData);
        foreach ($moduleRelationData as $key => $value) {
            if($value['type']==1)
            {
                $specData=Model::ins("ProSpec")->getRow(array('id'=>$value['obj_id']));
                if(!empty($specData))
                {
                    $specData['itemValue']=Model::ins("ProSpecValue")->getList(array('spec_id'=>$value['obj_id']));
                    $spec[$specData['id']]=$specData;
                }
                
            }
            
        }
        //print_r($spec);
       
        if(is_array($spec_array) && !empty($spec_array)){

            $spec_checked = [];

            foreach($spec_array as $k=>$v){
               
                $a = json_decode($v['productspec'], true);
             
                //print_r($a);
                if(!empty($a)){
                    foreach($a as $key=>$val){
                        $spec_checked[$key]['id'] = $key;
                        $spec_checked[$key]['name'] = $val;

                        //print_r($key);
                        //查询规格值数据的规格数据
                        $specValueData=Model::ins("ProSpecValue")->getRow(array('id'=>$key));
                       
                        if(!empty($specValueData['spec_value_name']))
                            $sp_name[$specValueData['spec_id']]=$specValueData['spec_value_name'];
                    }
                    $matchs = array_keys($a);
                    sort($matchs);

                    $sid = str_replace ( ',', '_', implode ( ',', $matchs ) );

                 
                    $sp_value ['i_' . $sid . '|id'] = $v['id'];
                    $sp_value ['i_' . $sid . '|discount'] = $v['discount'];
                    $sp_value ['i_' . $sid . '|saleprice'] = DePrice($v['saleprice']);
                    $sp_value ['i_' . $sid . '|prouctprice'] = DePrice($v['prouctprice']);
                    $sp_value ['i_' . $sid . '|supplyprice'] = DePrice($v['supplyprice']);
                    $sp_value ['i_' . $sid . '|bullamount'] = DePrice($v['bullamount']);
                    $sp_value ['i_' . $sid . '|productstorage'] = $v['productstorage'];
                    $sp_value ['i_' . $sid . '|sku'] = $v['sku'];
                    $sp_value ['i_' . $sid . '|image'] = $v['productimage'];
                }
            }
          
            $res['spec_checked']=$spec_checked;


        } 
        //print_r($res['spec_checked']);
        $spec_arr_td = [];
        $spec_arr = [];
        $spec_id  = [];
        foreach($spec_array as $k=>$v){
            $spec_value = json_decode($v['spec'],true);
            $productspec_value = json_decode($v['productspec'],true);

            $productspec_value_new = [];
            foreach($productspec_value as $id=>$productspec_value_value){
                $productspec_value_new[] = [
                    "id"=>$id,
                    //"specvalue"=>$productspec_value_value,
                ];

                $spec_id[] = $id;
            }

            $spec_spec = [];
            foreach($spec_value as $i=>$spec_value_value){
                $spec_value[$i]['id'] = $productspec_value_new[$i]['id'];

                $spec_arr[$spec_value_value['name']][$productspec_value_new[$i]['id']] = [
                    "id"=>$productspec_value_new[$i]['id'],
                    "spec_value_name"=>$spec_value_value['value'],                       
                ];
                if(empty($spec_arr_td[$spec_value_value['name']]))
                    $spec_arr_td[$spec_value_value['name']] = $spec_value_value['name'];

                $spec_spec[$spec_value_value['name']] = $spec_value_value['value'];
            }

            $spec_array[$k]['spec_spec'] = $spec_spec;
            //$spec_array[$k]['productimage'] = Img::url($v['productimage'],300,300);
            $spec_array[$k]['prouctprice']  = DePrice($v['prouctprice']);
            $spec_array[$k]['saleprice']  = DePrice($v['saleprice']);
            $spec_array[$k]['supplyprice']  = DePrice($v['supplyprice']);
            $spec_array[$k]['bullamount']  = DePrice($v['bullamount']);

        } 

        
        
        //echo "=================";
        $tmp_spec = [];
        // 商品里面的规格内容
        foreach($spec_arr as $pro_spec_key=>$pro_spec_value){

            $ishave = false; // 判断商品里面的规格值是否在类型规格里面存在
            foreach($spec as $spec_key=>$spec_value){
                
                if($spec_value['specname']==$pro_spec_key){
                    
                    
                    foreach($pro_spec_value as $pro_spec_value_v){
                        $item_flag = false;
                        foreach($spec_value['itemValue'] as $item_key=>$item_value){
                            if($item_value['id']==$pro_spec_value_v['id']){
                                $item_flag = true;
                                break;
                            }
                        }
                        if($item_flag==false){
                            $spec[$spec_key]["itemValue"][] = $pro_spec_value_v;
                        }
                    }
                    
                    
                    $ishave = true;
                    break;
                }
            }

            // 该规格值不存在原来的规格内容里面
            if($ishave==false){
                $tmp_spec[] = [
                    "id"=>$pro_spec_key,
                    "specname"=>$pro_spec_key,
                    "itemValue"=>$pro_spec_value,
                ];
            }
        }

        $spec = array_merge($spec,$tmp_spec);

        //print_r($spec_id);
        //echo "#####################";
        //print_r($spec_array);
        /*

        } 
       

        } 
        


        $new_spec = [];
        $count = 0 ; 
        foreach ($spec_arr as $spkey => $spvalue) {

            $specData = Model::ins('ProSpec')->getRow(['specname'=>$spkey,'businessid'=>$productinfo['businessid']],'id,specname');

            if(!empty($specData)){
                $itemValue = Model::ins('ProSpecValue')->getList(['spec_id'=>$specData['id']],'id,spec_value_name');
                foreach ($itemValue as $key => $value) {
                    $itemId_arr[] = $value['id'];
                }
                // print_r($itemId_arr);
                // print_r($spvalue);

                foreach ($spvalue as $spvkey => $spvvalue) {
                    if(!in_array($spvkey, $itemId_arr)){
                        $new[$spvkey] =  $spvalue[$spvkey];
                        $itemValue = array_merge($itemValue,$new);
                    }
                }
                
                $specData['itemValue'] =  $itemValue;
                $specData['_id'] = $specData['id'];
                $specData['id'] = $count;
               
                $new_spec[] = $specData;
            }else{

                $specData['id'] = $count;
                $specData['specname']   = $spkey;
                $specData['itemValue'] = $spvalue;
                $new_spec[] = $specData;
            }
            $count++;
        }
      */
        $res['sp_value']=$sp_value;
        //$res['sp_name']=$sp_name;
        //count($sp_name);
        $res['sign_i']=count($spec);
       

        $proCategorydata =  ProductModel::getCategoryData();
        $proCategorydata = BusinessCategoryModel::tree($proCategorydata,'name');
      
        foreach ($proCategorydata as $key => $value) {
            $optionProCate[$value['id']] = $value['_name'];
        }

        $categoryData = Model::ins('BusBusinessCategory')->getList(['businessid'=>$this->businessid,'is_delete'=>0]);
        $categoryData = BusinessCategoryModel::tree($categoryData,'category_name');
        
        foreach ($categoryData as $key => $value) {
            $optionCate[$value['id']] = $value['_category_name'];
        }

        //品牌
        $brandArr=array();
        $brandData=Model::ins("ProBrand")->getList(['isdelete'=>0],"id,brandname","sort desc");
        $brandArr[0] = '其他';
        foreach ($brandData as $key => $value) {
            $brandArr[$value['id']]=$value['brandname'];
        } 
       

         //form验证token
        $formtoken = $this->Btoken('Product-Index-editProduct');
     
        
      
        $parent_cate_id = Model::ins('ProCategory')->getRow(['id'=>$productinfo['categoryid']],'parent_id')['parent_id'];
       

        $res['parent_cate_id'] = $parent_cate_id;
        $res['optionCate']= $optionCate;
        $res['optionProCate']= $optionProCate;
        $res['module']=$moduleData;
        $res['spec']=$spec;
        $res['spec_arr_td']=$spec_arr_td;
        $res['spec_array'] = $spec_array;
        $res['spec_id'] = $spec_id;
        $res['attribute']=$attribute;
        $res['brand']=$brandArr;
        $res['formtoken']=$formtoken;
        $res['productinfo']=$productinfo;
        $res['photos'] = $photos_arr;
        // $res['transport_list'] = $transport_list;

        $price_type = Model::ins("BusBusiness")->getRow(['id'=>$this->businessid],'price_type');
        $type_arr = explode(',', $price_type['price_type']);
        $res['type_arr'] = $type_arr;


        return $this->view($res);


    }

    /**
     * [lookProductAction 查看]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-05T15:41:09+0800
     * @return   [type]                   [description]
     */
    public function lookProductAction(){

        $goodsid=$this->getParam('goodsid');

        if(empty($goodsid))
            $this->showError('请选择需要编辑的商品');
        $res=array();

        //商品基础信息
        $ProProduct = Model::ins('ProProduct');
        $productinfo = $ProProduct->getRow(['id'=>$goodsid]);
      

        $ProProductInfo = Model::ins('ProProductInfo');
        $productinfo['description'] = $ProProductInfo->getRow(['id'=>$goodsid],'description')['description'];
        $productinfo['description'] = textimg($productinfo['description']);
       
        //商品规格数据
        $ProProductSpec = Model::ins('ProProductSpec');
        //规格数据
        $spec_array = $ProProductSpec->getList(["productid"=>$goodsid]);

        $sp_value = array();
        $sp_name=array();
        $ProProductPhoto = Model::ins('ProProductPhoto');
        $photos_arr = '';
        $photos_data = $ProProductPhoto->getList(['productid'=>$goodsid],'productpic');
        //print_r($photos_data);
        foreach ($photos_data as $key => $value) {
            $photos_arr .= $value['productpic'].',';
        }
        $photos_arr = rtrim($photos_arr,',');

         $moduleid=$productinfo['moduleid'];

        if(!isset($moduleid)||empty($moduleid))
        {
            return json_encode('产品添加需要先选好商品类型');
        }
        
        //查询上一步选中的类型
        $mo_where['id']=$moduleid;
        $moduleData=Model::ins("ProModule")->getRow($mo_where);
        //$res['module']=$moduleData;

        //查询关联的规格
        //$mor_sp_where['type']=1;
        $mor_sp_where['module_id']=$moduleid;
        $spec=array();
        $attribute=array();
        $specCount=0;
        $moduleRelationData=Model::ins("ProModuleRelation")->getList($mor_sp_where);
     

        foreach ($moduleRelationData as $key => $value) {
            if($value['type']==1)
            {
                $specData=Model::ins("ProSpec")->getRow(array('id'=>$value['obj_id']));
                if(!empty($specData))
                {
                    $specData['itemValue']=Model::ins("ProSpecValue")->getList(array('spec_id'=>$value['obj_id']));
                    $spec[$specData['id']]=$specData;
                }
                
            }
        }


        if(is_array($spec_array) && !empty($spec_array)){

            $spec_checked = [];

            foreach($spec_array as $k=>$v){
                $a = json_decode($v['productspec'], true);

              
                if(!empty($a)){
                    foreach($a as $key=>$val){
                        $spec_checked[$key]['id'] = $key;
                        $spec_checked[$key]['name'] = $val;

                        //print_r($key);
                        //查询规格值数据的规格数据
                        $specValueData=Model::ins("ProSpecValue")->getRow(array('id'=>$key));
                        
                        if(!empty($specValueData['spec_value_name']))
                            $sp_name[$specValueData['spec_id']]=$specValueData['spec_value_name'];
                    }
                    $matchs = array_keys($a);
                    sort($matchs);

                    $sid = str_replace ( ',', '_', implode ( ',', $matchs ) );

                 
                    $sp_value ['i_' . $sid . '|id'] = $v['id'];
                    $sp_value ['i_' . $sid . '|discount'] = $v['discount'];
                    $sp_value ['i_' . $sid . '|saleprice'] = DePrice($v['saleprice']);
                    $sp_value ['i_' . $sid . '|prouctprice'] = DePrice($v['prouctprice']);
                    $sp_value ['i_' . $sid . '|supplyprice'] = DePrice($v['supplyprice']);
                    $sp_value ['i_' . $sid . '|bullamount'] = DePrice($v['bullamount']);
                    $sp_value ['i_' . $sid . '|productstorage'] = $v['productstorage'];
                    $sp_value ['i_' . $sid . '|sku'] = $v['sku'];
                    $sp_value ['i_' . $sid . '|image'] = $v['productimage'];
                }
            }
          
            $res['spec_checked']=$spec_checked;


        }
        
        $spec_arr = [];
        foreach($spec_array as $k=>$v){
            $spec_value = json_decode($v['spec'],true);
            $productspec_value = json_decode($v['productspec'],true);

            $productspec_value_new = [];
            foreach($productspec_value as $id=>$productspec_value_value){
                $productspec_value_new[] = [
                    "id"=>$id,
                    //"specvalue"=>$productspec_value_value,
                ];
            }

            foreach($spec_value as $i=>$spec_value_value){
                $spec_value[$i]['id'] = $productspec_value_new[$i]['id'];

                $spec_arr[$spec_value_value['name']][$productspec_value_new[$i]['id']] = [
                    "id"=>$productspec_value_new[$i]['id'],
                    "spec_value_name"=>$spec_value_value['value'],                       
                ];
            }
        } 
       
        $new_spec = [];
        $count = 0 ; 
        foreach ($spec_arr as $spkey => $spvalue) {
            $specData = Model::ins('ProSpec')->getRow(['specname'=>$spkey,'businessid'=>$productinfo['businessid']],'id,specname');
            // print_r($specData);
            if(!empty($specData)){
                $itemValue = Model::ins('ProSpecValue')->getList(['spec_id'=>$specData['id']],'id,spec_value_name');
                
                foreach ($itemValue as $key => $value) {
                    $itemId_arr[] = $value['id'];
                }
               
                foreach ($spvalue as $spvkey => $spvvalue) {
                    if(!in_array($spvkey, $itemId_arr)){
                        $new[$spvkey] =  $spvalue[$spvkey];
                        $itemValue = array_merge($itemValue,$new);
                    }
                }
                
                $specData['itemValue'] =  $itemValue;
                $specData['id'] = $count;
                $new_spec[] = $specData;
            }else{

                $specData['id'] = $count;
                $specData['specname']   = $spkey;
                $specData['itemValue'] = $spvalue;
                $new_spec[] = $specData;
            }
            $count++;
        }

        $res['sp_value']=$sp_value;
        $res['sp_name']=$sp_name;
        //count($sp_name);
        $res['sign_i']=count($sp_name);


        $proCategorydata =  ProductModel::getCategoryData();
        $proCategorydata = BusinessCategoryModel::tree($proCategorydata,'name');
      
        foreach ($proCategorydata as $key => $value) {
            $optionProCate[$value['id']] = $value['_name'];
        }

        $categoryData = Model::ins('BusBusinessCategory')->getList(['businessid'=>$this->businessid,'is_delete'=>0]);
        $categoryData = BusinessCategoryModel::tree($categoryData,'category_name');
        
        foreach ($categoryData as $key => $value) {
            $optionCate[$value['id']] = $value['_category_name'];
        }

        //品牌
        $brandArr=array();
        $brandData=Model::ins("ProBrand")->getList(['isdelete'=>0],"id,brandname","sort desc");
        $brandArr[0] = '其他';
        foreach ($brandData as $key => $value) {
            $brandArr[$value['id']]=$value['brandname'];
        } 
        

         //form验证token
        $formtoken = $this->Btoken('Product-Index-editProduct');
        
      
        $parent_cate_id = Model::ins('ProCategory')->getRow(['id'=>$productinfo['categoryid']],'parent_id')['parent_id'];
       
        //print_r($transport_list);
        $res['parent_cate_id'] = $parent_cate_id;
        $res['optionCate']= $optionCate;
        $res['optionProCate']= $optionProCate;
        $res['module']=$moduleData;
        $res['spec']=$new_spec;
        $res['attribute']=$attribute;
        $res['brand']=$brandArr;
        $res['formtoken']=$formtoken;
        $res['productinfo']=$productinfo;
        $res['photos'] = $photos_arr;
       

        $price_type = Model::ins("BusBusiness")->getRow(['id'=>$this->businessid],'price_type');
        $type_arr = explode(',', $price_type['price_type']);
        $res['type_arr'] = $type_arr;


        return $this->view($res);
        
    }

    /**
     * [setEableAction 上下架]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-10T21:51:54+0800
     */
    public function setEableAction(){
        $ids = $this->getParam('ids');
        $id_arr = explode(',', $ids);
        //$goodsid = $this->getParam('goodsid');
        $enable = $this->getParam('enable');

        $params = $this->params;
        unset($params['ids']);
        unset($params['goodsid']);
        unset($params['enable']);
        foreach ($params as $k => $v) {
            $paramString .= "&$k=$v";
        }

        $url = '/Product/Index/list'."?".$paramString;
        $i = 0;
        $errorName = '';
        foreach ($id_arr as $key => $goodsid) {
            
            if(empty($goodsid))
                $this->showError('请选择需要操作的商品');

            $proData = Model::ins('ProProduct')->getRow(['id'=>$goodsid],'id,productname,enable');

            if(empty($proData))
                $this->showError('商品信息不存在');

            if($proData['enable'] == 2){

                $errorName .= '<font color="green">'.$proData['productname'].'</font>&nbsp|&nbsp';
                //$this->showError('<font color="green">'.$proData['productname'].'</font>已上架不能删除');
            }else{
            
            
                 //商品基础信息
                $ProProduct = Model::ins('ProProduct');
                if($enable == -1){
                    $updata = [
                            'enable'=>$enable,
                            'checksatus'=>0
                    ];
                }else{
                    $updata = [
                            'enable'=>$enable,
                    ];
                }
               
                $ProProduct->update($updata,['id'=>$goodsid]);
                
                $updatas = [
                    'enable'=> (string) $enable,
                ];

             
                Model::Es("ProProduct")->update($updatas,['id'=>$goodsid]);
                $i++;
            }

        }
        if(!empty($errorName)){
            $msg = '其中'.rtrim($errorName,'&nbsp|&nbsp').'为违规下架商品不能批量上下架';
        }

        $this->showSuccess('成功操作'.$i.'条记录'.$msg,$url,10);
        //$this->showSuccess('操作成功',$url,10);
    }

    /**
     * [lookDisenbaleReasonAction 查看違規理由]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-10T21:57:30+0800
     * @return   [type]                   [description]
     */
    public function lookDisenbaleReasonAction(){

        $goodsid = $this->getParam('goodsid');
        $enable = $this->getParam('enable');

        if(empty($goodsid))
            $this->showError('请选择需要操作的商品');
         //商品基础信息
        $ProProduct = Model::ins('ProProduct');
        $proData = $ProProduct->getRow(['id'=>$goodsid]);
        //print_r($proData);
        $viewData = [
            'proData'=>$proData
        ];
        return $this->view($viewData);
    }

    /**
     * [checkPriceAction 价格公式 生成价格]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-27T16:04:47+0800
     * @return   [type]                   [description]
     */
    public function formatPriceAction(){
        $post = $this->params;
        $data = ProductModel::formatPrice($post);
        echo json_encode($data);
    }

    /**
     * [addProductAction 添加产品第二部]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-16T17:33:20+0800
     */
    public function addProductSteponeAction(){
        
        $moduleid=$this->getParam('moduleid');

        if(!empty($moduleid)&&$moduleid!=0)
        {
            // return "<script>window.close();parent.parent.goto2('/product/index/addproduct?moduleid={$moduleid}')</script>";
            return "<script>parent.parent.goto2('/product/index/addProductSetptwo?moduleid={$moduleid}');parent.parent.layer.close(parent.parent.layer.getFrameIndex(parent.window.name));</script>";
            //$this->showSuccessPage("进入添加产品下一步","/product/index/addproduct?moduleid=".$moduleid);
        }

        

        $moduleData = Model::ins('ProModule')->getList(['isdelete'=>[">=",0],'businessid'=>$this->businessid]); //,'businessid'=>$this->businessid
        $moduleid = [];
        foreach ($moduleData as $key => $value) {
            $moduleid[$value['id']] = $value['modulename'];
        }
        $action = '/Product/Index/addProductStepone';
        $viewData = [
            "moduleid"=>$moduleid,
            'action'=>$action,
            'title'=>'选择商品属性'
        ];
        return $this->view($viewData);
    }


    /**
     * [addProductSetptwoAction 添加产品第二步]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-16T17:35:12+0800
     */
    public function addProductSetptwoAction(){
    


        $res=array();
        $moduleid=$this->getParam('moduleid');
        if(!isset($moduleid)||empty($moduleid))
        {
            return json_encode('产品添加需要先选好商品类型');
        }
        //查询上一步选中的类型
        $mo_where['id']=$moduleid;
        $moduleData=Model::ins("ProModule")->getRow($mo_where);
        //$res['module']=$moduleData;

        //查询关联的规格
        //$mor_sp_where['type']=1;
        $mor_sp_where['module_id']=$moduleid;
        $spec=array();
        $attribute=array();
        $specCount=0;
        $moduleRelationData=Model::ins("ProModuleRelation")->getList($mor_sp_where);
     

        foreach ($moduleRelationData as $key => $value) {
            if($value['type']==1)
            {
                $specData=Model::ins("ProSpec")->getRow(array('id'=>$value['obj_id']));
                if(!empty($specData))
                {
                    $specData['itemValue']=Model::ins("ProSpecValue")->getList(array('spec_id'=>$value['obj_id']));
                    $specData['_id'] = $specData['id'];
                    $spec[$specData['id']]=$specData;
                }
                
            }else if($value['type']==2){
                //关联属性
                $attributeData=Model::ins("ProAttribute")->getRow(array('id'=>$value['obj_id']));
                if(!empty($attributeData))
                {
                    $attributeData['itemValue']=Model::ins("ProAttributeValue")->getList(array('attr_id'=>$value['obj_id']));
                    $attribute[]=$attributeData;
                }
            }else if($value['type']==3){
                $cateData = Model::ins('BusBusinessCategory')->getRow(['id'=>$value['obj_id']]);
                $categoryData[] = $cateData;
            }
            
        }
     

        $proCategorydata =  ProductModel::getCategoryData();
        $proCategorydata = BusinessCategoryModel::tree($proCategorydata,'name');
      
        foreach ($proCategorydata as $key => $value) {
            $optionProCate[$value['id']] = $value['_name'];
        }
       
        $categoryData = Model::ins('BusBusinessCategory')->getList(['businessid'=>$this->businessid,'is_delete'=>0]);

        $categoryData = BusinessCategoryModel::tree($categoryData,'category_name');

        foreach ($categoryData as $key => $value) {
            $optionCate[$value['id']] = $value['_category_name'];
        }

        //品牌
        $brandArr=array();
        $brandData=Model::ins("ProBrand")->getList(['isdelete'=>0],"id,brandname","sort desc");
        $brandArr[0] = '其他';
        foreach ($brandData as $key => $value) {
            $brandArr[$value['id']]=$value['brandname'];
        } 
        
        //form验证token
        $formtoken = $this->Btoken('Product-Index-addProductSetptwo');
        
        $specname = [];
        foreach ($spec as $key => $value) {
            $specname[$key]['specname'] = $value['spec_name'];
        }
        //print_r($specname);
        $res['specname']=$specname;

        $res['optionCate']= $optionCate;
        $res['optionProCate']= $optionProCate;
        $res['sign_i']=count($spec);
        $res['module']=$moduleData;
        $res['spec']=$spec;
        $res['attribute']=$attribute;
        $res['brand']=$brandArr;
        $res['formtoken']=$formtoken;

        $price_type = Model::ins("BusBusiness")->getRow(['id'=>$this->businessid],'price_type');
        $type_arr = explode(',', $price_type['price_type']);
        $res['type_arr'] = $type_arr;


        return $this->view($res);

    }


    /**
     * [doaddOreditProductAction 添加或修改商品操作]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-17T15:24:49+0800
     * @return   [type]                   [description]
     */
    public function doaddOreditProductAction(){

        
            if($this->businessid < 0)
                $this->showError('商家信息错误','/login');
            

            $post = $this->params;

           
            $post['categoryid'] = $post['categoryid_son'];

            $post['brandid'] = empty($post['brandid']) ? 0 : $post['brandid'];

            $id = $post['id'];


            if(!empty($id)){
                $url = '/Product/Index/editProduct?goodsid='.$id;
            }else{
                $url = 'addProductSetptwo?moduleid='.$post['moduleid'];
            }

            if(empty($post['categoryid'])){
                $this->showError("请选择分类");
            }

         
            if(empty($post['sp_val'])){
                 $this->showError("请选择商品规格");
            }
            // foreach ($post['sp_name'] as $key => $value) {
            //     if(empty($post['sp_val'][$key])){
            //          $this->showError("请勾选".$value."属性的值");
            //     }
            // }

            foreach ($post['spec'] as $key => $value) {

                //获取价格
                $specformart = [
                    'supplyprice' =>$value['supplyprice'],
                    'saleprice' =>$value['saleprice'],
                    'settle_cycle' =>$post['saletype']
                ];

                $specformartPrice = ProductModel::formatPrice($specformart); 

                $specprouctprice = EnAdminPrice($specformartPrice['prouctprice']);
                $specbullamount = EnAdminPrice($specformartPrice['bullamount']);
               

                if($specbullamount <= 0 && $specprouctprice <= 0){
                    $this->showError("价格错误,请重新填写数据");
                }
                if($value['productstorage'] <= 0){
                    $this->showError("请填写库存");
                }
            }

            //轮播图 商品详情
            $photos = $post['photos'];
            //print_r($photos);
            $photos = explode(',', $photos);

            $post['bullamount'] =  empty($post['bullamount']) ? 568 : $post['bullamount'];
            $post['prouctprice'] =  empty($post['prouctprice']) ? 568 : $post['prouctprice'];
            //获取价格
            $formart = [
                'supplyprice' => empty($post['supplyprice']) ? 568 : $post['supplyprice'],
                'saleprice' => empty($post['saleprice'])  ? 789 : $post['saleprice'],
                'settle_cycle' =>$post['saletype']
            ];
            
            if($post['bullamount'] <= 0 && $post['prouctprice'] <= 0){
                $this->showError("价格错误,请重新填写数据");
            }

            $formartPrice = ProductModel::formatPrice($formart); 

            $prouctprice = EnAdminPrice($formartPrice['prouctprice']);
            $bullamount = EnAdminPrice($formartPrice['bullamount']);
            $discount = $formartPrice['discount'];
            $saleprice = EnAdminPrice($post['saleprice']);
            $supplyprice = EnAdminPrice($post['supplyprice']);
            $saletype = $post['saletype'];

            // if($bullamount <= 0 && $prouctprice <= 0){
            //     $this->showError("价格错误,请重新填写数据");
            // }
        
            // print_r($post);
            // exit();
            $ProProduct = Model::ins('ProProduct'); //商品基本信息
            $ProProductSpec = Model::ins('ProProductSpec');//商品sku信息（规格）
            $ProProductSpecValue = Model::ins('ProProductSpecValue');//商品所有sku组合信息
            $ProProductInfo = Model::ins('ProProductInfo');//商品详情等扩展信息
            $ProSpec = Model::ins('ProSpec');//商品规格
            $ProSpecValue = Model::ins('ProSpecValue');//商品规格对应值
            $ProModuleRelation = Model::ins('ProModuleRelation');
            $ProProductPhoto = Model::ins('ProProductPhoto');
            $ProCategory = Model::ins('ProCategory');//商品分类
            $ProCategory = Model::ins('ProCategory');//商品分类
            $BusBusinessCategory = Model::ins('BusBusinessCategory'); //商家分类

            //自动验证表单 需要修改form对应表名
            $ProProductAdd = new ProProductAdd();
            $ProProductSpecValueAdd = new ProProductSpecValueAdd();
            $ProProductSpecAdd = new ProProductSpecAdd();
            // var_dump(gettype($this->businessid));
            //  var_dump(gettype($post['enable']));
            if($post['freight'] == 1){
                $post['g_freight'] = 0;
            }else{
                $post['transportid'] = 0;
            }
            if(empty($id)){
               
                $procategoryname = $ProCategory->getRow(['id'=>$post['categoryid']],'name')['name'];
                $buscategoryname = $BusBusinessCategory->getRow(['id'=>$post['businesscategoryid']],'category_name')['category_name'];

                $addProData = [
                    'spu'=>$ProProduct->getSPuNo(),
                    'businessid' =>$this->businessid,
                    'categoryid'=>$post['categoryid'],
                    'categoryname' => $procategoryname,
                    'businesscategoryid'=>$post['businesscategoryid'],
                    'productname' => $post['productname'],
                    'prouctprice' => (int)  $prouctprice,
                    'supplyprice' => (int)  $supplyprice,
                    'bullamount' => (int)  $bullamount,
                    'saleprice' => (int) $saleprice,
                    'saletype' => (int)  $saletype,
                    'discount' => (int)  $discount,
                    //'productstorage' => $post['productstorage'],
                    'thumb' => $post['thumb'],
                    'weight' => $post['weight'],
                    'weight_gross'=> $post['weight_gross'],
                    'volume' => $post['volume'],
                    'moduleid' => $post['moduleid'],
                    'brandid' => $post['brandid'],
                    'enable' => $post['enable'],
                    'freight' => empty($post['g_freight']) ? 0 : $post['g_freight'],
                    'transportid' => empty($post['transportid']) ? 0 : $post['transportid'],
                    'checksatus' => 0,
                    'addtime'=>date('Y-m-d H:i:s'),
                    'enabletime'=>date('Y-m-d H:i:s')
                ]; 
                
                if(!$ProProductAdd->isValid($addProData)){//验证是否正确 

                    $this->showError($ProProductAdd->getErr());//提示报错信息
                }else{
                    $data = $ProProduct->insert($addProData);
                    $pid = $data;
                  
                }

            }else{

                $rowPro = Model::ins("ProProduct")->getRow(["id"=>$id]);

                if($rowPro['businessid']!=$this->businessid){
                    $this->showError("无权限操作");
                    exit;
                }
               
                $procategoryname = $ProCategory->getRow(['id'=>$post['categoryid']],'name')['name'];
                $pid = $id;
                $updateProData = [
                    'businessid' =>$this->businessid,
                    'categoryid'=>$post['categoryid'],
                    'categoryname' => $procategoryname,
                    'businesscategoryid'=>$post['businesscategoryid'],
                    'productname' => $post['productname'],
                    'prouctprice' => (int)  $prouctprice,
                    'supplyprice' => (int)  $supplyprice,
                    'bullamount' => (int)  $bullamount,
                    'saleprice' => (int) $saleprice,
                    'saletype' => (int)  $saletype,
                    'discount' => (int)  $discount,
                    //'productstorage' => $post['productstorage'],
                    'thumb' => $post['thumb'],
                    'weight' => $post['weight'],
                    'weight_gross'=> $post['weight_gross'],
                    'volume' => $post['volume'],
                    'moduleid' => $post['moduleid'],
                    'brandid' => $post['brandid'],
                    'enable' => $post['enable'],
                    'freight' => empty($post['g_freight']) ? 0 : $post['g_freight'],
                    'transportid' => empty($post['transportid']) ? 0 : $post['transportid'],
                    'checksatus' => 0
                ]; 

                if(empty($rowPro['addtime']))
                    $updateProData['addtime'] = date('Y-m-d H:i:s');
                
                if(empty($rowPro['enabletime']))
                    $updateProData['enabletime'] = date('Y-m-d H:i:s');

                if(!$ProProductAdd->isValid($updateProData)){//验证是否正确 
                    $this->showError($ProProductAdd->getErr());//提示报错信息
                }else{
                   
                    //$updata = $ProProduct->update($updateProData,['id'=>$id]);
                    $ProProduct->update($updateProData,['id'=>$id]);
                }
                
            }

            $ProProductPhoto->delete(['productid'=>$pid]);

            foreach ($photos as $key => $value) {
              
                $ProProductPhoto->insert([
                    'productpic'=>$value,
                    'productid'=>$pid,
                    'sort'=>$key,
                    'addtime'=>date('Y-m-d H:i:s')
                ]);
            }

         
            //组合所有规格
            // $allSpecData = [];
            // foreach ($post['sp_val'] as $key => $value) {
            //     $allSpecData[$key] = $post['sp_name'][$key];
            // }

            
            // //组合所有规格的值
            // $specValue = [];
            
            // foreach ($allSpecData as $ka => $va) {
            //     $tmp = [];
            //     $specValueData = $ProSpecValue->getList(['spec_id'=>$ka],'id,spec_value_name');
            //     foreach ($specValueData as $ko => $vo) {
            //         $tmp[$vo['id']] = $vo['spec_value_name'];
            //     }
            //     $specValue[$ka] = $tmp; 
            // }
           
            
            // $addSpecValueData = [
            //     'productid'=>$pid,
            //     // 'spec_name'=> json_encode($specname,JSON_UNESCAPED_UNICODE),
            //     'spec_name'=> json_encode($allSpecData,JSON_UNESCAPED_UNICODE),
            //     'spec_vlaue'=> json_encode($specValue,JSON_UNESCAPED_UNICODE),
            // ];

            $addSpecValueData = [
                'productid'=>$pid,
                // 'spec_name'=> json_encode($specname,JSON_UNESCAPED_UNICODE),
                'spec_name'=> json_encode($post['sp_name'],JSON_UNESCAPED_UNICODE),
                'spec_vlaue'=> json_encode($post['sp_val'],JSON_UNESCAPED_UNICODE),
            ];
           
            $SpecValueData = $ProProductSpecValue->getRow(['productid'=>$pid]);
            
            if(empty($SpecValueData)){

                if(!$ProProductSpecValueAdd->isValid($addSpecValueData)){//验证是否正确 
                    $this->showError($ProProductSpecValueAdd->getErr());//提示报错信息
                }else{
                    $ProProductSpecValue->insert($addSpecValueData);
                }

            }else{

                if(!$ProProductSpecValueAdd->isValid($addSpecValueData)){//验证是否正确 
                    $this->showError($ProProductSpecValueAdd->getErr());//提示报错信息
                }else{
                    $ProProductSpecValue->update($addSpecValueData,['productid'=>$pid]);
                }

            }

            if(empty($id)){
              
                $prouctDescription = [
                    'id' => $pid,
                    'description' => $post['description']
                ];
                $ProProductInfo->insert($prouctDescription);

                if(!empty($post['spec'])){
                    
                    foreach ($post['spec'] as $key => $value) {
                        
                        if(!empty($value['sp_value'] && is_array($value['sp_value']))){
                            $spec = [];
                            $specname = [];
                            
                            $i = 0;
                            foreach ($value['sp_value'] as $k => $v) {
                                $specValueData = $ProSpecValue->getRow(['id'=>$k],'spec_id');
                                $specData = $ProSpec->getRow(['id'=>$specValueData['spec_id']],'specname'); 
                                $spec[$i]['name'] = $specData['specname'];
                                $spec[$i]['value'] = $v; 
                                $i++;
                                $specname[$specValueData['spec_id']] = $specData['specname'];
                            
                            }

                        }
                        
                        //获取价格
                        $specformart = [
                            'supplyprice' =>$value['supplyprice'],
                            'saleprice' =>$value['saleprice'],
                            'settle_cycle' =>$post['saletype']
                        ];

                        $specformartPrice = ProductModel::formatPrice($specformart); 

                        $specprouctprice = EnAdminPrice($specformartPrice['prouctprice']);
                        $specbullamount = EnAdminPrice($specformartPrice['bullamount']);
                        $specdiscount = $specformartPrice['discount'];
                        $specsaleprice = EnAdminPrice($value['saleprice']);
                        $specsupplyprice = EnAdminPrice($value['supplyprice']);
                        $specsaletype = $post['saletype'];

                        if($specbullamount <= 0 && $specprouctprice <= 0){
                            $this->showError("价格错误,请重新填写数据");
                        }
                                    
                        $addSpecData = [
                            'sku' =>  $ProProductSpec->getSKuNo(),
                            'productid'=>$data,
                            'productname'=>$post['productname'],
                            'prouctprice' =>  $specprouctprice,
                            'supplyprice' => $specsupplyprice,
                            'bullamount' =>  $specbullamount,
                            'saleprice' => $specsaleprice,
                            'saletype' => $specsaletype,
                            'discount' => $specdiscount,
                            'productstorage'=>$value['productstorage'],
                            'weight'=> $post['weight'],
                            'volume'=> $post['volume'],
                            'weight_gross'=> $post['weight_gross'],
                            'productspec'=> json_encode($value['sp_value'],JSON_UNESCAPED_UNICODE),
                            'spec' => json_encode($spec,JSON_UNESCAPED_UNICODE),
                            'productimage'=>$value['image_upload'],
                            'businessid' =>$this->businessid
                        ];
                      
                        if(!$ProProductSpecAdd->isValid($addSpecData)){//验证是否正确 
                            $this->showError($ProProductSpecAdd->getErr());//提示报错信息
                        }else{
                            $ProProductSpec->insert($addSpecData);
                        }
                        
                    }



                }

            }else{
              
                $prouctDescription = [
                    'description' => $post['description']
                ];

                $ProProductInfo->update($prouctDescription,['id'=>$pid]);
                
                

                if(!empty($post['spec'])){

                   
                    $specDatas = $ProProductSpec->getList(['productid'=>$pid],'id,productspec');
                    

                    foreach ($specDatas as $sk => $sv) {
                        foreach ($post['spec'] as $spkey => $spv){
                            if($sv['productspec'] == json_encode($spv['sp_value'],JSON_UNESCAPED_UNICODE)){
                                unset($specDatas[$sk]);
                            }
                        }
                    }
                   
                  
                    if(!empty($specDatas)){
                        foreach ($specDatas as $key => $value) {
                           $ProProductSpec->delete(['id'=>$value['id']]);
                        }
                    }

                    // $ProProductSpec->delete(['productid'=>$id]);
                    foreach ($post['spec'] as $key => $value) {
                        
                        
                        $tmp_img = explode(',',$value['image']);
                        // print_r($tmp_img);
                        foreach($tmp_img as $mk => $mv) {
                             $value['image'] = $mv;
                        }
                       
                        if(!empty($value['sp_value'] && is_array($value['sp_value']))){
                            $spec = [];
                            
                            $i = 0;
                            foreach ($value['sp_value'] as $k => $v) {
                                $specValueData = $ProSpecValue->getRow(['id'=>$k],'spec_id');
                                $specData = $ProSpec->getRow(['id'=>$specValueData['spec_id']],'specname'); 
                               
                                if(!empty($specData['specname'])){
                                    $spec[$key][$i]['name'] = $specData['specname'];
                                }else{
                                    foreach ($post['sp_val'] as $spvk => $spvv) {
                                        if($v == $spvv[$k]){
                                           
                                            $specname = $post['sp_name'][$spvk];
                                            
                                        }
                                    }
                                    $spec[$key][$i]['name'] = $specname;
                                }
                                $spec[$key][$i]['value'] = $v; 
                                $i++;
                            
                            }

                        }
                       
                       
                        // if(empty($value['goods_id'])){
                            
                        //获取价格
                        $specformart = [
                            'supplyprice' =>$value['supplyprice'],
                            'saleprice' =>$value['saleprice'],
                            'settle_cycle' =>$post['saletype']
                        ];

                        $specformartPrice = ProductModel::formatPrice($specformart); 

                        $specprouctprice = EnAdminPrice($specformartPrice['prouctprice']);
                        $specbullamount = EnAdminPrice($specformartPrice['bullamount']);
                        $specdiscount = $specformartPrice['discount'];
                        $specsaleprice = EnAdminPrice($value['saleprice']);
                        $specsupplyprice = EnAdminPrice($value['supplyprice']);
                        $specsaletype = $post['saletype'];

                        if($specbullamount <= 0 && $specprouctprice <= 0){
                            $this->showError("价格错误,请重新填写数据");
                        }

                        $addSpecData = [
                            'sku' =>  $ProProductSpec->getSKuNo(),
                            'productid'=>$id,
                            'productname'=>$post['productname'],
                            'prouctprice' =>  $specprouctprice,
                            'supplyprice' => $specsupplyprice,
                            'bullamount' => $specbullamount,
                            'saleprice' =>$specsaleprice,
                            'saletype' =>  $specsaletype,
                            'discount' =>  $specdiscount,
                            'productstorage'=>$value['productstorage'],
                            'weight'=> $post['weight'],
                            'volume'=> $post['volume'],
                            'weight_gross'=> $post['weight_gross'],
                            'productspec'=> json_encode($value['sp_value'],JSON_UNESCAPED_UNICODE),
                            'spec' => json_encode($spec[$key],JSON_UNESCAPED_UNICODE),
                            'productimage'=>$value['image'],
                            'businessid' =>$this->businessid
                        ];




                        //print_r($addSpecData);
                        if(!$ProProductSpecAdd->isValid($addSpecData)){//验证是否正确 
                            $this->showErrorPage($ProProductSpecAdd->getErr(),'/Product/Index/editProduct?goodsid='.$pid);//提示报错信息
                        }else{
                            $getSpecData = $ProProductSpec->getRow(['productspec'=>json_encode($value['sp_value'],JSON_UNESCAPED_UNICODE),'productid'=>$pid],'id','id desc');
                            if(empty($getSpecData)){
                                $ProProductSpec->insert($addSpecData);
                            }else{
                                unset($addSpecData['sku']);
                                $ProProductSpec->update($addSpecData,['id'=>$getSpecData['id']]);
                            }
                        }
                      
                    }
                }
            }

             //商品sku 所有情况
            $sku = ProductModel::getProSkuById($pid);
            $sku = $sku['sku'];
            //取出sku最低价
            $min = [];
            $arr = $sku;
            $len = count($arr);
            for ($i=0; $i<$len; $i++){
                if ($i==0){
                    $min = $arr[$i];

                    continue;

                }
                if($min['prouctprice'] > 0){
                    if ($arr[$i]['prouctprice'] < $min['prouctprice']){
                        $min = $arr[$i];
                    }
                }else{
                    if ($arr[$i]['bullamount'] < $min['bullamount']){
                        $min = $arr[$i];
                    }
                }

            }
            
            
            if(!empty($min)){
                $proDatas['prouctprice'] = EnAdminPrice($min['prouctprice']);
                $proDatas['supplyprice'] = EnAdminPrice($min['supplyprice']);
                $proDatas['bullamount'] = EnAdminPrice($min['bullamount']); 
                $proDatas['saleprice'] = EnAdminPrice($min['saleprice']);               
            }
            

            $ProProduct->update($proDatas,['id'=>$pid]);

            //写入ES
            $row = Model::ins("ProProduct")->getRow(["id"=>$pid]);
            
           

            Model::Es("ProProduct")->delete(["id"=>$pid]);
            
            if($row['checksatus'] != 1){
                $row['enable'] = 0;
            }

            Model::Es("ProProduct")->insert($row);
            
            $gooCountData = Model::ins('ProProduct')->getRow(['businessid'=>$this->businessid],'count(*) as count');

            Model::ins('BusBusinessInfo')->update(['goodscount'=>$gooCountData['count']],['id'=>$this->businessid]);
           
            $this->showSuccess('操作成功');//,'/Product/Index/list'

        // }else{
        //     $this->showError('token错误，禁止操作');
        // }
    }

    /**
     * [ajaxaddsepcAction ajax添加商品规格]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-17T14:51:20+0800
     * @return   [type]                   [description]
     */ 
    public function ajaxAddSepcValueAction(){

        $spec_id=$this->getParam('spec_id');
        $spec_name=$this->getParam('spec_name');
        $value_name=$this->getParam('name');

        $data['spec_id']=$spec_id;
        $data['spec_value_name']=$value_name;
        $data['isdelete']=0;
        $data['store_id']=$this->businessid;

        $value_id=Model::ins("ProSpecValue")->insert($data);

        if($value_id){
            echo json_encode(['done' => true, 'value_id' => $value_id]);
        }else{
            echo json_encode(['done' => false]);
        }
        exit();
    }

    /**
     * [delBrandAction 删除品牌]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-15T14:16:26+0800
     * @return   [type]                   [description]
     */
    public function delgoodsAction(){

        $goodsId = $this->getParam('ids');

        if(empty($goodsId)){
            $this->showError('请选择需要删除的商品');
        }

        $goodsId = explode(',', $goodsId);
        $i = 0;
        $errorName = '';
        //批量删除用户
        foreach ($goodsId as $value) {
            
            $proData = Model::ins('ProProduct')->getRow(['id'=>$value],'id,productname,enable');

            if(empty($proData))
                $this->showError('商品信息不存在');

            if($proData['enable'] == 1){

                $errorName .= '<font color="green">'.$proData['productname'].'</font>&nbsp|&nbsp';
                //$this->showError('<font color="green">'.$proData['productname'].'</font>已上架不能删除');
            }
            
            if(!empty($proData) && $proData['enable'] != 1){

                $goodsData = Model::ins('ProProduct')->delete(['id'=>$value]);


               // var_dump($goodsData);
                $goodsInfoData = Model::ins('ProProductInfo')->delete(['id'=>$value]);
                //var_dump($goodsInfoData);
                $goodsSpecData = Model::ins('ProProductSpec')->delete(['productid'=>$value]);
                //var_dump($goodsSpecData);
                $goodsSpecValueData = Model::ins('ProProductSpecValue')->delete(['productid'=>$value]);
                //var_dump($goodsSpecValueData);
                //
                Model::Es("ProProduct")->delete(['id'=>$value]);
                $i ++;
            }

        }


        if(!empty($errorName)){
            $msg = '其中'.rtrim($errorName,'&nbsp|&nbsp').'为上架商品不能删除';
        }
        $this->showSuccess('成功删除'.$i.'条记录'.$msg,'',10);
    }

}