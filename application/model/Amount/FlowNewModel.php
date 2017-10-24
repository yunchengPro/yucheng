<?php
namespace app\model\Amount;

class FlowNewModel {
    
    // 牛人小组分享收益流水类型
    public function getCommunityShareType($params) {
        $type = $params['type'];
        $str = 0;
        if($type == 1) {
            // 牛票
            $str = $this->getCommunityShareRoleCash(["role"=>$params['role']]);
        } else if($type == 2) {
            // 预留牛粮
        } else if($type == 3) {
            // 牛豆
            $temp = $this->getCommunityShareRoleBull(["role"=>$params['role']]);
            $str = $this->getCommunityShareFansBull();
            if($temp != 0) {
                $str .= ",".$temp;
            }
        } else if($type == 4) {
            // 牛粮奖励金
            $str = $this->getCommunityShareRoleBonus(["role"=>$params['role']]);
//             $str = $this->getCommunityShareFansBonus();
//             if($temp != 0) {
//                 $str .= ",".$temp;
//             }
        } else if($type == 5) {
            // 孵化中心 牛粮奖励金
            $str = $this->getCommunityShareRoleCash(["role"=>$params["role"]]).",".$this->getCommunityShareRoleBonus(["role"=>$params['role']]);
        } else if($type == 6) {
            $str = $this->getCommunityShareRoleCash(["role"=>$params["role"]]).",".$this->getCommunityShareRoleBonus(["role"=>$params['role']]);
        }
        return $str;
    }
    
    // 牛人小组分享收益 拓展实体店奖励
    public function getCommunityShareStoType($params) {
        $type = $params['type'];
        $str = 0;
        if($type == 1) {
            // 牛票
            $str = $this->getCommunityShareStoCash();
        } else if($type == 2) {
            
        } else if($type == 3) {
            // 牛豆
            $str = $this->getCommunityShareStoBull();
        } else if($type == 4) {
            // 牛粮奖励金
            $str = $this->getCommunityShareStoBonus();
        }
        return $str;
    }
    
    
    /**
    * @user 服务员流水类型
    * @param 
    * @author jeeluo
    * @date 2017年8月15日下午3:41:08
    */
    public function getServiceShareType() {
        return $this->serviceShareType().','.$this->serviceTradeType();
    }
    
    public function getCommunityShareFansBull() {
        return 50;
    }
    
    public function getCommunityShareFansBonus() {
        return 101;
    }
    
    public function getCommunityShareRoleCash($params) {
        if($params['role'] == 2)
            return 35;
        else if($params['role'] == 8)
            return 58;
        else if($params['role'] == 3)
            return 36;
        else if($params['role'] == 6)
            return "135,145,70";
        else if($params['role'] == 7)
            return "139,149,74";
        return 0;
    }
    
    public function getCommunityShareRoleBonus($params) {
        if($params['role'] == 2)
            return 123;
        else if($params['role'] == 8)
            return "123,131";
        else if($params['role'] == 3)
            return "123,127";
        else if($params['role'] == 6)
            return "137,147,153";
        else if($params['role'] == 7)
            return "141,151,155";
        return 0;
    }
    
    public function getCommunityShareRoleBull($params) {
        if($params['role'] == 2)
            return 124;
        return 0;
    }
    
    public function serviceShareType() {
        return 89;
    }
    
    public function serviceTradeType() {
        return 99;
    }
    
    public function getCommunityShareStoCash() {
        return "103,109,185";
    }
        
    public function getCommunityShareStoBonus() {
        return "115,104,110,186";
    }
    
    public function getCommunityShareStoBull() {
        return "116,105,111,187";
    }
}