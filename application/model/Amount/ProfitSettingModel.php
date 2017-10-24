<?php
// +----------------------------------------------------------------------
// |  [ 分润配置 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author zhuangqm
// | @DateTime 2017-03-015
// +----------------------------------------------------------------------
namespace app\model\Amount;

use think\Config;
use app\lib\Model;

class ProfitSettingModel{

    public static function getfield(){
        return [
            "userBind" => "用户获得收益金额",      // 用户获得收益金额
            "companyBind" => "公司获得收益余额",   // 公司获得收益余额
            "bullNumber" => "用户获得牛币",    // 用户获得牛币
            "companyBull" => "公司获得牛币",   // 公司获得牛币
            "entityProfit" => "绑定实体店分润金额",  // 绑定实体店分润金额
            "selfProfit" => "实体店引荐者",    // 实体店引荐者
            "parentProfit" => "引荐者上级创业者",  // 引荐者上级创业者
            "bestProfit" => "引荐者上上级创业者",    // 引荐者上上级创业者
            "countAgentProfit" => "区县代理",  // 区县代理
            "countyAgentRecoProfit" => "区县代理引荐者", // 区县代理引荐者
            "cityAgentProfit" => "地市代理",   // 地市代理
            "cityAgentRecoProfit" => "地市代理引荐者",  // 地市代理引荐者
            "consumerParentProfit" => "上级消费者", // 上级消费者
            "consumerBestProfit" => "上上级消费者",   // 上上级消费者
            "procedureProfit" => "交易手续费",      // 交易手续费
            "charitableProfit" => "慈善",     // 慈善
            "factoryProfit" => "供应商推荐人",        // 供应商推荐人
            "factoryParentProfit" => "供应商推荐人的上级创业者",  // 供应商推荐人的上级创业者
            //"companyProfit" => "公司",        // 公司
        ];

    }

    /**
     * 获取分润配置
     * @Author   zhuangqm
     * @DateTime 2017-05-10T15:42:44+0800
     * @param    [type]                   $param [description]
     *                                           objtype 1牛商2牛店
     *                                           objid 对象ID
     * @return   [type]                          [description]
     */
    public static function setting($param){
        $objtype    = $param['objtype'];
        $objid      = $param['objid'];

        $setting = Model::ins("AmoProfitSetting")->getList(["objid"=>$objid,"objtype"=>$objtype],"*");

        $profit_setting = [];
        foreach($setting as $k=>$v){

            $profit_setting[$v['profit']] = $v['status']; // -1表示不分润

        }

        return $profit_setting;
    }

    /**
     * 重新计算分润
     * @Author   zhuangqm
     * @DateTime 2017-05-12T15:47:19+0800
     * @return   [type]                   [$profit 分润结果]
     */
    public static function profit($param){

        $profit_setting = self::setting([
                "objtype"=>$param['objtype'],
                "objid"=>$param['objid'],
            ]);

        $profit = $param['profit'];

        $companyProfit = $profit['companyProfit']; // 分给公司
        foreach($profit as $key=>$value){
            if($key!='companyProfit'){
                if(!empty($profit_setting[$key]) && $profit_setting[$key]=='-1'){
                    $profit[$key] = 0;
                    $companyProfit+=$profit[$key];
                }
            }
        }
        
        $profit['companyProfit'] = $companyProfit;

        return $profit;
    }
}