<?php
namespace app\model\User;

use app\lib\Model;
use think\Config;

class UserBonusModel
{
    /**
    * @user 返回用户消费分红比例(浮点型  非百分比)
    * @param 
    * @author jeeluo
    * @date 2017年10月19日下午3:20:45
    */
    public function getUserBonus() {
        $where['role'] = 1;         // 默认为1(购物券)
        $where['adddate'] = date("Y-m-d", time());
        
        // 读取数据
        $SysBonusSetting = Model::ins("SysBonusSetting");
        
        $bonusInfo = $SysBonusSetting->getRow($where,"proportion");
        
        if(empty($bonusInfo['proportion'])) {
            // 数据库没存储数据  读取配置文件内容
            $bonus = Config::get("bonus");
            
            $bonusInfo['proportion'] = $bonus['cus_bonus'];
        }
        
        return $bonusInfo['proportion'];
    }
}