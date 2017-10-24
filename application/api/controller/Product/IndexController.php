<?php
// +----------------------------------------------------------------------
// |  [ 2016-02-28 商品信息控制器 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ISir <673638498@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller\Product;
use app\api\ActionController;

//获取配置
use \think\Config;

use app\model\Product\ProductModel;
use app\lib\Model;

use app\model\User\TokenModel;
use app\model\User\CollectionModel;

class IndexController extends ActionController{
	
   /**
     * 固定不变
     */
   public function __construct(){
      parent::__construct();
   }

    /**
     * [goodsCommentAction 商品评价信息]
     * @return [type] [description]
     */
    public function goodsEvaluateAction(){
    	$goodsid = $this->params['goodsid'];//分类id
    	if(!empty($goodsid)){
    		
    		//商品信息
		   //商品信息
            $proData = ProductModel::getProDetailById($goodsid,"id as productid,productname");
            $commnentData = ProductModel::getAllProComment($goodsid);

		    //print_r($commnentData);
            if(!empty($proData)){

		    	//返回json数据
       			return $this->json('200',[
       				'commnentData'=>$commnentData
       				]);
		    }else{
		    	//返回json数据
       			return $this->json('5005');
		    }
    	}else{
    		//返回json数据
       		return $this->json('5002');
    	}
    }

    /**
     * [goodsDetailAction 商品详情]
     * @return [type] [description]
     */
    public function goodsDetailAction(){
    	$goodsid = $this->params['goodsid'];//分类id
        $mtoken = $this->params['mtoken'];

    	if(!empty($goodsid)){
            $userId = '';
            $check = false;
            if(!empty($mtoken)){
                $tokenModel = new TokenModel();
                $userId = $tokenModel->getTokenId($mtoken);
                
                $params = [
                    'type' => 1,
                    'obj_id' => $goodsid,
                    'userid' => $userId['id']
                ];
               
                $check = CollectionModel::checkCollectcount($params);
               
            }

    		   //商品信息
            $proData = ProductModel::getProDetailById($goodsid,"id,productname,prouctprice,supplyprice,bullamount,businessid,enable,thumb,productstorage,freight,categoryid,transportid",$userId['id']);
              
            if(!empty($proData)){
		        
                if($check){
                    $proData['iscollect'] = 1;
                }else{
                    $proData['iscollect'] = -1;
                }
		    	//返回json数据
       		   return $this->json('200',$proData);

		    }else{
		    	//返回json数据
       			return $this->json('5005');
		    }
    	}else{
    		//返回json数据
       		return $this->json('5002');
    	}
    }


    /**
     * [intoProSpecValeAction 更新商品的sku]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-26T14:40:52+0800
     * @return   [type]                   [description]
     */
    public function updateIntoProSpecValeAction(){
        exit;
        //获取所有商品
        $proData = Model::ins('ProProduct')->getList([],'id','id desc',1500);

        foreach ($proData as $key => $value) {
            // print_r($value);
            //获取sku信息
            $specDatas = Model::ins('ProProductSpec')->getList(['productid'=>$value['id']],'id,productspec,spec','id desc');
            $spec_ids = '';
            $spec_names = '';
           
            //取出各种sku值
            foreach ($specDatas as $ko => $vo) {    
                $spec_arr_tmp = json_decode($vo['productspec'],true);
                $specname_arr_tmp = json_decode($vo['spec'],true);
              
                foreach ($specname_arr_tmp as $kb => $vb) {
                    $spec_names .= $vb['name'].',';
                }
                foreach ($spec_arr_tmp as $ka => $va) {
                    $spec_ids .= $ka.',';
                }
            }
            
            //组合sku值 取出所有情况去掉重复的
            $specName = explode(',', rtrim($spec_names,','));
            $specNewName = array_unique($specName);

            //更新的sku 和对应的sku值
            $newSpecName = [];
            $newSpecValue = [];
            $updateSpecArr = [];

            //根据以后的sku值 取出sku信息
            foreach ($specNewName as $kc => $vc) {
                $spec_arr = Model::ins('ProSpec')->getRow(['specname'=>$vc],'id,specname'); 
                //print_r($spec_arr);
                $newSpecName[$spec_arr['id']] =  $spec_arr['specname'];
               

            }
            //已经有的sku对应的值
            foreach ($newSpecName as $kd => $vd) {

                if(!empty($kd)){
                    $spec_value_arr = Model::ins('ProSpecValue')->getList(['spec_id'=>$kd],'id,spec_value_name');
                    foreach ($spec_value_arr as $ke => $ve) {
                        $spec_value_arr_tmp[$ve['id']] = $ve['spec_value_name'];
                    }
                    $newSpecValue[$kd] = $spec_value_arr_tmp;
                }

            }
          
            if(!empty($newSpecValue)){

                $updateSpecArr = [
                    'spec_name'=> json_encode($newSpecName,JSON_UNESCAPED_UNICODE),
                    'spec_vlaue'=> json_encode($newSpecValue,JSON_UNESCAPED_UNICODE),
                ];
                
            }else{
                $updateSpecArr = [
                    'spec_name'=> json_encode([],JSON_UNESCAPED_UNICODE),
                    'spec_vlaue'=> json_encode([],JSON_UNESCAPED_UNICODE),
                ];
            }
           

            //if(!empty($updateSpecArr)){
            $SpecValueData = Model::ins('ProProductSpecValue')->getRow(['productid'=>$value['id']],'productid');
            if(!empty($SpecValueData)){
                var_dump(Model::ins('ProProductSpecValue')->update($updateSpecArr,['productid'=>$value['id']]));
            }else{
                $updateSpecArr['productid'] = $value['id'];
                var_dump(Model::ins('ProProductSpecValue')->insert($updateSpecArr));
            }
            ///}

        }

    }
    
}