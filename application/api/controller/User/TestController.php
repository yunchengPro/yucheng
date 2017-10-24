<?php
namespace app\api\controller\User;
use app\api\ActionController;
use app\lib\Sms;
use app\lib\Model;

use think\Db;
use think\Config;
use app\model\Profit\CashFactory;
use app\model\Product\ProductModel;
use app\model\Sys\CommonModel;
use app\model\StoBusiness\StobusinessModel;
use app\model\Sys\CommonRoleModel;
use app\lib\Log;
use app\lib\QRcode;
use app\model\Profit\Cash_abstract;
use app\model\CusCustomerAgentModel;
//use traits\model\SoftDelete;
//use app\index\model\Company;
//use think\console\Command;
//use app\index\model\Cash_abstract;
//use app\modelProfit\\Entity;

class TestController extends ActionController
{
    
    public function test1Action() {
        $where = array();
        $list = Model::ins("StoBusiness")->getList($where,"id","id asc");
        
        foreach($list as $v) {
            $info = Model::ins("StoBusinessInfo")->getRow(array("id" => $v['id']), "area_code");
            $baseInfo = Model::ins("StoBusinessBaseinfo")->getRow(array("id" => $v['id']), "address");
            
            if($info['area_code'] == 0 && $baseInfo['address'] == '') {
                continue;
            }
            
            $areaname = CommonModel::getSysArea($info['area_code']);
            $map = CommonModel::getAddressLngLat($areaname['data'].$baseInfo['address']);
            
            if($map['code'] == 200) {
                $data['lngx'] = $map['data']['lngx'];
                $data['laty'] = $map['data']['laty'];
                Model::ins("StoBusinessInfo")->modify($data, array("id" => $v['id']));
            }
        }
        return $this->json(200);
    }
    
//     public function quickAddBusRoleAction() {
//         if(empty($this->params['mobile'])) {
//             return $this->json(404);
//         }
        
//         $cus = Model::ins("CusCustomer")->getRow(array("mobile" => $this->params['mobile']), "id,createtime");
        
//         if(!empty($cus)) {
            
//             /*
//              * 暂时写入角色表 确保能登录，不写入关系表(不写牛店数据) 2017-04-27 11:43:22
//              */
            
//             $cusRoleFans = Model::ins("CusRole")->getRow(array("customerid" => $cus['id'], "role" => 1));
            
//             if(empty($cusRoleFans)) {
//                 Model::ins("CusRole")->insert(array("customerid" => $cus['id'], "role" => 1, "addtime" => $cus['createtime']));
//             }
            
//             $cusRoleBus = Model::ins("CusRole")->getRow(array("customerid" => $cus['id'], "role" => 4));
            
//             if(empty($cusRoleBus)) {
//                 Model::ins("CusRole")->insert(array("customerid" => $cus['id'], "role" => 4, "addtime" => $cus['createtime']));
//             }
//             return $this->json(200);
//         }
//         return $this->json(10003);
//     }
     
    public function indexAction()
    {
        
        $logo = "D://logo.png";
        $QR = "D://test.png";
        $last = "D://last.png";
        QRcode::png("http://www.baidu.com", $QR, 4, 10);
        if($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);
            $QR_height = imagesy($QR);
            $logo_width = imagesx($logo);
            $logo_height = imagesy($logo);
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
        imagepng($QR,$last);
        exit;
        
//         $temp = CommonModel::getFormatTime('2017-04-12 22:12:12');
//         print_r($temp);
//         $applyInfo = Model::ins("RoleApplyLog")->getRow(array("id" => 42));
//         Model::new("Customer.BullPeoRole")->apply($applyInfo);
//         Sms::send("13265420062", ["admin.nnht.cn", rand(100000, 999999)], "165110");
//         $temp = StobusinessModel::creatStoBusCode(array("businessid" => 1));
//         $temp = CommonModel::getAddressLngLat("广东省深圳市南山区腾讯大厦");
//         print_r($temp);
//         exit;
//         $arr = array();
//         $arr[0]['a'] = 1;
//         $arr[1]['a'] = 2;
        
//         echo sprintf("%.2f",substr(sprintf("%.4f", 1112.458), 0, -2));
        
//         echo custom_number_format(1.1);


//         $result = Db::table('test')->where('id', 2)->delete();
//         print_r($result);

        
        
//         print_r ((object)$arr);
//         $result = Db::table('test')->fields('id')->where('id', '>=', 1)->select(false);
//         print_r($result);
//         return 'year='.$year.'&month='.$month;
//         $requestInfo = Request::instance()->header();
//         print_r($requestInfo);
//         return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
    
    public function testAction() {
        $orderObj = (object)[];
        
//         $order = array();
//         $order[0]['parentFactoryId'] = 21;          // 订单商品1 的供应商上级
//         $order[0]['bestFactoryId'] = 42;            // 订单商品1 的供应商上上级
//         $order[1]['parentFactoryId'] = 22;          // 订单商品2 的供应商上级
//         $order[1]['bestFactoryId'] = 52;            // 订单商品2 的供应商上上级
        
     
        $configTmp = Config::get('cashconfig');
        
        
        
        $goodslist = array();
//         $goodslist[0]['id'] = 1;
//         $goodslist[0]['sell'] = 99;                // 订单商品1 的出售价
//         $goodslist[0]['cost'] = 69.3;                // 订单商品1 的成本价
//         $goodslist[0]['type'] = 1;                  // 订单商品1 的类型 现金商城
//         $goodslist[0]['discount'] = ($goodslist[0]['cost'] / $goodslist[0]['sell']) * 10;              // 商品折扣
//         $goodslist[0]['parentFactoryId'] = 21;
//         $goodslist[0]['bestFactoryId'] = 42;
        
        $goodslist[1]['id'] = 2;
        $goodslist[1]['sell'] = DePrice(10-2);
        $goodslist[1]['cost'] = (10-2)*(90/100)/100;
        $goodslist[1]['type'] = 2; //实体店
        $goodslist[1]['discount'] = ($goodslist[1]['cost'] / $goodslist[1]['sell']) * 10;
        $goodslist[1]['parentFactoryId'] = 22;
        $goodslist[1]['bestFactoryId'] = 52;
        
        $resultarr = array();
        foreach ($goodslist as $k => $goods){
            
            $userInfoObj = (object)[];

            //$temp  = (object)[];
            
            $userInfoObj->userId = 1;                   // 用户id
            $userInfoObj->bindId = 11;                  // 绑定实体店id
            $userInfoObj->parentEntityId = 41;          // 实体店的创业者id
            $userInfoObj->parentSecondEntityId = 43;    // 实体店的创业者上级id
            $userInfoObj->parentBestEntityId = 44;      // 实体店的创业者上上级id
            $userInfoObj->parentConsump = 2;            // 用户上级消费者
            $userInfoObj->parentBestConsump = 3;        // 用户上上级消费者
            
            $userInfoObj->countyAgentId = 31;           // 区县代理
            $userInfoObj->countyRecommend = 100;        // 区县代理的推荐者
            $userInfoObj->cityAgentId = 32;             // 地市代理
            $userInfoObj->cityRecommend = 101;          // 地市代理的推荐者
            
            $userInfoObj->payAction = 1;                
            
            $userInfoObj->goodsInfo = $goods;
            
            $fa = CashFactory::getChoice($userInfoObj, $orderObj);  
            //var_dump($fa);
            $resultarr[$k] = $fa->getBalance() ?: (object)[];

            //$tempObj = $fa->getBalance() ?: (object)[];
            
            //print_r($tempObj->entityProfit);
            //exit;
            
            //$entity = new Entity();
            //$resultarr[$k] = $entity->getEntityProfit($tempObj);
        }
        
        print_r($resultarr);
    }
}
