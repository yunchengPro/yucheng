<?php
namespace app\superadmin\controller\Updatemogo;
use app\superadmin\ActionController;

use app\lib\Db;
use app\model\StoBusiness\StobusinessModel;
use app\lib\Model;
use \think\Config;
use app\model\Sys\CommonModel;
use app\model\Sys\BountyUserModel;
use app\model\Product\ProductModel;

class IndexController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

	 
	 /**
     * [updateMgDataAction 初始化mongoDB的数据]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-24T11:57:52+0800
     * @return   [type]                   [description]
     */
    public function updateMgDataAction(){
       
        
        $StoBusinessInfoMG = Model::Mongo('StoBusinessInfo');
        $StoBusinessInfo = Model::ins('StoBusinessInfo');
        $StoBusinessImage = Model::ins('StoBusinessImage');

        $BusinessInfoData = $StoBusinessInfo->getList(['lngx'=>['>',0],'laty'=>['>',0]],'*','id desc');
        $StoBusinessInfoMG->delete([],0);
    
        foreach ($BusinessInfoData as $key => $value) {
            
            // var_dump(StobusinessModel::updateStoBusinessSalecount($value['id']));
            // exit();
            $inserMongo = $value;
            $inserMongo['businessid'] = $value['id'];


            $reutnproportion = StobusinessModel::getBusReturnBull(['businessid'=>$value['id']]);
           
            $salecount = Model::ins('StoPayFlow')->getRow(['businessid'=>$value['id'],'status'=>1],'count(*) as count');
          
            $StoBusinessInfo->update(['salecount'=>$salecount['count']],['id'=>$value['id']]);

            $logoData = $StoBusinessImage->getRow(['business_id'=>$value['id']],'thumb');
            

            if(empty($inserMongo['businesslogo']))
                $inserMongo['businesslogo'] = $logoData['thumb'];

            $inserMongo['loc'] =  [
                 'type' => 'Point', 
                 'coordinates' => [
                     doubleval($inserMongo['lngx']),//经度
                     doubleval($inserMongo['laty'])//纬度
                 ]
            ];
            //print_r($inserMongo);
            
            $int_arr = [
                'reutnproportion' =>  intval($reutnproportion),
                'salecount' =>       intval($salecount) ,//$value['salecount'],
                'scores' =>          intval($value['scores']),
                'busendtime' => intval($value['busendtime']),
                'busstartime' => intval($value['busstartime']),
                'area_code' => intval($value['area_code'])
            ];

           $StoBusinessInfoMG->insert($inserMongo,$int_arr);
            
        }
        echo "ok";
        //return $this->json(200);
        exit();
    }



    public function getAddressLngLatAction(){

        $address = $this->getParam('address');

        $gaomap = Config::get('map');
        
        echo $url = $gaomap['domain']."?address=".$address."&output=JSON&key=".$gaomap['key'];
        
        $map_json = CommonModel::get_curl($url);
        var_dump($map_json);
        $map_arr = json_decode($map_json, true);
        
        print_r($map_arr);

        $areaname = CommonModel::getSysArea($params['area_code']);
                $map = CommonModel::getAddressLngLat($areaname['data'].$params['address']);
                $dataParams['longitude'] = $map['data']['lngx'];
                $dataParams['latitude'] = $map['data']['laty'];
                
    }

    /**
     * [updateSysAreatmpAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-29T14:44:48+0800
     * @return   [type]                   [description]
     */
    public function updateSysAreatmpAction(){
        $list = Model::ins('SysAreaTmp')->getList(['id'=>['>=',451101]],'id,parentid,areaname','id desc');
       // print_r($list);
        foreach ($list as $key => $value) {
           
            $gaomap = Config::get('map');
        
            echo $url = $gaomap['domain']."?address=".$value['areaname']."&output=JSON&key=".$gaomap['key'];
            
            $map_json = CommonModel::get_curl($url);
            var_dump($map_json);
            $map_arr = json_decode($map_json, true);
            
            print_r($map_arr);
          
        }
    }

    /**
     * [getBusinessCodeAction 获取商家平台号]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-04T11:35:23+0800
     * @return   [type]                   [description]
     */
    public function getBusinessCodeAction(){//32117
        // $data = StobusinessModel::creatStoBusCode(['businessid'=>489]);
        // var_dump($data);
        $data = BountyUserModel::userGetBounty(['orderno'=>'NNHSTO20170725201809822268']);
        var_dump($data);
    }

    /**
     * [getAreaJsonAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-07T11:24:16+0800
     * @return   [type]                   [description]
     */
    public function getAreaJsonAction(){
        $data = Model::ins('SysArea')->getList(['parentid'=>0],'id,areaname as name');
        foreach ($data as $key => $value) {
            $child = Model::ins('SysArea')->getList(['parentid'=>$value['id'],'level'=>2],'id,areaname as name');
            foreach ($child as $ck => $cv) {
                $childson = Model::ins('SysArea')->getList(['parentid'=>$cv['id'],'level'=>3],'id,areaname as name');
                $child[$ck]['child']  = $childson;
            }
            $data[$key]['child'] = $child;
        }
        $address['data'] = $data;
        //$address = $data;
        //print_r($address);

        echo json_encode($address,JSON_UNESCAPED_UNICODE);
    }

    /**
     * [updatebusinesscodeAction 同步平台号]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-14T18:24:11+0800
     * @return   [type]                   [description]
     */
    public function updatebusinesscodeAction(){
        $pagesize = 100;
        $page     = 1; 

        // while(true){
           

            $list = Model::ins('StoBusinessBaseinfo')->getList([],'id,business_code,businessname','id desc');
            var_dump(count($list));
            foreach ($list as $key => $value) {
                $busData = Model::ins('StoBusiness')->getRow(['id'=>$value['id']],'customerid');
                $insert = [
                    'business_code' => intval($value['business_code']),
                    'isuse' => 1,
                    'businessid'=>$value['id'],
                    'businessname' => $value['businessname'],
                    'customerid' => $busData['customerid'],
                    'addtime' => date('Y-m-d H:i:s')
                ];
                $codeData = Model::ins('StoBusinessCode')->getRow(['business_code'=>$value['business_code']],'id');

                if(empty($codeData)){
                    $insert['addtime'] = date('Y-m-d H:i:s');
                    Model::ins('StoBusinessCode')->insert($insert);
                }else{
                    Model::ins('StoBusinessCode')->update($insert,['id'=>$codeData['id']]);
                }

            }
        //     $page+=1;
        //     echo $page;
        //     if(count($list)==0 || count($list)<$pagesize)
        //             break;
        // }
        echo "同步完成";
    }

    /**
     * [updatebountyuserAction 同步已注册用户领取记录脚本]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-28T15:09:48+0800
     * @return   [type]                   [description]
     */
    public function updatebountyuserAction(){
        $list = Model::ins('CusCustomer')->getList([],'id,mobile','id desc');

        foreach ($list  as $key => $value) {
            $insert = [
                'customerid'=>$value['id'],
                'mobile' => $value['mobile'],
                'isget' => 1,
                'addtime' => date('Y-m-d H:i:s'),
                'gettime' => date('Y-m-d H:i:s'),
                'amount'  => 1,
                'minamount' => 1,
                'payamount' => 0,
            ];
            $BountyUser = Model::ins('SysBountyUser')->getRow(['mobile'=>$value['mobile']],'id,isget');
            
            if(empty($BountyUser)){

                Model::ins('SysBountyUser')->insert($insert);

            }else{

                if($BountyUser['isget'] != 1){
                    Model::ins('SysBountyUser')->update($insert,['id'=>$BountyUser['id']]);
                }

            }
        }

        echo "同步完成";

    }

    /**
     * [updatecomarticleAction 同步公司新闻]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-09T18:58:33+0800
     * @return   [type]                   [description]
     */
    public function updatecomarticleAction(){
        $list = Model::ins('ComNewsArticle')->getList([],'*','id desc');
        foreach ($list  as $key => $value) {
            $content = Model::ins('ComNewsArticleContent')->getRow(['id'=>$value['id']],'description');

            $insert_mongo = $value;
            $insert_mongo['description'] = $content['description'];
            $article_mg = Model::Mongo('ComNewsArticle')->getRow(['id'=>$value['id']],'id');
            if(!empty($article_mg)){
                $insert_int['sort'] = intval($value['sort']);
                Model::Mongo('ComNewsArticle')->update(['id'=>$value['id']],$insert_mongo,$insert_int);
            }else{
              
                $insert_int['sort'] = intval($value['sort']);
                Model::Mongo('ComNewsArticle')->insert($insert_mongo,$insert_int);
            }
        }
        echo "同步完成";
    }




   /**
     * [updateproductpriceAction 更新商品价格]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-12T10:14:15+0800
     * @return   [type]                   [description]
     */
    public function updateproductpriceAction(){
        $pagesize = 50;
        $page     = 1;

        while(true){
            $product_list  = Model::ins('ProProduct')->pageList([],'id,saleprice,supplyprice,saletype,checksatus','id desc',0,$page,$pagesize);
            $page+=1;
            foreach ($product_list as $key => $product) {
                
               
                // $formart_price_arr = [
                //     'supplyprice' =>$product['supplyprice'],
                //     'saleprice' =>$product['saleprice'],
                //     'settle_cycle' =>intval($product['saletype'])
                // ];     
               
                // $formart_price = ProductModel::newFormatPrice($formart_price_arr); 
               
          
                // // print_r($formart_price);
                // if($formart_price['prouctprice'] >= 0 || $formart_price['bullamount'] > 0 ){
                   
                //     var_dump(Model::ins('ProProduct')->update(['prouctprice'=>$formart_price['prouctprice'],'bullamount'=>$formart_price['bullamount']],['id'=>$product['id']]));

                //     if($product['checksatus'] != 1){
                //         $product_price['enable'] = 0;
                //     }
                //     $product_price['prouctprice'] = $formart_price['prouctprice'];
                //     $product_price['bullamount'] = $formart_price['bullamount'];
                //     Model::Es("ProProduct")->update($product_price,['id'=>$product['id']]);
                // }
               
                $spec_product = Model::ins('ProProductSpec')->getList(['productid'=>$product['id']],'id,productid,saleprice,supplyprice,saletype');
                echo "productid:".$product['id']."\n";

                $arr_up_price = [];
                $arr_price    = [];

                foreach ($spec_product as $spec_key => $spec_value) {

                    echo "spec_product:".$spec_value['id']."\n";
                    $formart_price_arr_spec = [
                        'supplyprice' =>$spec_value['supplyprice'],
                        'saleprice' =>$spec_value['saleprice'],
                        'settle_cycle' =>intval($spec_value['saletype'])
                    ]; 
                    $formart_price_spec = ProductModel::newFormatPrice($formart_price_arr_spec);

                    $arr_price[$key][$spec_value['id']]  =  $formart_price_spec['prouctprice'];
                    $arr_up_price[$key][$spec_value['id']]['prouctprice'] =  $formart_price_spec['prouctprice'];
                    $arr_up_price[$key][$spec_value['id']]['bullamount']   =  $formart_price_spec['bullamount'];
                    $arr_up_price[$key][$spec_value['id']]['saleprice']    =  $spec_value['saleprice'];
                    $arr_up_price[$key][$spec_value['id']]['supplyprice']  =  $spec_value['supplyprice'];


                    if($formart_price_spec['prouctprice'] >= 0 ){ 
                     
                       Model::ins('ProProductSpec')->update(['prouctprice'=>$formart_price_spec['prouctprice'],'bullamount'=>$formart_price_spec['bullamount']],['id'=>$spec_value['id']]); 

                    }

                    if($formart_price_spec['prouctprice'] == 0 && $formart_price_spec['bullamount'] == 0 ){

                        Model::ins('ProProduct')->update(['enable'=>-1,'checksatus'=>0],['id'=>$product['id']]); 
                        Model::Es("ProProduct")->update(['enable'=>0],['id'=>$product['id']]);

                        $insert = [
                            'productid' =>$product['id'],
                            'addtime' => date('Y-m-d H:i:s')
                        ];

                        $Update_row =  Model::ins('ProProductUpdate')->getRow(['productid'=>$product['id']],'id');

                        if(empty($Update_row))
                            Model::ins('ProProductUpdate')->insert($insert);
                    }
                }

                if(!empty($arr_price)  && !empty($arr_up_price)){
                    // print_r($arr_price);
                    // print_r($arr_up_price);
                    $pos=array_search(min($arr_price[$key]),$arr_price[$key]);
                    // print_r($pos);
                    
                    Model::Es("ProProduct")->update($arr_up_price[$key][$pos],['id'=>$product['id']]);
                    Model::ins('ProProduct')->update($arr_up_price[$key][$pos],['id'=>$product['id']]);
                }
            }
           
            if(count($product_list)==0 || count($product_list)<$pagesize)
                    break;
        }
       
       
        echo "更新完成";
    }


    /**
     * [sendOneSystemMsg 单条逐个发送系统消息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-13T15:04:30+0800
     * @return   [type]                   [description]
     */
    public function sendOneSystemMsgAction(){
        
        Model::new("Sys.SendMsg")->sendOneSystemMsg();
        
        echo "ok";
    }
    
    /**
     * [sendOneSystemMsg 单条逐个发送系统消息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-13T15:04:30+0800
     * @return   [type]                   [description]
     */
    public function sendTowSystemMsgAction(){
        
        Model::new("Sys.SendMsg")->sendTwoSystemMsg();
        
        echo "ok";
    }

     /**
     * [sendTestSystemMsgAction 群发系统消息测试]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-23T10:27:12+0800
     * @return   [type]                   [description]
     */
    public function sendTestSystemMsgAction(){
        Model::new("Sys.SendMsg")->sendTestSystemMsg();
        
        echo "ok";
    }

    /**
     * [updateArtMongoAction 同步资讯]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-11T11:33:02+0800
     * @return   [type]                   [description]
     */
    public function updateArtMongoAction(){
        $article_list = Model::ins('ArtArticle')->getList(['isdelete'=>0],'*');
        foreach ($article_list  as $key => $value) {
            
            $article_mg = Model::Mongo('ArtArticle')->getRow(['id'=>$value['id']],'id');
            $article_row = Model::ins('ArtArticle')->getRow(['id'=>$value['id']]);
            $article_int['sort'] = $article_row['sort'];
            $article_int['hits'] = $article_row['hits'];
            

            if(!empty($article_mg)){
                Model::Mongo('ArtArticle')->update(['id'=>$id],$article_row,$article_int);
            }else{
                Model::Mongo('ArtArticle')->insert($article_row,$article_int);
            }
        }
        echo "ok";

    }

    /**
     * [updateStoBusinesCount 初始化商家人气销量粉丝]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-20T10:35:51+0800
     * @return   [type]                   [description]
     */
    public function updateStoBusinesCountAction(){
        $pagesize = 50;
        $page     = 1;

        while(true){
            $business_list  = Model::ins('StoBusinessInfo')->pageList([],'id,hits','id desc',0,$page,$pagesize);
            $page+=1;
            foreach ($business_list as $key => $business) {
               
                $hits = rand(100,500);
                $salecount = rand($hits*0.05,$hits*0.1);
                $fanscount = rand(100,500);
                // $stoOrderCount = Model::ins('StoOrder')->getRow(['businessid'=>$business['id']],'count(*) as count');
                // $flowCount = Model::ins('StoPayFlow')->getRow(['businessid'=>$business['id']],'count(*) as count');
                // $salecount = $stoOrderCount['count'] + $flowCount['count'];
                Model::ins('StoBusinessInfo')->update(['salecount'=>$salecount],['id'=>$business['id']]);
                Model::Mongo('StoBusinessInfo')->update(['id'=>$business['id']],['salecount'=>$salecount],['salecount'=>intval($salecount)]);
                
                

                Model::ins('StoBusinessInfo')->update(['hits'=>$hits],['id'=>$business['id']]);
                Model::Mongo('StoBusinessInfo')->update(['id'=>$business['id']],['hits'=>$hits],['hits'=>intval($hits)]);
               
                // $cus_bus = Model::ins('StoBusiness')->getRow(['id'=>$business['id']],'customerid');
                // $cus_count = Model::ins('CusRelation')->getRow(['parentid'=>$cus_bus['customerid']],'count(*) as count');
               
                Model::ins('StoBusinessInfo')->update(['fanscount'=>$fanscount],['id'=>$business['id']]);
                Model::Mongo('StoBusinessInfo')->update(['id'=>$business['id']],['fanscount'=>$fanscount],['fanscount'=>intval($fanscount)]);
                

            }
           
            if(count($business_list)==0 || count($business_list)<$pagesize)
                    break;
        }
       
       
        echo "更新完成";
    }

    /**
     * [upateBusinessGoodsAction 转移商品]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-22T14:01:50+0800
     * @return   [type]                   [description]
     */
    public function upateBusinessGoodsAction(){
        $oldMobile = '18126266902';
        $newMobile = '17621411161';
        
        $old_cus = Model::ins('CusCustomer')->getRow(['mobile'=>$oldMobile],'id');
        $old_bus = Model::ins('BusBusiness')->getRow(['customerid'=>$old_cus['id']],'id');
        $old_businessid = $old_bus['id'];
        var_dump($old_businessid);
        $new_cus = Model::ins('CusCustomer')->getRow(['mobile'=>$newMobile],'id');
        $new_bus = Model::ins('BusBusiness')->getRow(['customerid'=>$new_cus['id']],'id');
        $new_businessid = $new_bus['id'];
        var_dump($new_businessid);

        var_dump(Model::ins('ProProduct')->update(['businessid'=>$new_businessid],['businessid'=>$old_businessid]));
        var_dump(Model::ins('ProProductSpec')->update(['businessid'=>$new_businessid],['businessid'=>$old_businessid]));
        var_dump(Model::Es("ProProduct")->update(['businessid'=>$new_businessid],['businessid'=>$old_businessid]));

        
        echo "ok";
    }

    /**
     * [delProductRdAction 清除redis商品]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-22T16:13:03+0800
     * @return   [type]                   [description]
     */
    public function delProductRdAction(){
        $id_str = '11408,11409,11411,11412,11413,11415,11416,11417,11418,11419,11420,11421,11422,11423,11424,11425,11426,11427,11428,11429,11431,11432,11433,11435,11436,11437,11438,11439,11440,11441,11442,11443,11444,11445,11446,11447,11448,11449,11450,11451,11452,11453,11454,11455,11456,11457,11458,11459,11460,11461,11462,11463,11464,11465,11466,11467,11468,11469,11470,11471,11472,11473,11475,11476';

        $id_arr = explode(',', $id_str);
        $ProProductRD  = Model::Redis('ProProduct');
        foreach ($id_arr as $value) {
            var_dump($ProProductRD->del($value));
        }
        echo "ok";
    }
    

}