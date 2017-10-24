<?php
// +----------------------------------------------------------------------
// |  [ 牛人模型]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author jeeluo
// | @DateTime 2017-04-07
// +----------------------------------------------------------------------
namespace app\model\Customer;
use app\lib\Model;
use app\model\Sys\CommonRoleModel;
class BullCountyModel extends AgentModel {
    
    protected $_recoModelName = "RoleRecoCounty";
    protected $_agentType = 2;
    
    public function updateExamStatus($params) {
        // 确保状态修改
        if($params['status'] != 2 && $params['status'] != 3) {
            return ["code" => "400", "data" => "选择正确操作"];
        }
        // 推荐信息
        $recoInfo = Model::ins("RoleRecoCounty")->getRow(array("id" => $params['id']));
        
        if($params['status'] == 2) {
            // 审核成功
            $result = self::pass($recoInfo, 2);
        } else {
            $result = self::nopass($params);
        }
        return ["code" => "".$result['code']."", "data" => $result['data']];
    }
    
    /**
    * @user 审核成功
    * @param 
    * @author jeeluo
    * @date 2017年4月7日上午11:18:24
    */
    public function pass($recoInfo, $agent_type) {
        return $this->_pass($recoInfo, $agent_type);
    }
    
    /**
    * @user 审核失败
    * @param 
    * @author jeeluo
    * @date 2017年4月7日上午11:18:16
    */
    public function nopass($params) {
        $updateData['examinetime'] = getFormatNow();
        $updateData['remark'] = $params['remark'];
        $updateData['status'] = $params['status'];
        $status = Model::ins("RoleRecoCounty")->modify($updateData, array("id" => $params['id']));
        if($status) {
            return ["code" => "200"];
        }
        return ["code" => "400", "data" => "操作异常，请联系管理员"];
    }
    
    /**
    * @user 添加孵化中心
    * @param 
    * @author jeeluo
    * @date 2017年5月17日下午5:37:02
    */
    public function addAgent($params) {
        return $this->_addAgent($params);
    }
    
    /**
    * @user 修改孵化中心信息
    * @param 
    * @author jeeluo
    * @date 2017年6月19日下午2:52:42
    */
    public function editAgent($params) {
        return $this->_editAgent($params);
    }
    
    public function editReco($params) {
        return $this->_editReco($params);
    }
    
    public function examSend($params) {
        $recoId = $params['recoId'];
    
        if(!empty($recoId)) {
            $title = '';
            $content = '';
            $recoInfo = Model::ins("RoleRecoCounty")->getRow(array("id" => $recoId),"cus_role_id,type,realname,company_name,status,remark");
            $recoParentInfo = CommonRoleModel::getCusRole(array("cus_role_id" => $recoInfo['cus_role_id']));
            
            $name = '';
            if($recoInfo['type'] == 1) {
                $name = $recoInfo['realname'];
            } else {
                $name = $recoInfo['company_name'];
            }
    
            if($recoInfo['status'] == 2) {
                $title = "分享成功";
                $content = "您分享".$name."为孵化中心,已审核成功!";
            } else {
                $title = "分享失败";
                $content = "您分享".$name."为孵化中心,未通过审核!原因:".$recoInfo['remark'];
            }
    
            Model::new("Msg.SendMsg")->SendSysMsg(["userid"=>$recoParentInfo['customerid'],"title"=>$title,"content"=>$content]);
        }
        return true;
    }
}