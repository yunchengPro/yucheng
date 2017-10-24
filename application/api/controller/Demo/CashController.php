<?php
namespace app\api\controller\Demo;

use app\api\ActionController;
use app\model\Profit\Cash_abstract;
use app\model\Profit\CashFactory;
use app\model\Profit\Entity;

class CashController extends ActionController {
    public function __construct() {
        parent::__construct();
    }
    
    public function indexAction() {
        
        $orderObj = (object)[];
        $configTemp = Cash_abstract::getCashConfig();
        
        $goodslist = array();
        $goodslist[0]['id'] = 1;
        $goodslist[0]['sell'] = 200;                // 订单商品1 的出售价
        $goodslist[0]['cost'] = 100;                // 订单商品1 的成本价
        $goodslist[0]['type'] = $configTemp->CashGoodsType;                  // 订单商品1 的类型
        $goodslist[0]['discount'] = ($goodslist[0]['cost'] / $goodslist[0]['sell']) * 10;              // 商品折扣
        $goodslist[0]['parentFactoryId'] = 21;
        $goodslist[0]['bestFactoryId'] = 42;
        
        $goodslist[1]['id'] = 2;
        $goodslist[1]['sell'] = 500;
        $goodslist[1]['cost'] = 400;
        $goodslist[1]['type'] = $configTemp->BindGoodsType;
        $goodslist[1]['discount'] = ($goodslist[1]['cost'] / $goodslist[1]['sell']) * 10;
        $goodslist[1]['parentFactoryId'] = 22;
        $goodslist[1]['bestFactoryId'] = 52;
        
        $resultarr = array();
        
        foreach ($goodslist as $k => $goods) {
            $userInfoObj = (object)[];
            $userInfoObj->userId = 1;
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
            
            $userInfoObj->payAction = 1;                //(待确定)
            
            $userInfoObj->goodsInfo = $goods;
            
            // 静态化工厂实例
            $fa = CashFactory::getChoice($userInfoObj, $orderObj);
            
//             $resultarr[$k] = $fa->getBalance() ?: (object)[];

            $tempObj = $fa->getBalance() ?: (object)[];
            
            // 实例化对象角色
            $entity = new Entity();
            // 返回某个对象的操作
            $resultarr[$k] = $entity->getEntityProfit($tempObj);
        }
        
        print_r($resultarr);
    }
}