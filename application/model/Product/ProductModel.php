<?php
// +----------------------------------------------------------------------
// |  [ 商品相关模型 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-03
// +----------------------------------------------------------------------
namespace app\model\Product;

use app\lib\Db;

use app\lib\Img;
use app\lib\Model;
//获取配置
use \think\Config;

use app\model\Profit\Cash_abstract;
use app\model\Business\BusinessModel;
use app\model\Sys\CommonModel;

class ProductModel{

    //14现金换100牛豆
    const  cash = 14; //14现金
    const  bullamount = 100; // 100牛豆

    const  disFormat = 100;
    const  priceFormat = 0.86; 
    const  cashPriceOne = 1.05;
    const  cashPriceTwo = 0.14;
    
    const  maxCashDiscount = 0.8;//现金最大折扣
    const  maxbullDiscount = 0.9;//现金加牛豆最大折扣

    const  maxCashDiscount2 = 0.97;//现金最大折扣2
    //现金分润
    //const cashPercent = 0.80;
    //区分牛豆加现金商品 和纯牛豆商品条件
    const bullamountPercent = 0.14;

    //新人专享分类ID
    const newusercategoryid = 211;
    /**
     * [getBanner 获取快捷方式]
     * @Author   ISir<673638498@qq.com>
     * @Date 2017-03-01 
     * @return [type] [description]
     */
    public static function getSaleway($param){

        $where =  ['type'=>$param['type']];
       
        $redis_key = $param['type'];
        //售卖方式
        $MallSaleway = Model::ins("MallSaleway");

        if($MallSaleway->RedisObj()->exists($redis_key)){
          
            $item = $MallSaleway->RedisObj()->getRow($redis_key);
         
            $saleWay = json_decode($item['saleway']);
            
        }else{
           
            $saleWay = $MallSaleway->getList($where,'name,thumb,urltype,url,sort','sort asc');
            foreach ($saleWay as $key => $value) {
                $saleWay[$key]['bname'] = $value['name'];
                $saleWay[$key]['thumb'] = Img::url($value['thumb']);
            }
           

            $MallSaleway->RedisObj()->insert($redis_key,['saleway'=>json_encode($saleWay)]);

        }
     
        return $saleWay ;
    }

    /**
     * [getBanner 获取banner图片]
     * @Author   ISir<673638498@qq.com>
     * @Date 2017-03-01 
     * @param  [type] $param [1为商城首页2现金专区3现金加牛币专区]
     * @return [type]        [description]
     */
    public static function getTypeBanner($type){

        $MallBanner = Model::ins("MallBanner");
        $redis_key = $type;

        if($MallBanner->RedisObj()->exists($redis_key)){
            $item = $MallBanner->RedisObj()->getRow($redis_key);
         
            $brand = json_decode($item['banner']);
        }else{

            $brand = $MallBanner->getList(['type'=>$type],'bname,thumb,urltype,url,sort','sort desc');
            foreach ($brand as $key => $value) {
                $brand[$key]['thumb'] = Img::url($value['thumb']);
            }
            $MallBanner->RedisObj()->insert($redis_key,['banner'=>json_encode($brand)]);

        }
        return $brand ;
    }
    
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
                "fields"    => "id as productid,productname,thumb,prouctprice",
                "order"     => $param['order']!="" ? $param['order']:" sort desc,id desc",
            ]);
       
        foreach($list['list'] as $k=>$v){
            $list['list'][$k]['prouctprice']  = DePrice($v['prouctprice']);
            $list['list'][$k]['thumb'] = Img::url($v['thumb'],300,300);
            $productinfo_row = Model::ins('ProProductInfo')->getRow(['id'=>$v['productid']],'salecount');
           
            $list['list'][$k]['salecount'] = $productinfo_row['salecount'];
        }

        return ['code'=>200,'data'=>$list];
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
        $ProProduct = Model::ins("ProProduct");
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
        $ProProduct = Model::ins("ProProduct");
        $proData = $ProProduct->getRow(['id'=>$id],$fields);
        


        if(!empty($proData)){
            
           
            $proData['productid'] = $proData['id'];

           
            $proData['prouctprice'] = DePrice($proData['prouctprice']);
            $proData['bullamount'] = DePrice($proData['bullamount']);
            $proData['supplyprice'] = DePrice($proData['supplyprice']);
            
            //商品sku 所有情况
            $sku = self::getProSkuById($id,$newuserproductstorage,$productbuy);  // return ["sku"=>$sku,"spec"=>]
            $proData['productstorage'] = $sku['all_productstorage'];  
            $proData['sku'] = $sku['sku'];   
            //print_r($sku);
            $proData['companyphone'] = CommonModel::getCompanyPhone();
            $proData['spec'] = $sku['spec'];
            

            //商品图片
            $img =  self::getProPhotosById($id);

            $proData['bannerimg'] = $img;

             //图文详情
            $productInfo =  self::getProInfoById($id);

            $arr['url'] =  Config::get('shareparma.product_url').$id;
            $arr['title'] =  $proData['productname'];
            $arr['description'] =  mb_substr(strip_tags($productInfo['description']),0,30,'utf-8');
            $arr['image'] = Img::url($proData['thumb'],300,300);
            
            $proData['sharecontent'] = $arr;


            if(!empty($productInfo))
               $proData = array_merge($proData,$productInfo); 
               //商家信息
            $business =  self::getProBusinessById($proData['businessid']);

            $service = BusinessModel::businessnService($proData['businessid']);

            if($service){
                $proData['isservice'] = 1;
            }else{
                $proData['isservice'] = -1;
            }

            $gooCountData = Model::ins('ProProduct')->getRow(['businessid'=>$proData['businessid'],'enable'=>1,'checksatus'=>1],'count(*) as count');
         
            $business['goodscount'] =  $gooCountData['count'];
            
            if($business['scores'] <= 0){
                $business['scores'] = '5.0';
            }else{
                $business['scores'] = scoresFormat($business['scores']);
            }

            $Transport = self::getProTransport($proData['transportid'],$proData['businessid']);
            $Transport = !empty($Transport) ? $Transport : 0;
            $proData['transport'] = DePrice($Transport);

            $proData['area']= $business['area'];
            if(!empty($business))
               $proData['business'] = $business; 
           
            

            $proData['detailInfo'] = Config::get('webview.webviewUrl').'/Product/Index/detail?goodsid='.$id;
            $proData['detailParams'] = Config::get('webview.webviewUrl').'/Product/Index/detailParams?goodsid='.$id;

            return ['code'=>200,'data'=>$proData];
        }else{
            return ['code'=>'400'];
        }
    }

    /**
     * [prospecsdata 获取商品信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-21T22:45:59+0800
     * @param    [type]                   $goodsid [description]
     * @return   [type]                            [description]
     */
    public static function prospecsdata($goodsid){
         //商品sku 所有情况
            $sku = self::getProSkuById($goodsid);
            $proData['sku'] = $sku;   
            //print_r($sku);
            foreach ($sku as $key => $value) {
                 $sku_arr[] = explode(',',$value['f_productspec']);
            }
           
            foreach ($sku_arr as $key => $value) {
                foreach ($value as $ka => $va) {
                        $string[$ka][] =  $va ;
                }
            }
           

            //商品规格 取出已有sku
            $spec = self::getProSpecById($goodsid);
            //print_r($spec);
            foreach ($spec as $sk => $sv) {

                foreach ($sv['value'] as $skv => $svv) {
                  
                    if(!in_array($svv['id'], $string[$sk])){
                        unset($spec[$sk]['value'][$skv]);
                    }
                }
            }

            foreach ($spec as $key => $value) {
                if(empty($value['value'])){
                    unset($spec[$key]);
                }
                //sort($spec[$key]['value']);
            }
            
            foreach ($spec as $key => $value) {
                sort($spec[$key]['value']);
            }
           
            return $spec;
    }

  
       
     

    /**
     * [getProSpecById 根据skuid获取单条商品sku信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-03T20:19:46+0800
     * @return   [type]                   [description]
     */
    public static function getRowProSpecById($id,$fields='*'){
        //商品sku信息
        $ProProductSpec = Model::ins("ProProductSpec");
        return $proSpecData = $ProProductSpec->getRow(['id'=>$id],$fields);
    }

    /**
     * [getProPhoto 获取商品图片]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-01 
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function getProPhotosById($id){
        $empty_array = [['istop'=>"","productpic"=>"","sort"=>""],['istop'=>"","productpic"=>"","sort"=>""]];
         
        //商品图片
        $ProProductphoto = Model::ins("ProProductPhoto");
        $photoData = $ProProductphoto->getList(['productid'=>$id],"istop,productpic,sort",'sort asc',0,5);
        foreach ($photoData as $key => $value) {
           $photoData[$key]['productpic'] = Img::url($value['productpic'],600,300);
        }
        if(!empty($photoData)){
            return $photoData;
        }else{
             return $empty_array;
        }
    }

    /**
     * [getProSpec 获取商品所有规格]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-01 
     * @return [type] [description]
     */
    public static function getProSpecById($id){
           
            $spec = [];
           
            //商品基础信息
            $ProProduct = Model::ins('ProProduct');
            //商品规格
            $ProProductSpecValue = Model::ins("ProProductSpecValue");
            
            //商品规格对应值
            $ProSpecValue = Model::ins('ProSpecValue');

            $spec_info = $ProProductSpecValue->getRow(['productid'=>$id]);

            //$ProModuleRelation = Model::ins("ProModuleRelation");

            $specs = [];
           
           
            //print_r($spec_info);
            $specname = json_decode($spec_info['spec_name'],true);
            //print_r($specname);
            $specvalue = json_decode($spec_info['spec_vlaue'],true);
            //print_r($specvalue);
            foreach ($specname as $key => $value) {
                $name_arr = [
                    'id'=>$key,
                    'f_images'=>'',
                    'spec_name' => $value,
                ];
                $specs[] = $name_arr;
            }

            //print_r($specs);
            foreach ($specvalue as $k => $v) {
                foreach ($v as $key => $value) {
                    $tmp_arr[$k][] = [
                        'id'=>$key,
                        'parent_id'=>$k,
                        'spec_value'=>$value
                    ];
                }
            }
            //print_r($tmp_arr);
            foreach ($specs as $key => $value) {
               
                    
                $specs[$key]['value'] = $tmp_arr[$value['id']];
                
            }
            //print_r($specs);

            if(!empty($spec_info)){
              
                return $specs;

            }else{
                return [
                        ['id'=>"","f_images"=>"",
                            "value"=>[
                                ["id"=>"","parent_id"=>"","spec_value"=>""],
                                ["id"=>"","parent_id"=>"","spec_value"=>""]
                            ]
                        ],
                        ['id'=>"","f_images"=>"",
                            "value"=>[
                                ["id"=>"","parent_id"=>"","spec_value"=>""],
                                ["id"=>"","parent_id"=>"","spec_value"=>""]
                            ]
                        ]
                    ];
            }
    }


   

   

    /**
     * [getProIntroById 获取商品图文详情]
     * @return [type] [description]
     */
    public static function getProInfoById($id){
        $ProProductInfo = Model::ins("ProProductInfo");
        $InfoData = $ProProductInfo->getRow(['id'=>$id],'description,evaluatecount,salecount,salecountrand,scores');
        if($InfoData['salecountrand'] <= 0){
            self::updateGoodsSalecount($id);
            $InfoData = $ProProductInfo->getRow(['id'=>$id],'description,evaluatecount,salecount,salecountrand,scores');
            $InfoData['salecount'] = $InfoData['salecount'] + $InfoData['salecountrand'];
        }else{
            $InfoData['salecount'] = $InfoData['salecount'] + $InfoData['salecountrand'];
        }
        unset( $InfoData['salecountrand']);
        return $InfoData ;
    }

    /**
     * [getCommentListById 商品详情返回评论信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-01 
     * @param  [type] $param [description]
     * @return [type]        [description]
     */
    public static function getCommentListById($id){
        $ProEvaluate = Model::ins('ProEvaluate');
        $ProEvaluateImage = Model::ins('ProEvaluateImage');
        
        $commentData = $ProEvaluate->getRow(['productid'=>$id,"state"=>0],"id as evaluate_id,isanonymous,productid,productname,scores,content,frommemberid,frommembername,headpic,addtime","addtime desc");
        
        if($commentData['isanonymous'] == 1){
            $commentData['frommembername'] = substr_cut($commentData['frommembername']);
        }
        
        return $commentData;
    }

    /**
     * [getAllProComment 获取商品所有评论]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-01 
     * @return [type] [description]
     */
    public static function getAllProComment($id){

        $ProEvaluate = Model::ins('ProEvaluate');
        $ProEvaluateImage = Model::ins('ProEvaluateImage');
        
        $data =  $ProEvaluate->pageList(["productid"=>$id,'parentid'=>0,"state"=>0, "enable" => 1],"id as evaluate_id,isanonymous,productid,productname,scores,content,frommemberid,frommembername,headpic,addtime","addtime desc");
       
        foreach ($data['list']  as $key => $value) {
            $reply = '';
            $repData = $ProEvaluate->getRow(['parentid'=>$value['evaluate_id']],'content');
            if(!empty($repData))
                $reply = $repData['content'];

            if($value['isanonymous'] == 1){
                $data['list'][$key]['frommembername'] = substr_cut($value['frommembername']);
            }

            $data['list'][$key]['headpic'] = Img::url($value['headpic']);
            $imgArr = $ProEvaluateImage->getList(['evaluate_id'=>$value['evaluate_id']],'thumb','addtime desc',5);
            $imgArr_tmp = [];
            foreach ($imgArr as $k => $v) {
                if(!empty($v['thumb'])){
                    $v['thumb'] = str_replace('http://nnhcoupon.oss-cn-shenzhen.aliyuncs.com/', '', $v['thumb']);
                    $imgArr_tmp[] =  Img::url($v['thumb'],500,500);
                }
            }
           
            $data['list'][$key]['img_arr'] = $imgArr_tmp;
            $data['list'][$key]['reply'] = $reply;
        }
        return $data;
    }
    

    /**
     * [searchProduct 搜索商品信息]
     * @param  [type] $param [description]
     * @return [type]        [description]
     */
    public static function searchProduct($param){

        $ProProduct = Model::ins("ProProduct");
        $where = !empty($param['where']) ? $param['where'] : "";
        
        $fields = !empty($param['fields']) ? $param['fields'] : "*";
        $list =  $ProProduct->pageList($where,$fields);
     
        foreach($list['list'] as $k=>$v){
            $list['list'][$k]['thumb'] = Img::url($v['thumb']);
            $list['list'][$k]['prouctprice'] = DePrice($v['prouctprice']);
            $list['list'][$k]['bullamount'] = DePrice($v['bullamount']);
            if($v['bullamount'] > 0){
                $list['list'][$k]['marketprice'] = DePrice($v['prouctprice'] + $v['bullamount']);
            }else{
                $list['list'][$k]['marketprice'] =  0;
            }
        }
        return $list;
    } 

    /**
     * [checkPrice 价格公式]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-27T16:06:00+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public static function formatPrice($param){
        $supplyprice = $param['supplyprice'];
        $saleprice = $param['saleprice'];
        $settle_cycle = $param['settle_cycle'];

        if($saleprice <= 0 || $supplyprice <= 0)
            return ['discount'=>0,'prouctprice'=>0,'bullamount'=>0,'msg'=>'价格错误'];
       

        $msg = '';

        if($settle_cycle == 1){ //现金商品

            $discount = $supplyprice/$saleprice*self::disFormat; //折扣
            $prouctprice = $saleprice; //现金
            $bullamount = 0;    //牛豆

            if($discount >= self::disFormat*self::maxCashDiscount){
                $msg = '折扣比例过低，请重新调价';
                $prouctprice  = 0;
                $bullamount =0;
            }
            $discount = number_format($discount,0,'.',''); //折扣
            return ['discount'=>$discount,'prouctprice'=>$prouctprice,'bullamount'=>$bullamount,'msg'=>$msg];
        }else if($settle_cycle == 2){//现金+牛豆 ·商品
            $discount = $supplyprice/$saleprice*self::disFormat;

            if($discount <= self::disFormat*self::bullamountPercent){//纯牛豆商品
                
                $prouctprice = 0;  //现金
               
                $bullamount = number_format($supplyprice*self::bullamount/self::cash,2,'.','');  //牛豆 100牛豆等于14元

                $discount = number_format($discount,0,'.',''); //折扣

                return ['discount'=>$discount,'prouctprice'=>$prouctprice,'bullamount'=>$bullamount,'msg'=>$msg];

            }else if($discount > self::disFormat*self::bullamountPercent){

                if($discount >= self::disFormat*self::maxbullDiscount){
                    $msg = '折扣比例过低，请重新调价';
                    $prouctprice  = 0;
                    $bullamount =0;
                }else{
                    //现金
                    $prouctprice = (self::cashPriceOne*$supplyprice - self::cashPriceTwo*$saleprice)/self::priceFormat;
                    $prouctprice = number_format($prouctprice, 2, '.', '') + 0.01;
                    
                    $bullamount = $saleprice-$prouctprice;
                    //牛豆
                    // $bullamount = ($saleprice - self::cashPriceOne*$supplyprice)/self::priceFormat;
                    // $bullamount = number_format($bullamount, 2, '.', '') + 0.01;

                   //sprintf("%.2f",substr(sprintf("%.3f", $num), 0, -2)); 
                    $discount = number_format($discount,0,'.',''); //折扣
                }
                return ['discount'=>$discount,'prouctprice'=>$prouctprice,'bullamount'=>$bullamount,'msg'=>$msg];
            }
            
        }else{
            return ['msg'=>'error'];
        }
    }

    /**
     * [newFormatPrice 牛品价格调整]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-11T18:26:02+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function newFormatPrice($param){
        $supplyprice = $param['supplyprice'];
        $saleprice = $param['saleprice'];
        $settle_cycle = $param['settle_cycle'];
        if($saleprice <= 0 || $supplyprice <= 0)
            return ['discount'=>0,'prouctprice'=>0,'bullamount'=>0,'msg'=>'价格错误'];
       // var_dump($param);

        $msg = '';

        if($settle_cycle == 1){ //现金商品

            $discount = $supplyprice/$saleprice*self::disFormat; //折扣
            $prouctprice = $saleprice; //现金
            $bullamount = 0;    //牛豆

            if($discount > self::disFormat*self::maxCashDiscount2){
                $msg = '折扣比例过低，请重新调价';
                $prouctprice  = 0;
                $bullamount =0;
            }

            $discount = number_format($discount,0,'.',''); //折扣
            return ['discount'=>$discount,'prouctprice'=>$prouctprice,'bullamount'=>$bullamount,'msg'=>$msg];
        }else if($settle_cycle == 2){//现金+牛豆 ·商品
            $discount = $supplyprice/$saleprice*self::disFormat; //折扣
            $discount = number_format($discount,0,'.',''); //折扣
            $prouctprice = ($param['supplyprice'] + $param['supplyprice'] *0.2) ; //现金
            $bullamount = $saleprice - $prouctprice;    //牛豆
            if($bullamount < 0){
                $prouctprice = 0;
                $bullamount = 0;
                $msg = '销售价格太低';
            }
            return ['discount'=>$discount,'prouctprice'=>$prouctprice,'bullamount'=>$bullamount,'msg'=>$msg];
        }else if($settle_cycle==3){
            $discount = $supplyprice/$saleprice*self::disFormat; //折扣
            $discount = number_format($discount,0,'.',''); //折扣
            $prouctprice =0; //现金
            $bullamount = $saleprice;    //牛豆
            if($bullamount < 0){
                $prouctprice = 0;
                $bullamount = 0;
                $msg = '销售价格太低';
            }
            return ['discount'=>$discount,'prouctprice'=>$prouctprice,'bullamount'=>$bullamount,'msg'=>$msg];
        }else{
            return ['msg'=>'error'];
        }
    }

    /**
     * [getCategoryData 获取分类数据]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-30T22:03:46+0800
     * @return   [type]                   [description]
     */
    public static function getCategoryData(){
        
        $data = Model::ins("ProCategory")->getList(['status'=>['>=',0],'category_icon'=>['<>',''],'parent_id'=>0]);
        foreach ($data as $key => $value) {
            $data[$key]['category_icon'] = Img::url($value['category_icon']);
        }
        return ['code'=>200,'data'=>$data];
    }

    /**
     * [formartCategory 格式化商品分类 二级]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-10T10:24:09+0800
     * @return   [type]                   [description]
     */
    public static function formartCategory(){
        $category = Model::ins('ProCategory')->getList(['status'=>['>=',0],'parent_id'=>0],'id as cateId,name as cateName');
        $cate_arr = [];
        foreach ($category as $key => $value) {
            $son_category = Model::ins('ProCategory')->getList(['status'=>['>=',0],'parent_id'=>$value['cateId']],'id as cid,name as cname,category_icon');
            $category[$key]['childs'] = $son_category;
        }
        foreach ($category as  $cate) {
            $cate_arr[$cate['cateId']] = $cate;
        } 
        return ['code'=>200,'data'=>$cate_arr];
    }

    /**
     * [returSelectTopCate 返回选择父级分类数据]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-14T17:09:21+0800
     * @return   [type]                   [description]
     */
    public static function returnSelectTopCate($id=''){

        $where['parent_id'] = 0;
        $where['status'] = ['<>',-1];
        if(!empty($id))
            $where['id'] = ['<>',$id];
       
        $cateData =    Model::ins("ProCategory")->getList($where,'id,name');
        
        $topCate = [];
        foreach ($cateData as $key => $value) {
            $topCate[$value['id']] = $value['name'];
        }
    
        return $topCate;
    }

    /**
     * [formart_category 分类管理]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-14T19:30:23+0800
     * @param    [type]                   $category [description]
     * @return   [type]                             [description]
     */
    public function formart_category(){
    
        $cateData =    Model::ins("ProCategory")->getList(['status'=>['<>',-1]],'*');

        return $treeCate = self::tree($cateData,'name');

    }


    /**
     * 获得树状数据
     *
     * @param $data 数据
     * @param $title 字段名
     * @param string $fieldPri 主键id
     * @param string $fieldPid 父id
     *
     * @return array
     */
    public static function tree( $data, $title, $fieldPri = 'id', $fieldPid = 'parent_id' ) {
        if ( ! is_array( $data ) || empty( $data ) ) {
            return [ ];
        }
        $arr = self::channelList( $data, 0, '', $fieldPri, $fieldPid );
       
        foreach ( $arr as $k => $v ) {
            $str = "";
            if ( $v['_level'] > 2 ) {
                for ( $i = 1;$i < $v['_level'] - 1;$i ++ ) {
                    $str .= "│&nbsp;&nbsp;&nbsp;&nbsp;";
                }
            }
            if ( $v['_level'] != 1 ) {
                $t = $title ? $v[ $title ] : '';
                if ( isset( $arr[ $k + 1 ] ) && $arr[ $k + 1 ]['_level'] >= $arr[ $k ]['_level'] ) {
                    $arr[ $k ][ '_' . $title ] = $str . "├─ " . $v['_html'] . $t;
                } else {
                    $arr[ $k ][ '_' . $title ] = $str . "└─ " . $v['_html'] . $t;
                }
            } else {
                $arr[ $k ][ '_' . $title ] = $v[ $title ];
            }
        }
        //设置主键为$fieldPri
        $data = [ ];
        foreach ( $arr as $d ) {
            //            $data[$d[$fieldPri]] = $d;
            $data[] = $d;
        }

        return $data;
    }

    /**
     * 获得所有子栏目
     *
     * @param $data 栏目数据
     * @param int $pid 操作的栏目
     * @param string $html 栏目名前字符
     * @param string $fieldPri 表主键
     * @param string $fieldPid 父id
     * @param int $level 等级
     *
     * @return array
     */
    public static function channelList( $data, $pid = 0, $html = "&nbsp;", $fieldPri = 'id', $fieldPid = 'parent_id', $level = 1 ) {
        $data = self::_channelList( $data, $pid, $html, $fieldPri, $fieldPid, $level );
        if ( empty( $data ) ) {
            return $data;
        }
        foreach ( $data as $n => $m ) {
            if ( $m['_level'] == 1 ) {
                continue;
            }
            $data[ $n ]['_first'] = FALSE;
            $data[ $n ]['_end']   = FALSE;
            if ( ! isset( $data[ $n - 1 ] ) || $data[ $n - 1 ]['_level'] != $m['_level'] ) {
                $data[ $n ]['_first'] = TRUE;
            }
            if ( isset( $data[ $n + 1 ] ) && $data[ $n ]['_level'] > $data[ $n + 1 ]['_level'] ) {
                $data[ $n ]['_end'] = TRUE;
            }
        }
        //更新key为栏目主键
        $category = [ ];
        foreach ( $data as $d ) {
            $category[ $d[ $fieldPri ] ] = $d;
        }

        return $category;
    }

    //只供channelList方法使用
    private static function  _channelList( $data, $pid = 0, $html = "&nbsp;", $fieldPri = 'cid', $fieldPid = 'pid', $level = 1 ) {
        if ( empty( $data ) ) {
            return [ ];
        }
        $arr = [ ];
        foreach ( $data as $v ) {
            $id = $v[ $fieldPri ];
            if ( $v[ $fieldPid ] == $pid ) {
                $v['_level'] = $level;
                $v['_html']  = str_repeat( $html, $level - 1 );
                array_push( $arr, $v );
                $tmp = self::channelList( $data, $id, $html, $fieldPri, $fieldPid, $level + 1 );
                $arr = array_merge( $arr, $tmp );
            }
        }

        return $arr;
    }

    //获取所属分类的子分类
    public function getChildCategoryStr($categoryid){

        $list = Model::ins("ProCategory")->getList(["parent_id"=>$categoryid],"id");

        $idstr = '';
        foreach($list as $k=>$v){
          $idstr.=$v['id'].",";
        }

        $idstr = $idstr!=''?substr($idstr,0,-1):'';

        return $idstr;
    }

    /**
     * [updateGoodsSalecount 更新销量]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-01T11:03:41+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public static function updateGoodsSalecount($goodsid){
        
        $productInfo = Model::ins('ProProductInfo')->getRow(['id'=>$goodsid],'id,salecount,salecountrand');
        
        if(empty($productInfo))
            return false;
        if($productInfo['salecountrand'] <= 0){
            $updateData['salecountrand'] = rand(50,100); 
        }
        $updateData['salecount'] = $productInfo['salecount'] + 1;
        
        $ret = Model::ins('ProProductInfo')->update($updateData,['id'=>$goodsid]);
        return $ret;
    }

    /**
     * 新人专享
     * @Author   zhuangqm
     * @DateTime 2017-09-04T17:57:58+0800
     * @return   [type]                   [description]
     */
    public static function getBullProduct(){
        //获取新人专享分类，及子分类
        $categorylist = Model::ins("ProCategory")->getList(["parent_id"=>self::newusercategoryid,"status"=>1],"id");

        $idarr[] = self::newusercategoryid;

        foreach($categorylist as $k=>$v){
            $idarr[] = $v['id'];
        }

        return $idarr;
    }

    /**
     * 新人专享模块，只能购买一次
     * @Author   zhuangqm
     * @DateTime 2017-09-04T18:14:11+0800
     * @param    [type]                   $param [
     *                                           productid
     *                                           categoryid
     *                                           customerid
     *                                           cartitemids
     *                                     ]
     * @return   [type]                   [description]
     */
    public static function getNewUserProductStorage($param){

        $category_arr = self::getBullProduct();

        if(in_array($param['categoryid'], $category_arr)){
            if($param['customerid']>0){
                $category_str = implode(",",$category_arr);
                $category_str = $category_str!=""?$category_str:"0";
                // 判断用户是否已购买过该商品
                /*$orderitem = Model::ins("OrdOrderItem")->getRow(["productid"=>$param['productid'],"orderno"=>["in","select orderno from ord_order where customerid='".$param['customerid']."' and orderstatus<5"]],"count(*) as count");*/
                // $orderitem = Model::ins("OrdOrderItem")->getRow("productid='".$param['productid']."' and orderno in(select orderno from ord_order where customerid='".$param['customerid']."' and orderstatus<5)","count(*) as count");
                $orderitem = Model::ins("OrdOrderItem")->getRow("categoryid in(".$category_str.") and orderno in(select orderno from ord_order where customerid='".$param['customerid']."' and orderstatus<5)","count(*) as count");

                if($orderitem['count']==0){
                    $where = "productid in(select id from pro_product where categoryid in(".$category_str.")) and customerid='".$param['customerid']."'";
                    if(!empty($param['cartitemids']))
                        $where.=" and id not in(".$param['cartitemids'].")";
                    $row = Model::ins("OrdShoppingcartItem")->getRow($where,"count(*) as count");
                    if($row['count']==0){
                        return [
                            "product_storage_flag"=>true,
                            "productstorage"=>1,
                        ];
                    }else{
                        return [
                            "product_storage_flag"=>true,
                            "productstorage"=>0,
                        ];
                    }
                    
                }else{
                    return [
                        "product_storage_flag"=>true,
                        "productstorage"=>0,
                    ];
                }
            }else{
                return [
                    "product_storage_flag"=>true,
                    "productstorage"=>1,
                ];
            }
        }else{ 
            return [
                "product_storage_flag"=>false,
                "productstorage"=>0,
            ];
        }
    }

    /**
     * [addProductAlterlog 添加商品操作日志]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-05T18:00:26+0800
     * @param    [type]                   $param [description]
     */
    public static function addProductAlterlog($params){
       
        $insert = [
            "customerid" =>  $params["customerid"],
            "productid"  =>  $params["productid"],
            "username"   =>  $params["username"],
            "content"    =>  $params["content"],
            "addtime"    =>  date("Y-m-d H:i:s"),

        ];
        return $ret = Model::ins('ProProductAlterlog')->insert($insert);
    }

      /**
     * [getProSkuById 获取商品所有sku]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-01 
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function getProSkuById($id,$newuserproductstorage=[],$productbuy=[]){
         

             //组合商品sku 获取商品所以组合sku情况
            $ProProductspec = Model::ins('ProProductSpec');
            $ProductSpecInfo = $ProProductspec->getList ('productid='.$id,'*','id desc');
            
             //--------------------------------
            //重组商品sku
            $sku_list = [];

            $empty_sku = [["id"=>"","aotusku"=>"","productid"=>"","supplyprice"=>"","bullamount"=>"","productimage"=>"","f_productspec"=>""],["id"=>"","aotusku"=>"","productid"=>"","supplyprice"=>"","bullamount"=>"","productimage"=>"","f_productspec"=>""]];
          
            $empty_spec = [
                ["id"=>"",'f_images'=>"","value"=>[
                        ["id"=>"","spec_value"=>"","parent_id"=>""],
                        ["id"=>"","spec_value"=>"","parent_id"=>""]
                    ]
                ],
                ["id"=>"",'f_images'=>"","value"=>[
                        ["id"=>"","spec_value"=>"","parent_id"=>""],
                        ["id"=>"","spec_value"=>"","parent_id"=>""]
                    ]
                ]
            ]
            ;
            if(!empty($ProductSpecInfo) && is_array($ProductSpecInfo)){


                $all_productstorage = 0;
                foreach ($ProductSpecInfo as $k => $v) {
                    
                    $sku_list[$k]['id'] = $v['id']; 
                    $sku_list[$k]['aotusku'] = $v['aotusku']; 
                    $sku_list[$k]['productid'] = $v['productid']; 
                    $sku_list[$k]['prouctprice'] = DePrice($v['prouctprice']); 
                    $sku_list[$k]['supplyprice'] = DePrice($v['supplyprice']);
                    $sku_list[$k]['bullamount'] = DePrice($v['bullamount']);
                    $sku_list[$k]['saleprice'] = DePrice($v['saleprice']);

                    if($v['bullamount'] > 0){
                        $sku_list[$k]['marketprice'] = DePrice($v['prouctprice'] + $v['bullamount']);
                    }else{
                        $sku_list[$k]['marketprice'] =  0;
                    }

                    $sku_list[$k]['productimage'] = Img::url($v['productimage']);
                    $sku_list[$k]['productstorage'] = $v['productstorage'];
                    $all_productstorage += $v['productstorage'];
                    $productspec = json_decode($v['productspec'],true); 

                  
                    $gtproductspec = '';

                    foreach ($productspec as $fk => $fv) {
                       $gtproductspec .= $fk . ",";
                    }
                    $gtproductspec = rtrim($gtproductspec,',');
                    $sku_list[$k]['f_productspec'] = $gtproductspec;

                    // 新人专享
                    if($v['productstorage']>0 && $newuserproductstorage['product_storage_flag'])
                        $sku_list[$k]['productstorage'] = $newuserproductstorage['productstorage'];

                    // 抢购活动
                    if(!empty($productbuy) && $productbuy['product_storage_flag'] && $productbuy['product_buy_status']==1){
                        $sku_list[$k]['productstorage'] = ($sku_list[$k]['productstorage']>$productbuy['product_buy']['productstorage'])?$sku_list[$k]['productstorage']-$productbuy['product_buy']['productstorage']:0;
                        /*$sku_list[$k]['prouctprice']    = DePrice($productbuy['product_buy']['prouctprice']);
                        $sku_list[$k]['supplyprice']    = DePrice($productbuy['product_buy']['supplyprice']);
                        $sku_list[$k]['saleprice']      = DePrice($productbuy['product_buy']['saleprice']);*/
                    }
                }

                //--------------------------------
                //$skulist = Model::ins("ProProductspec")->getList(["productid"=>$id],"productspec,spec");

                $spec_arr = [];
                foreach($ProductSpecInfo as $k=>$v){
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
                            "spec_value"=>$spec_value_value['value'],                       
                        ];
                    }

                } 

                $spec = [];
                $count = 1;
                foreach($spec_arr as $k=>$v){
                    $value = [];
                    foreach($v as $vv){
                        $vv['parent_id'] = $count;
                        $value[] = $vv;
                    }

                    $spec[] = [
                        "id"=>$count,
                        "f_images"=>"",
                        "spec_name"=>$k,
                        "value"=>$value,
                    ];

                    $count++;
                }

            }else{
                 $sku_list = $empty_sku ;
                 $spec = $empty_spec;
            }
           
            //--------------------------------
            
            return ['sku'=>$sku_list,'spec'=>$spec,'all_productstorage'=>$all_productstorage];
    }

    /**
     * [getOneRecommendProduct 获取推荐商品]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-21T11:29:44+0800
     * @return   [type]                   [description]
     */
    public static function getOneRecommendProduct(){
        $where['enable'] = 1;
        $where['checksatus'] = 1;
        $where['thumb'] = ['<>',''];
        $product_row = Model::ins('ProProduct')->getList($where,'id,productname,thumb,prouctprice,productstorage','id desc',10);

        foreach ($product_row as $key => $value) {
            $productinfo_row = Model::ins('ProProductInfo')->getRow(['id'=>$value['id']],'salecount');
            $ProductSpecInfo = Model::ins("ProProductSpec")->getList('productid='.$value['id'],'productstorage','id desc');
            $count_productstorage = 0;
            foreach ($ProductSpecInfo as $v) {
                $count_productstorage += $v['productstorage'];
            }
            $product_row[$key]['productstorage'] = $count_productstorage;
            $product_row[$key]['salecount'] = $productinfo_row['salecount'];
            $product_row[$key]['thumb'] = Img::url($value['thumb']);
            $product_row[$key]['prouctprice'] = DePrice($value['prouctprice']);
        }
       
     
        return ['code'=>200,'data'=>$product_row];
    }

    /**
     * [getProBussinessById 获取商铺基本信息]
     * @return [type] [description]
     */
    public static function getProBusinessById($id){
        
        $empty_array = ["businessid"=>"","businessname"=>"","businesslogo"=>"","mobile"=>"","area"=>"","goodscount"=>"","collectcount"=>"","scores"=>""];
        
        $BusBusinessInfo = Model::ins("BusBusinessInfo");
        $busineInfo = $BusBusinessInfo->getRow(['id'=>$id],'id as businessid,businessname,businesslogo,mobile,area,area_code,goodscount,collectcount,scores');
        $busineInfo['businesslogo'] = Img::url($busineInfo['businesslogo']);

        $areaData = Model::ins('SysArea')->getRow(['id'=>$busineInfo['area_code']],'id,areaname,parentid');
        $parentarea = Model::ins('SysArea')->getRow(['id'=>$areaData['parentid']],'id,areaname,parentid');
        $grandarea = Model::ins('SysArea')->getRow(['id'=>$parentarea['parentid']],'id,areaname,parentid');
  
        $busineInfo['area'] = $grandarea['areaname'] .'/'. $parentarea['areaname'];
        if(!empty($busineInfo)) {
            return $busineInfo;
        }else{
            return $empty_array;
        }
    }



    /**
     * [getProTransport 获取商家运费]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-08T10:39:23+0800
     * @return   [type]                   [description]
     */
    public function getProTransport($transportid,$businessid){
       
        // //$OrdTransport = Model::ins("OrdTransport");
        // $OrdTransportExtend = Model::ins("OrdTransportExtend");
        // //$transportData = $OrdTransport->getRow(['business_id'=>$businessid],"id,valuation_type"); 
        // //print_r($transportData);
        // $extendData = $OrdTransportExtend->getRow(['business_id'=>$businessid,"is_default"=>1],"snum,sprice,xnum,xprice");
        // if(empty($extendData))
        //     $OrdTransportExtend->getRow(['business_id'=>$businessid],"snum,sprice,xnum,xprice");
        if(!empty($transportid)){
            $OrdTransport = Model::ins('OrdTransport')->getRow(['id'=>$transportid]);
            $extendData = Model::ins("OrdTransportExtend")->getRow(['transport_id'=>$transportid,'business_id'=>$businessid,"is_default"=>1],"snum,sprice,xnum,xprice");

        }else if($transportid == 0){
            $OrdTransport = Model::ins('OrdTransport')->getRow(['business_id'=>$businessid,'transport_type'=>1]);
            $extendData = Model::ins("OrdTransportExtend")->getRow(['transport_id'=>$OrdTransport['id'],'business_id'=>$businessid,"is_default"=>1],"snum,sprice,xnum,xprice");
            if(empty($extendData)){
                $extendData = Model::ins("OrdTransportExtend")->getRow(['business_id'=>$businessid,"is_default"=>1],"snum,sprice,xnum,xprice");
            }
        }else{
            $OrdTransport = Model::ins('OrdTransport')->getRow(['business_id'=>$businessid,'transport_type'=>1]);
            $extendData = Model::ins("OrdTransportExtend")->getRow(['transport_id'=>$OrdTransport['id'],'business_id'=>$businessid,"is_default"=>1],"snum,sprice,xnum,xprice");
            if(empty($extendData)){
                $extendData = Model::ins("OrdTransportExtend")->getRow(['business_id'=>$businessid,"is_default"=>1],"snum,sprice,xnum,xprice");
            }
        }
        return $extendData['sprice']; 
    }
    

}