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

class BullCityModel extends AgentModel {
    
    protected $_recoModelName = "RoleRecoCity";
    protected $_agentType = 1;
    
    public function updateExamStatus($params) {
        // 确保状态修改
        if($params['status'] != 2 && $params['status'] != 3) {
            return ["code" => "400", "data" => "选择正确操作"];
        }
        // 推荐信息
        $recoInfo = Model::ins("RoleRecoCity")->getRow(array("id" => $params['id']));
    
        if($params['status'] == 2) {
            // 审核成功
            $result = self::pass($recoInfo, 1);
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
        $status = Model::ins("RoleRecoCity")->modify($updateData, array("id" => $params['id']));
        if($status) {
            return ["code" => "200"];
        }
        return ["code" => "400", "data" => "操作异常，请联系管理员"];
    }
    
    public function examSend($params) {
        $recoId = $params['recoId'];
    
        if(!empty($recoId)) {
            $title = '';
            $content = '';
            $recoInfo = Model::ins("RoleRecoCity")->getRow(array("id" => $recoId),"cus_role_id,type,realname,company_name,status,remark");
            $recoParentInfo = CommonRoleModel::getCusRole(array("cus_role_id" => $recoInfo['cus_role_id']));
    
            $name = '';
            if($recoInfo['type'] == 1) {
                $name = $recoInfo['realname'];
            } else {
                $name = $recoInfo['company_name'];
            }
    
            if($recoInfo['status'] == 2) {
                $title = "分享成功";
                $content = "您分享".$name."为运营中心,已审核成功!";
            } else {
                $title = "分享失败";
                $content = "您分享".$name."为运营中心,未通过审核!原因:".$recoInfo['remark'];
            }
    
            Model::new("Msg.SendMsg")->SendSysMsg(["userid"=>$recoParentInfo['customerid'],"title"=>$title,"content"=>$content]);
        }
        return true;
    }
    
    /**
    * @user 添加代理信息
    * @param 
    * @author jeeluo
    * @date 2017年6月20日上午11:01:35
    */
    public function addAgent($params) {
        return $this->_addAgent($params);
    }
    
    /**
    * @user 修改代理信息
    * @param 
    * @author jeeluo
    * @date 2017年6月20日上午11:33:00
    */
    public function editAgent($params) {
        return $this->_editAgent($params);
    }
    
    /**
    * @user 修改推荐信息
    * @param 
    * @author jeeluo
    * @date 2017年6月21日上午10:31:58
    */
    public function editReco($params) {
        return $this->_editReco($params);
    }
}