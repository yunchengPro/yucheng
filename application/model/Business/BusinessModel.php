<?php
// +----------------------------------------------------------------------
// |  [ 店铺模型 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-03
// +----------------------------------------------------------------------
namespace app\model\Business;
use app\lib\Db;
use app\lib\Img;
use app\model\Business;

use app\lib\Model;
use app\model\OrdOrderItemModel;

class BusinessModel{

	/**
	 * [getBusinessInfoById 通过id获取商家信息]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-03T21:42:09+0800
	 * @return   [type]                   [description]
	 */
	public static function getBusinessInfoById($id,$fileds=''){

        $fileds = empty($fileds) ? 'id,businessname,businesslogo' :  $fileds;
		//商品sku信息
        $BusBusinessInfo = Db::Model("BusBusinessInfo");
        $BusBusinessBanner = Db::Model("BusBusinessBanner");
        $businessInfo = $BusBusinessInfo->getRow(['id'=>$id],$fileds);
       	$BannerData = $BusBusinessBanner->getList(['businessid'=>$businessInfo['id']],'*','sort asc',5);
        foreach ($BannerData as $key => $value) {
            $BannerData[$key]['thumb'] = Img::url($value['thumb']);
        }

       
        if(empty($BannerData)){
           $BannerData = $BusBusinessBanner->getList(['businessid'=>0],'*','sort asc',5);
            foreach ($BannerData as $key => $value) {
                $BannerData[$key]['thumb'] = Img::url($value['thumb']);
            }
        }
        $service =self::businessnService(['businessid'=>$businessInfo['id']]);

        if($service){
            $businessInfo['isservice'] = 1;
        }else{
            $businessInfo['isservice'] = -1;
        }

       	$businessInfo['banner'] = $BannerData;
        $businessInfo['businesslogo'] =  Img::url($businessInfo['businesslogo']);
        return $businessInfo;
	}

	/**
	 * [getBusinessCategoryById 通过id获取所有店铺分类]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-04T15:01:07+0800
	 * @return   [type]                   [description]
	 */
	public static  function  getBusinessCategoryById($id){
		$BusBusinessCategory = Db::Model('BusBusinessCategory');
		return $BusBusinessCategory->getCateData($id);
	}

	/**
     * 获取店铺基本信息：图片+店铺名称
     * @Author   zhuangqm
     * @DateTime 2017-02-28T17:53:36+0800
     * @param    [type]                   $id [description]
     * @return   [type]                       [description]
     */
	public static function getBaseInfo($id){
        
        $row = Db::Table("BusBusiness")->getRow(["id"=>$id],"businessname");
        return $row;
    }
    
    /**
     * 通过商家id检查商家是否合法
     * Author:xurui
     * Date:2017/03/12
     * @Param: business_id int
     * @Return boolean
     */
    public static function checkBusiness ($business_id) {
        if (!empty($business_id) && is_numeric($business_id)) {
            $business_info = Db::Table('BusBusiness') -> getRow(array('id'=>$business_id,'enable'=>1),'businessname');//商家信息
            if (!empty($business_info['businessname']))  return true;
            else return false;
        }
        return false;
    }

    /**
     * 获取牛商关系
     * @Author   zhuangqm
     * @DateTime 2017-03-17T11:34:58+0800
     * @param    [type]                   $business_id [description]
     * @return   [type]                                [description]
     */
    public static function getBusinessRelation($business_id){
        $busOBJ = Model::ins("BusBusiness");
        $item = $busOBJ->getRow(['id'=>$business_id,"enable"=>1],"customerid");

        $item['customerid'] = !empty($item['customerid'])?$item['customerid']:-1;

        $cusrelationOBJ = Model::ins("CusRelation");

        $relation = $cusrelationOBJ->getRow(['customerid'=>$item['customerid'],"role"=>4],"parentid,parentrole,grandpaid,grandparole");

        $relation['introducerid']   = !empty($relation['parentid'])?$relation['parentid']:-1;
        $relation['pintroducerid']  = !empty($relation['grandpaid'])?$relation['grandpaid']:-1;
        $relation['introducerid_role']   = $relation['parentrole'];
        $relation['pintroducerid_role']  = $relation['grandparole'];
        $relation['business_customerid']  = $item['customerid'];
        return $relation;
    }

    /**
     * [updateBuinessInfo 修改商家信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-23T15:46:50+0800
     * @param    [type]                   $id     [description]
     * @param    [type]                   $updata [description]
     * @return   [type]                           [description]
     */
    public static function  updateBuinessInfo($id,$updata){
       
        $BusBusinessInfo = Model::ins("BusBusinessInfo");
        //$BusBusinessInfoMG =  Model::Mongo("BusBusinessInfo");
        //print_r($updata);
        if(!empty($id) && !empty($updata) && is_array($updata)){

            $BusBusinessInfo->update($updata,['id'=>$id]);
            Model::ins("BusBusiness")->update(['businessname'=>$updata['businessname']],['id'=>$id]);
            $id = (string) $id;
            //获取mongoDb数据
            // $MGbusInfo = $BusBusinessInfoMG->getRow(["id"=>$id]);
            // if(empty($MGbusInfo)){
            //     //写入mongoDb
            //     $updata['id'] = $id;
            //     $BusBusinessInfoMG->insert($updata);
            // }else{
            //     //更新mogoDb数据
            //     $BusBusinessInfoMG->update(["id"=>$id],$updata);
            // }
            return $id;
        }else{
            return false;
        }
    }

    /**
     * 获取供应商电话 返回数组
     * @Author   zhuangqm
     * @DateTime 2017-04-07T11:39:22+0800
     * @param    [type]                   $businessid [供应商ID]
     * @return   [type]                               [数组]
     */
    public static function getBusinessTel($businessid){
        
        $info = Model::ins("BusBusinessInfo")->getRow(['id'=>$businessid],"servicetel");
        $tel_arr = [];
        if(!empty($info['servicetel'])){
            $servicetel = explode(",",$info['servicetel']);
            foreach ($servicetel as $key => $value) {
                $tel_arr[] = $value;
            }
            
            return  $tel_arr;
        }else{
            return [];
        }
    }

    /**
     * [getCustomerBusiness 通过用户id获取店铺信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-26T15:45:20+0800
     * @return   [type]                   [description]
     */
    public function getCustomerBusiness($customerid){
        if(!empty($customerid)){
            $business = Model::ins('BusBusiness')->getRow(['customerid'=>$customerid,'enable'=>1,'ischeck'=>1],'id');

            return $business['id'];
        }else{
            return false;
        }
    }

    /**
     * [getBusinessEvaluateList 获取评价列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-26T15:28:16+0800
     * @param    [type]                   $businessid [description]
     * @return   [type]                               [description]
     */
    public static function getBusinessEvaluateList($params){
      
        if(!empty($params['businessid'])){

            $where['businessid'] = $params['businessid'];
            $where['parentid']  = 0 ;
            $where['enable']  = 1 ;

            $starttime = $params['starttime'] .' 00:00:00';
            $endtime = $params['endtime'] .' 23:59:59';

            if(!empty($params['starttime']) && !empty($params['endtime'])){
                $where['addtime'] = [
                    [">=",$starttime],
                    ["<=",$endtime,'and']
                ];
            }
            $businessinfo = Model::ins('BusBusinessInfo')->getRow(['id'=>$params['businessid']],'scores');

            $list = Model::ins('ProEvaluate')->pageList($where,'id as evaluate_id,businessid,productid,skuid,productname,productprice,scores,content,frommemberid,frommembername,headpic,isanonymous,addtime',"replytime desc,addtime desc");
            $list['scores'] = scoresFormat($businessinfo['scores']);
            
            $ordItemOBJ = new OrdOrderItemModel();
            foreach ($list['list'] as $key => $value) {

                $userData = Model::ins('CusCustomerInfo')->getRow(['id'=>$value['frommemberid']],'nickname,headerpic');
                $mobile  = Model::ins('CusCustomer')->getRow(['id'=>$value['frommemberid']],'mobile')['mobile'];
                $list['list'][$key]['addtime'] = substr($value['addtime'], 0,10);
                if(empty($value['headpic'])){
                    $list['list'][$key]['headpic'] = Img::url($userData['headerpic']);
                }else{
                    $list['list'][$key]['headpic'] =  Img::url($value['headerpic']);
                }

                $list['list'][$key]['frommembername'] = empty($userData['nickname']) ?  $mobile : $userData['nickname'];
            
                if($value['isanonymous'] == 1)
                    $list['list'][$key]['frommembername'] = substr_cut($list['list'][$key]['frommembername']);

                $list['list'][$key]['productprice']  = DePrice($value['productprice']);
                $goodsInfo = $ordItemOBJ->getItemDetailBySkuid($value);
                //print_r($goodsInfo);
                $list['list'][$key]['name'] = $goodsInfo['productname'];
                $list['list'][$key]['skudetail'] = !empty($goodsInfo['skudetail']) ? $goodsInfo['skudetail'] : '';
                $thumbData = Model::ins('ProProduct')->getRow(['id'=>$v['productid']],'id,thumb');
                $list['list'][$key]['thumb'] = Img::url($thumbData['thumb']);
                $list['list'][$key]['productprice'] = DePrice($goodsInfo['prouctprice']);
                $list['list'][$key]['bullamount'] =  DePrice($goodsInfo['bullamount']);

                $tmp_img = [];
                $imgList =  Model::ins('ProEvaluateImage')->getList(['evaluate_id'=>$v['evaluate_id']],'thumb');
               
                foreach ($imgList as $imgKey => $imgValue) {
                    if(!empty($imgValue['thumb'])){
                        $imgValue['thumb'] = str_replace('http://nnhtest.oss-cn-shenzhen.aliyuncs.com/', '', $imgValue['thumb']);
                        $tmp_img[] = Img::url($imgValue['thumb'],500,500);
                    }
                }

                $list['list'][$key]['img_arr'] = $tmp_img;

                $reply = '';
                $repData = Model::ins('ProEvaluate')->getRow(['parentid'=>$value['evaluate_id']],'content');

                if(!empty($repData))
                    $reply = '店家回复：'.$repData['content'];

                $list['list'][$key]['reply'] = $reply;


            }
            return $list ;
        }else{
            return false;
        }
    }

    /**
     * [relpyEvaluate 回复评价]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-26T17:28:10+0800
     * @return   [type]                   [description]
     */
    public static function relpyEvaluate($params){
        
       

        if(empty($params['evaluateid']))
          
            return ['code'=>404];
        if(empty($params['content']))
            return ['code'=>404];

        $strLen = getStringLength($params['content']);

        if($strLen > 500){
            return ['code'=>5012];
        }
        $reply = $params['content'];
        $where['id'] = $params['evaluateid'];
        $topWhere['parentid'] = $params['evaluateid'];
        $data = Model::ins("ProEvaluate")->getRow($where,'id,businessid,productid,productname,scores,isanonymous,frommemberid,frommembername,state');
      
        if(empty($data))
            return ['code'=>60014];

        if($data['businessid'] != $params['businessid'])
            return ['code'=>1001];

        $insert = [
            'productid'=>$data['productid'],
            'productname'=>$data['productname'],
            'scores' => $data['scores'],
            'isanonymous' => $data['isanonymous'],
            'frommemberid' => $params['userid'] ,
            'frommembername' => '商家回复',
            'state' => $data['state'],
            'parentid' => $data['id'],
            'rootid' => $data['id'],
            'content' => $reply,
            'addtime' => date('Y-m-d H:i:s'),
            'replytime'=> date('Y-m-d H:i:s')
        ];
        $repData = Model::ins("ProEvaluate")->getRow($topWhere,'id');
       
        if(empty($repData)){
            $ret = Model::ins("ProEvaluate")->insert($insert);
                
            if($ret > 0){
                return ['code'=>200,'data'=>$ret];
            }else{
                 return ['code'=>400];
            }
        }else{
            return ['code'=>60013];
        }
    }

    /**
     * [businessnService 商家是否服务]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-24T20:26:02+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public static function businessnService($param){

        $businessData = Model::ins('BusBusiness')->getRow(['id'=>$param['businessid']],'id');

        if(empty($businessData)){
            return false;
        }else{
            return true;
        }
    }
}