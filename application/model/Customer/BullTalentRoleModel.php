<?php
namespace app\model\Customer;
use app\lib\Model;

class BullTalentRoleModel extends CustomerModel {
    protected $_role = 'bullTalentRole';
    protected $_recoModelName     = 'RoleRecoTalent';
    protected $_relationModelName = 'CusRelationTalent';
    
    protected $_upgradeDescendantCount  = 300;
    
    public function getWaitAuditList($where) {
        $list = $this->_getWaitAuditList($where);
        return $list;
    }
    
    protected function _upgradeToBullenRole($customerId, $createtime)
    {
        $cusCustomerInfoModel = Model::ins("CusCustomerInfo");
        $cusRoleModel         = Model::ins("CusRole");
        $cusRelationOrModel   = $this->_getRelationModel();
        //var_dump($cusRelationOrModel); //exit();
    
        //是否已为牛创客
        $bullenRoleId = Config::get('role_money.bullenRole');
        $where = array(
            'customerid' => $customerId,
            'role' => $bullenRoleId
        );
        $bullenRole = $cusRoleModel->getRow($where);
    
        $cusRoleId = 0;
        if(empty($bullenRole)){
            //若未为牛创客，则判断粉丝数
    
            // $descendantCount = $cusRelationOrModel->getDescendantCount($customerId);
            $descendantCount = Model::ins("CusRelation")->getDescendantCount($customerId);
    
            if($descendantCount >= $this->_upgradeDescendantCount){
                //if(1){
                $customerInfo = $cusCustomerInfoModel->getById($customerId);
                //var_dump($parentCustomerInfo);
    
                $cusRole = array(
                    'customerid' => $customerId,
                    'role' => $bullenRoleId,
                    'area' => $customerInfo['area'],
                    'address' => $customerInfo['address'],
                    'area_code' => $customerInfo['area_code'],
                    'addtime' => $createtime
                );
                $cusRoleId = $cusRoleModel->add($cusRole);
                //var_dump($cusRoleId);
    
                //插入牛创客推荐关系，一般不需判断是否已有推荐关系
                $cusRelationEnModel = Model::ins('CusRelationEn');
                $cusRelationEn = array(
                    'customerid' => $customerId,
                    'parentid'   => -1,
                    'grandpaid'  => -1,
                );
                $cusRelationEnId = $cusRelationEnModel->add($cusRelationEn);
                //var_dump($cusRelationEnId);
            }
        }else{
            $cusRoleId = $bullenRole['id'];
        }
    
        return $cusRoleId;
    }
    
    public function pass($roleReco)
    {
        //var_dump('BullPeoRoleModel pass'); exit();
    
        $result = $this->_pass($roleReco);
    
        // 生成流水号
        $flowid = Model::new("Amount.Flow")->getFlowId($roleReco['orderno']);
    
        $user = Model::ins("CusCustomer")->getIdByMobile($roleReco['mobile']);
        Model::new("Amount.Role")->pay_tnd([
            "userid" => $user['id'],
            "amount" => $roleReco['amount'],
            "orderno" => $roleReco['orderno'],
            "flowid" => $flowid,
        ]);
    
        return $result;
    }
    
    public function payPass($roleReco) {
        $result = $this->_pass($roleReco);
        return $result;
    }
    
    /**
     * @user 申请成为牛人操作
     * @param  $applyInfo 牛人信息
     * @author jeeluo
     * @date 2017年4月5日下午8:54:43
     */
    public function apply($applyInfo) {
        $result = $this->_apply($applyInfo);
    
        return $result;
    }
    
    public function noPass($roleReco)
    {
        //var_dump('BullPeoRoleModel noPass'); exit();
    
        $result = $this->_noPass($roleReco);
    
        return $result;
    }
    
    /**
    * @user 添加用户角色
    * @param 
    * @author jeeluo
    * @date 2017年7月4日上午11:44:54
    */
    public function addCusRole($params) {
        $result = $this->_addCusRole($params);
        return $result;
    }
    
    /**
     * @user 获取用户详情
     * @param
     * @author jeeluo
     * @date 2017年4月24日下午2:18:25
     */
    public function getCusCustomerInfo($customerid) {
        return $this->_getCusCustomerInfo($customerid);
    }
}