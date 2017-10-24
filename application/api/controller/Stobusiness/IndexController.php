<?php
// +----------------------------------------------------------------------
// |  [ 实体店接口信息控制器 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-09
// +----------------------------------------------------------------------
namespace app\api\controller\Stobusiness;
use app\api\ActionController;


use app\model\User\TokenModel;
use app\model\StoBusiness\StobusinessModel;
use app\lib\Img;
use \think\Config;
use app\lib\Model;
use app\model\Rd\StoBusinessCodeRD;
use app\model\Sys\CommonRoleModel;
use  app\model\Mall\IndexModel;

class IndexController extends ActionController{
	
	/**
	 * [indexAction 实体店首页]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-09T20:52:30+0800
	 * @return   [type]                   [description]
	 */
	public function indexAction(){
        $city_id = $this->params['city_id'];
        $page = !empty($this->params['page']) ? $this->params['page'] : 1;
        if(empty($city_id))
            $city_id = "440300";
            //return $this->json('404');


        $data = [
            'banner'=>[
                [
                    "city_id" => "",
                    "bname" => "",
                    "thumb" => "",
                    "urltype" =>"",
                    "url" => ""
                ],[
                    "city_id" => "",
                    "bname" => "",
                    "thumb" => "",
                    "urltype" =>"",
                    "url" => ""
                ]
            ],
            'category'=>[
                [
                    "categoryid" => "",
                    "categoryname" => "",
                    "category_icon" => ""
                ],[
                    "categoryid" => "",
                    "categoryname" => "",
                    "category_icon" => ""
                ]
            ],
            'businessList'=>[
                "total" => "",
                "list" => [
                    [
                        "businessid" => "",
                        "businessname" => "",
                        "businesslogo" => "",
                        "categoryid" =>  "",
                        "categoryname" =>  "",
                        "area" => "",
                        "area_code" =>  "",
                        "lngx" => "",
                        "laty" =>  "",
                        "scores" =>  "",
                        "busstartime" =>  "",
                        "busendtime" => "",
                        "isdelivery" => "",
                        "isparking" => "",
                        "iswifi" => "",
                        "reutnproportion" => "",
                        "delivery" => "",
                        "isbusiness" => ""
                    ],[
                        "businessid" => "",
                        "businessname" => "",
                        "businesslogo" => "",
                        "categoryid" =>  "",
                        "categoryname" =>  "",
                        "area" => "",
                        "area_code" =>  "",
                        "lngx" => "",
                        "laty" =>  "",
                        "scores" =>  "",
                        "busstartime" =>  "",
                        "busendtime" => "",
                        "isdelivery" => "",
                        "isparking" => "",
                        "iswifi" => "",
                        "reutnproportion" => "",
                        "delivery" => "",
                        "isbusiness" => ""
                    ]
                ],
            ],
            'adv'=>[
                "city_id" => "",
                "city" => "",
                "bname" => "",
                "thumb" => "",
                "urltype" => "",
                "url" => ""
            ]

        ];

        $param['city_id'] = $city_id;
        $adPagePositon = Config::get("Advert.adPagePositon");
        $adListPositon = Config::get("Advert.adListPositon");
        $param['lngx'] = $this->params['lngx'];
        $param['laty'] = $this->params['laty'];
        $param['mtoken'] = $this->params['mtoken'];
        $param['area_code'] = $this->params['area_code'];
        
        if($this->Version("1.0.4")){
            
            $param['square'] = 1;
        }

        $businessList = StobusinessModel::businessList($param);
        
       

        $advParam = ['type'=>2,'city_id'=>$city_id];

        $advData = StobusinessModel::getOneAdvert($advParam);
      
        $lisArr = [];
      
     
        if($page == $adPagePositon){

            $i = 0;
           
            foreach ($businessList['data']['list'] as $key => $value) {
                
                $value['isadvert'] = -1;
                $lisArr[] = $value;
               
                if($key ==  $adListPositon  &&  !empty($advData['data']['thumb'])){
                    
                    $i = $key ;
                    $new_value[] = $value;
                   
                    $advData['data']['businessid'] = "";
                    $advData['data']['businessname'] = "";
                    $advData['data']['businesslogo'] = "";
                    $advData['data']['categoryid'] = "";
                    $advData['data']['categoryname'] = "";
                    $advData['data']['area'] = "";
                    $advData['data']['area_code'] = "";
                    $advData['data']['lngx'] = "";
                    $advData['data']['laty'] = "";
                    $advData['data']['scores'] = "";
                    $advData['data']['busstartime'] = "";
                    $advData['data']['busendtime'] = "";
                    $advData['data']['isdelivery'] = "";
                    $advData['data']['isparking'] = "";
                    $advData['data']['iswifi'] = "";
                    $advData['data']['reutnproportion'] = "";
                    $advData['data']['delivery'] = "";
                    $advData['data']['isbusiness'] = "";
                    $advData['data']['distance'] = "";
                    $advData['data']['isadvert'] = 1;

                    $lisArr[] = $advData['data'];
                   
                }
              
            }
            $businessList['data']['list'] = $lisArr;
            
        }
       
       
        

          
       
        if($page == 1){
          
            
            $announcement = IndexModel::announcement(2,$city_id);

            $banner = StobusinessModel::getBanner($param);
         
            $category = StobusinessModel::getCategory(10);
           
            $data = [
                'announcement' => $announcement,
                'banner'=>$banner['data'],
                'category'=>$category,
                'businessList'=>$businessList['data'],
                'adv' => $advData['data']
                
            ];
        }else{

           
            $data = [
                'businessList'=>$businessList['data']
            ];
        }
        return $this->json(200,$data);
        
	}

	

    /**
     * [indexBusinesslistAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-10T10:25:38+0800
     * @return   [type]                   [description]
     */
    public function indexBusinesslistAction(){
    	$city_id = $this->params['city_id'];
    	if(empty($city_id))
    		return $this->json('404');

    	
    	$data = StobusinessModel::businessList($this->params);
    	if($data['code'] == 200){
    		return $this->json($data['code'],$data['data']);
    	}else{
    		return $this->json($data['code']);
    	}
    }

    /**
     * [getCityAction 城市列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-10T14:45:05+0800
     * @return   [type]                   [description]
     */
    public function getCityAction(){

        // $city_id = $this->params['city_id'];
        // if(empty($city_id)) 
        //   return  $this->json('404');

       
        $data = StobusinessModel::getCityList();

        if($data['code'] == 200){
            return $this->json($data['code'],$data['data']);
        }else{
            return $this->json($data['code']);
        }
    }

  

    /**
     * [setPayMonetAction 设置付款金额 用户自己付款]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-25T09:46:52+0800
     */
    public function setPayMonetAction(){

        $this->params['customerid'] = $this->userid;
        $business_code = $this->params['business_code'];
        $data = StobusinessModel::getGatherMonet($this->params);

        if($data['code']==200){
            
            $cus = StobusinessModel::getCustomeridByCode(["business_code"=>$business_code]);
            if($cus['code'] == 200) {
                // 执行牛粉关系绑定
                // 判断扫描者 是否已经有牛粉关系归属(非公司)
                $cusRelation = Model::ins("CusRelation")->getRow(["customerid"=>$this->userid,"role"=>1],"parentid");
                if(!empty($cusRelation)) {
                    if($cusRelation['parentid'] != -1) {
                        // 不执行处理
                        return $this->json(200,$data['data']);
                    }
                }
                // 执行关系处理(1.0.4 后置需求)
                // 因为只关联牛粉关系，不关联实体点牛粉关系  所以这里不需要传递平台号
                $roleData = array("customerid"=>$this->userid,"userid"=>$cus['data'],"checkcode"=>md5($cus['data'].getConfigKey()));
                
                Model::new("User.Role")->updateCusRelation($roleData);
                CommonRoleModel::updateFansRoleRelation($roleData);
            }
            return $this->json(200,$data['data']);
        }else{
            return $this->json($data['code']);
        }
    }

    /**
     * [businessGatheringAction 商家收款 返回商家二维码和订单流水]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-25T11:05:20+0800
     * @return   [type]                   [description]
     */
    public function businessGatheringAction(){
       
        $this->params['customerid'] = $this->userid;

        $data = StobusinessModel::businessGathering($this->params);
       
        if($data['code'] == 200 ){
            
            unset($this->params['_apiname']);

            $this->params['managerid'] = $this->userid;


            $codeParam['managerid'] = $this->userid;
            $codeParam['business_code'] = $data['data']['business_code'];
            //$codeParam['businessname'] = $data['data']['businessname'];
           
            foreach ($codeParam as $k => $v) {
                $paramString .= "&$k=$v";
            }

            $domain = Config::get("stobusiness.domain");

            $url = $domain ."/?_apiname=Stobusiness.Index.getStoPayCodeUrl".$paramString;

            $data['data']['url'] = $url;

            return $this->json(200,$data['data']);

        }else{
            return $this->json($data['code']);
        }
    }

  

    /**
     * [setMonetAction 设置收款金额]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-24T17:50:18+0800
     */    
    public function setGatherMonetAction(){

        $this->params['customerid'] = $this->userid;
        $business_code = $this->params['business_code'];
        $data = StobusinessModel::setGatherMonet($this->params);
        if($data['code']==200){
            return $this->json($data['code'],$data['data']);
        }else{
            return $this->json($data['code']);
        }
    }

    /**
     * [getStoPayCode 生成付款码]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-24T20:37:22+0800
     * @return   [type]                   [description]
     */
    public function getStoPayCodeAction(){
      
        $this->params['customerid'] = $this->userid;
        $actionUrl = 'stobusiness.index.payMoney';
        $checkData = StobusinessModel::checkcode($this->params);
       
        if($checkData['code'] == 200){

            $TokenOBJ = new TokenModel();
            $tokeData = $TokenOBJ->getTokenId($this->params['mtoken']);

            if(!empty($tokeData['id'])){
                $this->params['mtoken'] = $tokeData['id'];
            }

            $codeParam['managerid'] = $this->params['mtoken'];
            $codeParam['business_code'] = $checkData['data']['business_code'];
            $codeParam['noinvamount'] = $checkData['data']['noinvamount'];
            $codeParam['amount'] = $checkData['data']['amount'];
            //$codeParam['businessname'] = $checkData['data']['businessname'];
            foreach ($codeParam as $k => $v) {
                $paramString .= "&$k=$v";
            }

            $domain = Config::get("stobusiness.domain");

            $url = $domain."/?_apiname=Stobusiness.Index.getStoPayCodeUrl".$paramString;

            $data = [
                    'url'=>$url,
                    'business_code'=>$checkData['data']['business_code'],
                    'businessname'=>$checkData['data']['businessname'],
                    'amount'=>$checkData['data']['amount'],
                    'noinvamount'=>$checkData['data']['noinvamount'],
                    'managerid' => $params['mtoken']
            ];
        

         
             return $this->json(200,$data);
            //StobusinessModel::getStoPayCode($actionUrl, $this->params);
            
        }else{
            return $this->json($checkData['code']);
        }
    }

    /**
     * [getStoPayCodeUrl 输出付款二维码图片]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-25T10:16:46+0800
     * @return   [type]                   [description]
     */
    public function getStoPayCodeUrlAction(){
       
        $actionUrl = '';
       
        $codeParam['managerid'] =  !empty($this->params['managerid']) ? $this->params['managerid'] : (string) "";
        $codeParam['business_code'] = !empty($this->params['business_code']) ? $this->params['business_code'] : (string) "";
        $codeParam['stocode'] = !empty($this->params['business_code']) ? $this->params['business_code'] : $this->params['business_code'];
        $codeParam['businessname'] = !empty($this->params['businessname']) ? (string) "" : (string) "";
        //$codeParam['managerid'] = !empty($this->params['managerid']) ? $this->params['managerid'] : (string) "" ;
        $codeParam['noinvamount'] = !empty($this->params['noinvamount']) ? $this->params['noinvamount'] : (string)  "";
        $codeParam['amount'] = !empty($this->params['amount']) ? $this->params['amount'] :  (string)  "" ;
        $codeParam['type'] = 2; //二维码类型 2是实体店收款二维码
        StobusinessModel::getStoPayCode($codeParam);
    }



    /**
    * @user 获取实体店分类
    * @param 
    * @author jeeluo
    * @date 2017年3月24日下午5:49:35
    */
    public function stocategoryAction() {
        $data = StobusinessModel::categoryList();
        return $this->json($data['code'], $data['data']);

    }



    /**
     * [payMoneyAction 支付]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-24T20:40:08+0800
     * @return   [type]                   [description]
     */
    public function addpayfollowAction(){
       
        $this->params['userid'] = $this->userid;
        $result = Model::new("StoBusiness.Stobusiness")->addorder($this->params);

        if($result['code'] == 200){

            return $this->json(200,$result['data']);
        }else{

            return $this->json($result['code']);
        }
    }

    /**
     * [stoOrderDetailAction 订单详情]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-29T17:04:44+0800
     * @return   [type]                   [description]
     */
    public function stoOrderDetailAction(){

        $this->params['customerid'] = $this->userid;

        $sign           = $this->params['sign']; //md5(按业务字段排序(address_id+items))
        $sign           = strtoupper($sign);

        $tokenModel = new TokenModel();

        $managerToken = $this->params['managerid'];
        $this->params['managerid'] = $tokenModel->getTokenId($this->params['managerid'])['id'];
        $this->params['managerToken'] = $managerToken;
        $data = StobusinessModel::stoOrderDetail($this->params);
        if($data['code'] == 200){
            return $this->json(200,$data['data']);
        }else{
            return $this->json($data['code']);
        }
    }

    /**
     * [getSobannerAction 获取实体店banner]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-31T10:49:38+0800
     * @return   [type]                   [description]
     */
    public function getSobannerAction(){

        $param['city_id'] = $this->params['city_id'];
        $banner = StobusinessModel::getBanner($param);
        return $this->json(200,$banner['data']);
    }

 
   

    


   
}