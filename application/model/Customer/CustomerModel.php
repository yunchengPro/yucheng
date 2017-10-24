<?php
// +----------------------------------------------------------------------
// |  [ 用户角色公共模型]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author gbfun<1952117823@qq.com>
// | @DateTime 2017年3月28日 21:17:18
// +----------------------------------------------------------------------
namespace app\model\Customer;

use \think\Request;

//use app\lib\Db;
use app\lib\Model;


//use app\lib\Img;

//获取配置
use \think\Config;
use app\model\Sys\CommonRoleModel;
use app\model\Sys\CommonModel;
use app\model\User\RoleRecoModel;
use app\model\User\RoleModel;
use app\model\User\FlowModel;
//Config::get('webview.webviewUrl');

class CustomerModel
{    
    protected $_allRoles   = array('bullCusRole', 'bullTalentRole', 'bullPeoRole', 'bullenRole', 'bullBusRole', 'bullStoRole');  
    protected $_vipRoles   = array('bullPeoRole', 'bullTalentRole', 'bullenRole', 'bullBusRole', 'bullStoRole');
    protected $_roleOrder  = array("bullPeoRole" => "NNHTNR", "bullenRole" => "NNHTNC", "bullTalentRole"=>"NNHTND");
    protected $_roleMoney  = array("bullPeoRole" => "bullPeoMoney", "bullenRole" => "bullenMoney", "bullTalentRole"=>"bullTalentMoney");
    protected $_commonRole = 'bullCusRole';
    
//     protected $_recoModelLookup = array(
//       'bullPeoRole' => 'RoleRecoOr', 
//       'bullenRole' => 'RoleRecoEn',
//     );
    
    protected $_role = '';                     //当前模型角色，每个子类都必须定义
    protected $_recoModelName = '';            //用户信息表，推荐时才用到此属性，牛人、牛创客用到
    protected $_relationModelName = '';        //推荐关系表，推荐时才用到此属性，牛人、牛创客用到
    protected $_upgradeDescendantCount = 0;    //自动升级所需下级数量，推荐时才用到此属性，【牛人】用到    
    
    protected $_roleLookup = array('1' => '牛粉', '2' => '牛人', '3' => '牛创客', '4' => '牛商', '5' => '牛掌柜', "6" => "孵化中心", "7" => "运营中心", "8"=>"牛达人");
    protected $enable_arr = array(1, -1);
    
    public function userSimpleList($where) {
        $userList = Model::ins("CusCustomer")->pageList($where, "*", "id desc");

        $count = 1;
        foreach($userList['list'] as $k => $v) {
            // 查询用户表
            $cusInfo = Model::ins("CusCustomerInfo")->getRow(array("id" => $v['id']), "realname");
            $userList['list'][$k]['realname'] = $cusInfo['realname'];

            $userList['list'][$k]['roleStr'] = '';
            // 查询用户角色表
            $roleList = Model::ins("CusRole")->getList(array("customerid" => $v['id']));

            if(!empty($roleList)) {
                foreach($roleList as $key => $value) {
                    $userList['list'][$k]['roleStr'] .= $this->_roleLookup[$value['role']].",";
                }

                $userList['list'][$k]['roleStr'] = substr($userList['list'][$k]['roleStr'], 0, -1);
            }
            $userList['list'][$k]['numId'] = $count;
            $count++;
        }

        return $userList;
    }

    public function getUserWhereList($where) {
        // $roleWhere = array();
        // if(!empty($where['role'])) {
        //     $roleWhere['role'] = $where['role'];
        // }

        // $cusInfoWhere = array();
        // if(!empty($where['realname'])) {
        //     $cusInfoWhere['realname'] = $where['realname'];
        // }

        // $cusWhere = array();
        // $cusWhere['mobile'] = !empty($where['mobile']) ? $where['mobile'] : '';
        // $cusWhere['addtime'] = !empty($where['addtime']) ? $where['addtime'] : '';

        if(!empty($where['role'])) {
            $where['id'] = ["in", "select id from cus_role where role = ".$where['role']];
        }
    }

    /**
     * [getSimpleList 获取用户简单列表，不带where条件]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月28日 21:17:18
     * @return [type]    [description]
     */
    public function getSimpleList($flag=1)
    {
        $role = $this->_role;
        //$role = in_array($role, $this->_allRoles) ? $role : $this->_commonRole;        
        
        //角色id,所有用户记录都存储对应角色id
        $roleId = Config::get('role_money.' . $role);
        
        if(in_array($role, $this->_vipRoles)){
            $where = array('role' => $roleId);
            $list  = Model::ins("CusRole")->pageList($where, '*', 'id desc', $flag);
            //var_dump($list); exit();
        }else{
            $where = array();
            $where['enable'] = 1;
            $list = Model::ins("CusCustomer")->pageList($where, '*', 'id desc', $flag);
            //var_dump($list); exit();
        }
        
        foreach($list['list'] as $key => $customerTemp){
            $customer_03 = array('role' => $roleId, 'role_enable' => 1);    //所有用户记录都存储对应角色id, 角色状态默认启用
            if(in_array($role, $this->_vipRoles)){
                $customerId  = $customerTemp['customerid'];
                $customer_01 = Model::ins("CusCustomer")->getById($customerId);
                
                $customer_03['role_enable'] = $customerTemp['enable'];
                $customer_03['createtime'] = $customerTemp['addtime'];
            }else{
                $customerId  = $customerTemp['id']; 
                $customer_01 = $customerTemp;
            }
            
            $customer_02 = Model::ins("CusCustomerInfo")->getById($customerId);
            if(empty($customer_02)){
                $customer_02 = array();
            }
            
            $customer   = array_merge($customer_01, $customer_02, $customer_03);
            $list['list'][$key] = $customer;
        } 
        //var_dump($list['list']); exit();
        
        return $list;        
    }
    
    /**
    * @user 获取用户黑名单列表
    * @param 
    * @author jeeluo
    * @date 2017年4月18日上午11:17:40
    */
    public function getBlackList($where) {
        $username = (!empty($where['username'])) ? '%'.$where['username'].'%' : '';
        
        $cusList = Model::ins("CusCustomer")->getList($where, "*", "id desc", 100);
        $cusList = $this->getRelatedList($cusList);
        
        $where = array();
        if(!empty($username)) {
            $where = array_merge($where, array(
                "username" => array("like", $username),
                "mobile" => array("like", $username, "or"),
            ));
        }
        
        $cusInfoList = Model::ins("CusCustomerInfo")->getList_02($where, '*', 'id desc', 100);
        $cusInfoList = $this->getRelatedList($cusInfoList);
        
        $list_ids = $this->getIntersecCustomerIds($cusList, $cusInfoList);
        
        $list = $this->pageList($list_ids);
        
        return $list;
    }
    
    /**
    * @user 合并数据信息
    * @param 
    * @author jeeluo
    * @date 2017年4月18日上午11:17:55
    */
    public function getIntersecCustomerIds($cusList, $cusInfoList) {
        $result = array();
        if(!empty($cusList)) {
            $count = 0;
            foreach ($cusList as $key => $cus) {
                if(!empty($cusInfoList[$key])) {
                    $result[$count] = array_merge($cus, $cusInfoList[$key]);
                    $count++;
                }
            }
        }
        return $result;
    }
    
    /**
     * [getWhereList 获取用户列表，带where条件]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月28日 21:17:18
     * @return [type]    [description]
     */
    public function getWhereList($where = null, $order = null, $flag=1)
    {       
//         var_dump($where); exit();
        
        $role     = $this->_role;        
        //$role     = in_array($role, $this->_allRoles) ? $role : $this->_commonRole;        
        $username = (!empty($where['username']))  ? '%' . $where['username'] . '%' : '';        
        
        //角色id,所有用户记录都存储对应角色id
        $roleId = Config::get('role_money.' . $role);
        //var_dump($roleId); exit();
        
        //用户表
        $where = array();
        if(!empty($username)){
            $where = $where = array_merge($where, array(
                'username' => array('like', $username),
                'mobile' => array('like', $username, 'or'),
            ));
        }
        $where['enable'] = 1;
//         var_dump($where); exit();
                 
        $list_01 = Model::ins("CusCustomer")->getList($where, '*', 'id desc', 100);
        $list_01 = $this->getRelatedList($list_01);        
//         var_dump($list_01); exit();
        
        //用户信息表
        $where = array();
        if(!empty($username)){
            $where = array_merge($where, array(
                'realname' => array('like', $username),
                'nickname' => array('like', $username, 'or'),
            ));
        }
        $list_02 = Model::ins("CusCustomerInfo")->getList_02($where, '*', 'id desc', 100);
        $list_02 = $this->getRelatedList($list_02);
        //var_dump($list_02); exit();
        
        //取id并集
        $list_ids = $this->getUniqueCustomerIds(array($list_01, $list_02));
        //$list_ids = array_unique(array_merge(array_keys($list_01), array_keys($list_02)));
        //var_dump($list_ids); exit();
        
        //若非牛粉角色，则获取对应角色的，用户列表
        if(in_array($role, $this->_vipRoles)){            
            $where = array(
                'role'       => $roleId,
                'customerid' => array('in', implode(',', $list_ids)),
                //'customerid' => array('in', $list_ids),
            );
            $list_03 = Model::ins("CusRole")->getInfoList($where, '*', 'id desc', 100);
            $list_03 = $this->getRelatedList($list_03, 'customerid');
            //var_dump($list_03); exit();
            
            //$list_ids = $this->getCustomerIds($list_03, 'customerid');
            $list_ids = array_keys($list_03);
            //var_dump($list_ids); exit();
        }
        
        $list = array();
        $lastlogintimes = array();
        foreach($list_ids as $key => $customerId){
            $customer    = array();
            $customer_01 = array();
            $customer_02 = array();
        
            if(!empty($list_01[$customerId])){
                $customer_01 = $list_01[$customerId];
            }else{
                $customer_01 = Model::ins("CusCustomer")->getById($customerId);
            }
        
            if(!empty($list_02[$customerId])){
                $customer_02 = $list_02[$customerId];
            }else{
                $customer_02 = Model::ins("CusCustomerInfo")->getById($customerId);
                if(empty($customer_02)){
                    $customer_02 = array();
                }
            }
            
            $customer_03 = array('role' => $roleId, 'role_enable' => 1);    //所有用户记录都存储对应角色id, 角色状态默认启用
            if(in_array($role, $this->_vipRoles)){
                if(!empty($list_03[$customerId])){
                    $customer_03['role_enable'] = $list_03[$customerId]['enable'];

                    $customer_03['createtime'] = $list_03[$customerId]['addtime'];
                }
            }

            // 用户角色
            $customer_04 = array();
            $cusRole = Model::ins("CusRole")->getRow(array("customerid" => $customerId));
            if(!empty($cusRole)) {
                foreach ($cusRole as $k => $v) {
                    $customer_04['roleStr'] .= $this->_roleLookup[$v['role']];
                }
                substr($customer_04['roleStr'], 0, -1);
            }
        
            $customer             = array_merge($customer_01, $customer_02, $customer_03, $customer_04);
            $list[$key]           = $customer;
            $lastlogintimes[$key] = $customer['lastlogintime'];
            //shuffle($list);    //不能这样，因为键名不对应，会出错
        }
        array_multisort($list_ids, SORT_NUMERIC, SORT_DESC, $list);
        //array_multisort($list_ids, SORT_NUMERIC, SORT_ASC, $list);
        //array_multisort($lastlogintimes, SORT_STRING, SORT_DESC, $list);
        //var_dump($list); exit();
        $list = $this->pageList($list, $flag);

        // var_dump($list); exit();
        
        return $list;
    }
    
    /**
     * [getUniqueCustomerIds 根据多个用户列表，获取唯一用户id列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月28日 21:17:18
     * @param array $customerLists 多个用户列表
     * @return [type]    [description]
     */
    public function getUniqueCustomerIds($customerLists, $isIdRelated = true, $key = 'id')
    {
        $customerIds = array();
               
        foreach($customerLists as $customerList){
            if($isIdRelated){
                $customerIdsTemp = array_keys($customerList);
            }else{
                $customerIdsTemp = $this->getCustomerIds($customerList, $key);
            }
            
            $customerIds = array_merge($customerIds, $customerIdsTemp);
            $customerIds = array_unique($customerIds);
        }
        
        return $customerIds;
    }
    
    /**
     * [getCustomerIds 获取用户id列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月28日 21:17:18
     * @param array $customerList 用户列表
     * @return [type]    [description]
     */
    public function getCustomerIds($customerList, $key = 'id')
    //protected function getCustomerIds($customerList, $key = 'id')
    {
        $customerIds = array();
        foreach($customerList as $customer){    
            $customerIds[] = $customer[$key];
        }
        
        return $customerIds;
    }
    
    /**
     * [getRelatedList 获取id为索引的，关联用户列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月28日 21:17:18
     * @param array $customerList 用户列表
     * @return [type]    [description]
     */
    public function getRelatedList($customerList, $key = 'id')
    {
        $list = array();
        foreach ($customerList as $customer){
            $id        = $customer[$key];
            $list[$id] = $customer;
        }
        
        return $list;
    }
    
    /**
     * [pageList 根据用户列表，获取]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月28日 21:17:18
     * @param array $list 用户列表
     * @return [type]    [description]
     */
    public function pageList($list, $flag=1)
    {
        $count = count($list);
        
        $page       = Request::instance()->param('page');
        $page       = !empty($page)&&is_numeric($page)?$page:1;
        $pagesize   = Request::instance()->param('pagesize');
        $pagesize   = !empty($pagesize)&&is_numeric($pagesize)?$pagesize:20;
        
        $list = array_slice($list, (($page-1)*$pagesize), $pagesize);
        if($flag==1){            
            return array(
                "total" => $count,
                "list"  => $list,
            );
        }else{
            return $list;
        }
    }
    
    /**
     * [_getRecoModel 获取当前角色的，推荐用户模型]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月28日 21:17:18
     * @return [type]    [description]
     */
    protected function _getRecoModel()
    {
        return Model::ins($this->_recoModelName);
    }
    
    /**
     * [_getRelationModel 获取当前角色的，推荐关系模型]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月28日 21:17:18
     * @return [type]    [description]
     */
    protected function _getRelationModel()
    {
        return Model::ins($this->_relationModelName);
    }
    
    /**
     * [_getWaitAuditList 获取待审核列表，牛人、牛创客用到]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月30日 下午2:07:16
     * @editor jeeluo 支付状态进行修改，修改代码 2017-04-05 21:38:35
     * @return [type]    [description]
     */
    protected function _getWaitAuditList($where)
    {
//         $where['pay_status'] = 1;
        $where['pay_status'] = array(array("=", 0), array("=", 2, "or"));
        $where['status'] = 1;
        $list = $this->_getRecoList($where);
        
        return $list;
    }
    
    /**
     * [_getWaitAuditList 获取未通过列表，牛人、牛创客用到]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月30日 下午2:07:16
     * @editor jeeluo 2017-04-06 09:22:42
     * @return [type]    [description]
     */
    protected function _getNoPassList($where)
    {
//         $where['pay_status'] = 1;
        $where['pay_status'] = 0;
        $where['status']     = 3;
        $list = $this->_getRecoList($where);
    
        return $list;
    }
    
    
    /**
     * [_getRecoList 获取推荐用户列表，牛人、牛创客用到]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月1日 下午4:13:30
     * @return [type]    [description]
     */
    protected function _getRecoList($where)
    {
//         //$recoModelName = $this->_recoModelLookup[$role];
//         $recoModelName = $this->_recoModelName;
//         //echo $recoModelName; exit();
//         $recoModel     = Model::ins($recoModelName);
        $recoModel = $this->_getRecoModel();
        
        $cusCustomerModel = Model::ins("CusCustomer");
        $cusRoleModel     = Model::ins("CusRole");
        
        $list = $recoModel->pageList($where, '*', 'id desc');
        //var_dump($list); exit();
        foreach($list['list'] as $key => $customer){
            $instroducer = $cusCustomerModel->getById($customer['instroducerid']);
            $list['list'][$key]['instroducer'] = $instroducer['mobile'];
        
            $customerRole = '';
            $customerTemp = $cusCustomerModel->getRow(['mobile' => $customer['mobile']]);
            //var_dump($customerTemp); //exit();
            if(!empty($customerTemp)){
                $customerRoleList = $cusRoleModel->getInfoList(['customerid' => $customerTemp['id']], '*', 'role asc');
                //var_dump($roleList); exit();
                if(!empty($customerRoleList)){
                    foreach($customerRoleList as $roleItem){
                        $customerRole .= $this->_roleLookup[$roleItem['role']] . ',';
                    }
                }
        
                $list['list'][$key]['role'] = rtrim($customerRole, ',');
            }
        }
        //var_dump($list); exit();
        
        return $list;
    }
    
    /**
     * [_pass 通过审核，牛人、牛创客用到]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月30日 下午2:20:02
     * @editor jeeluo 添加支付时间、修改支付状态 2017-04-05 21:01:38
     * @return [type]    [description]
     */
    public function _pass($roleReco)
    {
        //var_dump('BullPeoRoleModel pass'); exit();
    
//         $roleRecoModel = $this->_getRecoModel();
//         //var_dump($roleRecoModel); exit();
//         $roleReco = $roleRecoModel->getById($bullPeoId);
//         //var_dump($roleReco); exit();
    
        $roleRecoModel        = $this->_getRecoModel();
        $cusCustomerModel     = Model::ins("CusCustomer");
        $cusCustomerInfoModel = Model::ins("CusCustomerInfo");
        $cusRoleModel         = Model::ins("CusRole");
    
        $createtime = date('Y-m-d H:i:s');
        $customerId = $this->_checkRecoMobile($roleReco, $createtime);
        
        //exit();
    
        //若未支付，则插入牛人、牛创客角色，推荐关系，判断是否升级主推人
        $payStatus = intval($roleReco['pay_status']);
        
        //var_dump($payStatus);
        if($payStatus === 0){
            //是否已为牛人、牛创客
            $bullRoleId = Config::get('role_money.' . $this->_role);
            $where = array(
                'customerid' => $customerId,
                'role' => $bullRoleId
            );
            $bullRole = $cusRoleModel->getRow($where);
    
            if(empty($bullRole)){
                $cusRelation = $this->_addRecoRole($roleReco, $customerId, $createtime);

                //判断是否升级上级推荐人
//                 if(($this->_role === 'bullPeoRole') && ($this->_upgradeDescendantCount > 0) && 
//                     method_exists($this, '_upgradeToBullenRole'))
//                 {
//                 //if($this->_upgradeDescendantCount > 0){    
//                     $parentId  = $cusRelation['parentid'];
//                     $grandpaId = $cusRelation['grandpaid'];

//                     if($parentId >= 1){
//                         $parentCusRoleId = $this->_upgradeToBullenRole($parentId, $createtime);
        
//                         //判断是否升级上上级推荐人
//                         if($grandpaId >= 1){
//                             $grandpaCusRoleId = $this->_upgradeToBullenRole($grandpaId, $createtime);
//                         }
//                     }
//                 }
            } else {
                return ["code" => "400", "data" => "用户角色已存在, 请审核失败"];
            }
        }
    
        //更新审核状态
        $roleRecoUpdateData = array('pay_status' => 1, 'status' => 2, 'examinetime' => $createtime, "pay_time" => $createtime, "customerid"=>$customerId);
        $result = $roleRecoModel->modify($roleRecoUpdateData, ['id' => $roleReco['id']]);
        
        // 查询礼品订单
//         $roleOrder = Model::ins("RoleOrder")->getRow(array("role_orderno" => $roleReco['orderno']), "id,cust_name");
        
//         if(!empty($roleOrder['id'])) {
//             // 查看被推荐人信息
// //             $cusInfo = Model::ins("CusCustomerInfo")->getRow(array("id" => $customerId), "nickname");
//             // 修改订单状态
//             $roleOrder = Model::ins("RoleOrder")->modify(array("orderstatus" => 1, "cust_name" => $roleOrder['cust_name'], "customerid" => $customerId), array("id" => $roleOrder['id']));
//         }
        
        
        //var_dump($result); exit();
    
        //$result = $this->_pass();
        //var_dump($result); exit();
    
        return ["code" => "200", "data" => $result];
//         return $result;
    }
    
    /**
    * @user 申请成为牛人  牛创客数据处理操作
    * @param 
    * @author jeeluo
    * @date 2017年4月5日下午8:54:22
    */
    public function _apply($applyInfo) {
        $roleRecoModel        = $this->_getRecoModel();
        $cusCustomerModel     = Model::ins("CusCustomer");
        $cusCustomerInfoModel = Model::ins("CusCustomerInfo");
        $cusRoleModel         = Model::ins("CusRole");
        
        $createtime = date('Y-m-d H:i:s');
        
        $customerId = $this->_checkRecoMobile($applyInfo, $createtime);
        
        //exit();
        //若未支付，则插入牛人、牛创客角色，推荐关系，判断是否升级主推人
//         $payStatus = intval($applyInfo['pay_status']);
//         //var_dump($payStatus);
//         if($payStatus === 1){
            //是否已为牛人、牛创客
            $bullRoleId = Config::get('role_money.' . $this->_role);
            $where = array(
                'customerid' => $applyInfo['customerid'],
                'role' => $bullRoleId,
            );
            $bullRole = $cusRoleModel->getRow($where);
            
            if(empty($bullRole)){
                $cusRelation = $this->_addApplyRole($applyInfo, $customerId, $createtime);
        
                //判断是否升级上级推荐人
                // if(($this->_role === 'bullPeoRole') && ($this->_upgradeDescendantCount > 0) &&
                //     method_exists($this, '_upgradeToBullenRole'))
                // {
                //     //if($this->_upgradeDescendantCount > 0){
                //     $parentId  = $cusRelation['parentid'];
                //     $grandpaId = $cusRelation['grandpaid'];
                //     if($parentId >= 1){
                //         $parentCusRoleId = $this->_upgradeToBullenRole($parentId, $createtime);
        
                //         //判断是否升级上上级推荐人
                //         if($grandpaId >= 1){
                //             $grandpaCusRoleId = $this->_upgradeToBullenRole($grandpaId, $createtime);
                //         }
                //     }
                // }
            }
//         }

            // 查询礼品订单
            $roleOrder = Model::ins("RoleOrder")->getRow(array("role_orderno" => $applyInfo['orderno']), "id");
            
            if(!empty($roleOrder['id'])) {
                // 修改订单状态
                $roleOrder = Model::ins("RoleOrder")->modify(array("orderstatus" => 1), array("id" => $roleOrder['id']));
            }
        
        //更新审核状态
//         $roleRecoUpdateData = array('pay_status' => 3, 'status' => 2, 'examinetime' => $createtime, "pay_time" => $createtime);
//         $result = $roleRecoModel->modify($roleRecoUpdateData, ['id' => $applyInfo['id']]);
        return true;
    }
    
    /**
     * [_checkRecoMobile 检查是否已有对应手机的用户，牛人、牛创客用到]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月31日 下午5:31:28
     * @return [type]    [description]
     */
    protected function _checkRecoMobile($roleReco, $createtime)
    {
        $cusCustomerModel     = Model::ins("CusCustomer");
        $cusCustomerInfoModel = Model::ins("CusCustomerInfo");
        $cusRoleModel         = Model::ins("CusRole");
    
        $customerTemp = $cusCustomerModel->getRow(['mobile' => $roleReco['mobile']],"id");

        //var_dump($customerTemp); exit();
        if(empty($customerTemp)){
            //不存在对应手机，插入
            $customer = array(
                'mobile'     => $roleReco['mobile'],
                'username'   => $roleReco['mobile'],
                'createtime' => $createtime
            );
            $customerId = $cusCustomerModel->add($customer);
            //var_dump($customerId);
    
            //插入用户详细信息
            if(!empty($customerId)){
                $customerInfo = array(
                    'id' => $customerId,
                    'realname' => $roleReco['realname'],
                    "nickname" => $roleReco['realname'],
                    'area' => $roleReco['area'],
                    'address' => $roleReco['address'],
                    'area_code' => $roleReco['area_code'],
                );
                $customerInfoId = $cusCustomerInfoModel->add($customerInfo);
                //var_dump($customerInfoId);
            }
    
            //插入牛粉角色
            $bullCusRoleId = Config::get('role_money.' . $this->_commonRole);
            $cusRole = array(
                'customerid' => $customerId,
                'role' => $bullCusRoleId,
                'area' => $roleReco['area'],
                'address' => $roleReco['address'],
                'area_code' => $roleReco['area_code'],
                'addtime' => $createtime
            );
            $cusRoleModel->add($cusRole);
            // $cusRoleId = $cusRoleModel->add($cusRole);
            //var_dump($cusRoleId);
        }else{
            $customerId = $customerTemp['id'];
        }
    
        return $customerId;
    }
    
    /**
     * [_addRecoRole 添加推荐角色，牛人、牛创客用到]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月31日 下午5:31:28
     * @editor jeeluo 添加总表关联关系 2017-04-06 10:26:13
     * @return [type]    [description]
     */
    protected function _addRecoRole($roleReco, $customerId, $createtime)
    {
        $cusRoleModel     = Model::ins("CusRole");
        
        //var_dump($cusRelationModel); //exit();
    
        //若未为牛人、牛创客，则插入牛人、牛创客角色
        $bullRoleId   = Config::get('role_money.' . $this->_role);
        //$cusRole['role'] = $bullRoleId;
        $cusRole = array(
            'customerid' => $customerId,
            'role' => $bullRoleId,
            'area' => $roleReco['area'],
            'address' => $roleReco['address'],
            'area_code' => $roleReco['area_code'],
            'addtime' => $createtime
        );
        
        $cusRoleId = $cusRoleModel->add($cusRole);

        //var_dump($cusRoleId);
        //插入牛人、牛创客推荐关系，一般不需判断是否已有推荐关系
        $parentId  = intval($roleReco['instroducerid']);
//         $parentrole = $bullRoleId;
        $parentrole = !empty($roleReco['instroducerrole']) ? intval($roleReco['instroducerrole']) : $bullRoleId;
        $grandpaId = -1;
        $grandparole = -1;
        if($parentId >= 1){
            // $parentCusRelation = $cusRelationModel->getInfoRow(['customerid' => $parentId, "role" => $bullRoleId]);
//             $parentCusRelation = Model::ins("CusRelation")->getRow(array("customerid" => $parentId, "role" => $bullRoleId),"parentid,parentrole");
            $parentCusRelation = Model::ins("CusRelation")->getRow(array("customerid" => $parentId, "role" => $parentrole),"parentid,parentrole");
            
            if(!empty($parentCusRelation)){
                $grandpaId = intval($parentCusRelation['parentid']);
                $grandparole = $parentCusRelation['parentrole'];
                if($grandpaId < 1){
                    $grandpaId = -1;
                }
            } else {
                $parentId = -1;
                $parentrole = -1;
            }
        }else{
            $parentId  = -1;
            $parentrole = -1;
        }
        
        $cusRelation = array(
            'customerid' => $customerId,
            'parentid'   => $parentId,
            'grandpaid'  => $grandpaId,
            'addtime'    => $createtime
        );
        
        if($this->_relationModelName == "CusRelationOr" || $this->_relationModelName == "CusRelationEn") {
            $cusRelationModel = $this->_getRelationModel();
            $cusRelationId = $cusRelationModel->add($cusRelation);
            
            // 牛创客 赠送牛达人
            if($this->_relationModelName == "CusRelationEn") {
                // 判断是否已经有牛达人
                $ndRelation = Model::ins("CusRelation")->getRow(["customerid"=>$customerId,"role"=>8],"id");
                if(empty($ndRelation)) {
                    // 没有，查看推荐牛创客那个人是否有牛达人
                    $recoNdRelation = Model::ins("CusRelation")->getRow(["customerid"=>$parentId,"role"=>8],"parentid,parentrole");
                    
                    $insertData = array();
                    if(!empty($recoNdRelation)) {
                        // 假如有
                        $insertData = array("customerid"=>$customerId,"role"=>8,"parentid"=>$parentId,"parentrole"=>8,"grandpaid"=>$recoNdRelation['parentid'],"grandparole"=>$recoNdRelation['parentrole'],"addtime"=>getFormatNow());
                    } else {
                        $insertData = array("customerid"=>$customerId,"role"=>8,"parentid"=>-1,"parentrole"=>-1,"grandpaid"=>-1,"grandparole"=>-1,"addtime"=>getFormatNow());
                    }
                    Model::ins("CusRole")->insert(array("customerid"=>$customerId,"role"=>8,"addtime"=>getFormatNow()));
                    Model::ins("CusRelation")->insert($insertData);
                }
            }
        }
        
        $cusRelationInfo = Model::ins("CusRelation")->getRow(array("customerid" => $customerId, "role"=>1), "id,parentid");
        
        if(empty($cusRelationInfo['id'])) {
            // 查看牛粉记录
            $nfRelationInfo = Model::ins("CusRelationNf")->getRow(array("customerid" => $customerId), "id");
            // 添加牛粉的关联关系
            Model::ins("CusRelationNf")->insert($cusRelation);
            
            $parentCusFans = Model::ins("CusRelation")->getRow(array("customerid" => $parentId, "role" => 1, "parentrole" => 1), "parentid");
            // 添加牛粉在总表的关联关系
            $fansRelationData = array(
                "customerid" => $customerId,
                "role" => 1,
                "parentid" => $parentId,
                "parentrole" => 1,
                "grandpaid" => !empty($parentCusFans) ? $parentCusFans['parentid'] : -1,
                "grandparole" => !empty($parentCusFans) ? 1 : -1,
                "addtime" => $createtime
            );
            
            Model::ins("CusRelation")->insert($fansRelationData);
            
            Model::new("Amount.Role")->share_role(["userid"=>$parentId,"customerid"=>$customerId]);
        }
//         $nfRelationInfo = Model::ins("CusRelationNf")->getRow(array("customerid" => $customerId), "id");
//         if(empty($nfRelationInfo)) { 
//             // 添加牛粉的关联关系
//             Model::ins("CusRelationNf")->insert($cusRelation);
            
//             $parentCusFans = Model::ins("CusRelation")->getRow(array("customerid" => $parentId, "role" => 1, "parentrole" => 1), "parentid");
//             // 添加牛粉在总表的关联关系
//             $fansRelationData = array(
//                 "customerid" => $customerId,
//                 "role" => 1,
//                 "parentid" => $parentId,
//                 "parentrole" => 1,
//                 "grandpaid" => !empty($parentCusFans) ? $parentCusFans['parentid'] : -1,
//                 "grandparole" => !empty($parentCusFans) ? 1 : -1,
//                 "addtime" => $createtime
//             );
            
//             Model::ins("CusRelation")->insert($fansRelationData);
            
//         }
        
        // 添加在总表的关联关系表中
        $cusRelationData = array(
            "customerid" => $customerId,
            "role" => $bullRoleId,
            "parentid" => $parentId,
            "parentrole" => $parentId > -1 ? $parentrole : -1,
            "grandpaid" => $grandpaId,
            "grandparole" => $grandpaId > -1 ? $grandparole : -1,
            "addtime" => $createtime
        );
        Model::ins("CusRelation")->insert($cusRelationData);
        
        if($parentId > 0 && ($this->_relationModelName == "CusRelationOr" || $this->_relationModelName == "CusRelationTalent")) {
            // 查询推荐人用此角色 是否是赠送的角色(成为表和推荐表都无数据)
//             if(CommonRoleModel::getUserRoleGive(["customerid"=>$parentId,"role"=>$cusRelationData['parentrole']])) {
                // 升级角色
                Model::new("User.Role")->userLevelRole(["customerid"=>$parentId,"role"=>$cusRelationData['parentrole']]);
//             }
        }
        
        CommonRoleModel::roleRelationRole(array("userid" => $parentId, "roleid" => 1, "customerid" => $customerId));
        
        //var_dump($cusRelationId);
    
        return $cusRelation;
    }
    
    protected function _addApplyRole($applyInfo, $customerId, $createtime)
    {
        $cusRoleModel     = Model::ins("CusRole");
        //var_dump($cusRelationModel); //exit();
    
        //若未为牛人、牛创客，则插入牛人、牛创客角色
        $bullRoleId   = Config::get('role_money.' . $this->_role);
        //$cusRole['role'] = $bullRoleId;
        $cusRole = array(
            'customerid' => $customerId,
            'role' => $bullRoleId,
            'area' => $applyInfo['area'],
            'address' => $applyInfo['address'],
            'area_code' => $applyInfo['area_code'],
            'addtime' => $createtime
        );
        $cusRoleId = $cusRoleModel->add($cusRole);
        //var_dump($cusRoleId);

        //插入牛人、牛创客推荐关系，一般不需判断是否已有推荐关系
        $customerInfo = Model::ins("CusCustomer")->getRow(array("mobile" => $applyInfo['instrodcermobile']), "id");
        
        $instrodcerrole = !empty($applyInfo['instrodcerrole']) ? $applyInfo['instrodcerrole'] : -1;
        $parentId  = intval($customerInfo['id']);
        $parentRole = -1;
        $grandpaId = -1;
        $grandpaRole = -1;
        if($parentId >= 1){
            if($instrodcerrole == -1) {
                if($bullRoleId == 2) {
                    // 引荐角色级别优先级(2,8,3)
                    //  查询引荐人是否有牛人角色
                    $parentCusRelation = Model::ins("CusRelation")->getRow(array("customerid" => $parentId, "role" => $bullRoleId),"id,parentid,parentrole");
                
                    if(empty($parentCusRelation['id'])) {
                        // 查询引荐人是否有牛达人角色
                        $ndParentCusRelation = Model::ins("CusRelation")->getRow(["customerId"=>$parentId, "role"=> 8],"id,parentid,parentrole");
                        if(empty($ndParentCusRelation['id'])) {
                            // 查询引荐人是否有牛创客角色
                            $enParentCusRelation = Model::ins("CusRelation")->getRow(["customerId"=>$parentId, "role"=> 3],"id,parentid,parentrole");
                            if(!empty($enParentCusRelation['id'])) {
                                $parentRole = 3;
                                $grandpaId = $enParentCusRelation['parentid'];
                                $grandpaRole = $enParentCusRelation['parentrole'];
                            } else {
                                $parentId = -1;
                            }
                        } else {
                            $parentRole = 8;
                            $grandpaId = $ndParentCusRelation['parentid'];
                            $grandpaRole = $ndParentCusRelation['parentrole'];
                        }
                    } else {
                        $parentRole = $bullRoleId;
                        $grandpaId = $parentCusRelation['parentid'];
                        $grandpaRole = $parentCusRelation['parentrole'];
                    }
                    if(!$parentRole > -1) {
                        $parentId = -1;
                        $grandpaId = -1;
                        $grandpaRole = -1;
                    }
                } else {
                    $parentCusRelation = Model::ins("CusRelation")->getRow(array("customerid" => $parentId, "role" => $bullRoleId));
                    if(!empty($parentCusRelation)) {
                        $parentRole = $bullRoleId;
                        $grandpaId = intval($parentCusRelation['parentid']);
                        $grandpaRole = intval($parentCusRelation['parentrole']);
                        if($grandpaId < 1) {
                            $grandpaId = -1;
                            $grandpaRole = -1;
                        }
                    } else {
                        $parentId = -1;
                    }
                }
                // 当为-1时，修改原先申请记录表数据
                if($parentId > -1 && $parentRole > -1) {
                    Model::ins("RoleApplyLog")->update(["instrodcerrole"=>$parentRole],["id"=>$applyInfo['id']]);
                }
            } else {
                $parentCusRelation = Model::ins("CusRelation")->getRow(array("customerid" => $parentId, "role" => $instrodcerrole));
                if(!empty($parentCusRelation)) {
                    $parentRole = $instrodcerrole;
                    $grandpaId = intval($parentCusRelation['parentid']);
                    $grandpaRole = intval($parentCusRelation['parentrole']);
                    if($grandpaId < 1) {
                        $grandpaId = -1;
                        $grandpaRole = -1;
                    }
                } else {
                    $parentId = -1;
                }
            }
            
            // $parentCusRelation = Model::ins("CusRelation")->getRow(array("customerid" => $parentId, "role" => $bullRoleId));
            // if(!empty($parentCusRelation)){
            //     $grandpaId = intval($parentCusRelation['parentid']);
            //     if($grandpaId < 1){
            //         $grandpaId = -1;
            //     }
            // } else {
            //     $parentId = -1;
            // }
        }else{
            $parentId  = -1;
        }
    
        $cusRelation = array(
            'customerid' => $customerId,
            'parentid'   => $parentId,
            'grandpaid'  => $grandpaId,
            'addtime'    => $createtime
        );
        if($this->_relationModelName == "CusRelationOr" || $this->_relationModelName == "CusRelationEn") {
            $cusRelationModel = $this->_getRelationModel();
            $cusRelationId = $cusRelationModel->add($cusRelation);
            
            if($this->_relationModelName == "CusRelationEn") {
                // 判断是否已经有牛达人
                $ndRelation = Model::ins("CusRelation")->getRow(["customerid"=>$customerId,"role"=>8],"id");
                
                if(empty($ndRelation['id'])) {
                    
                    // 查询上级数据
                    $parentNdCusRelation = Model::ins("CusRelation")->getRow(["customerid"=>$parentId,"role"=>8],"id,parentid,parentrole");
                    $ndparentid = $ndparentrole = $ndgranpaid = $ndgrandparole = -1;
                    if(!empty($parentNdCusRelation['id'])) {
                        $ndparentid = $parentId;
                        $ndparentrole = 8;
                        $ndgranpaid = $parentNdCusRelation['parentid'];
                        $ndgrandparole = $parentNdCusRelation['parentrole'];
                    }
                    Model::ins("CusRole")->insert(array("customerid"=>$customerId,"role"=>8,"addtime"=>getFormatNow()));
                    Model::ins("CusRelation")->insert(array("customerid"=>$customerId,"role"=>8,"parentid"=>$ndparentid,"parentrole"=>$ndparentrole,"grandpaid"=>$ndgranpaid,"grandparole"=>$ndgrandparole,"addtime"=>getFormatNow()));
                }
            }
        }
        
        $nfRelationInfo = Model::ins("CusRelationNf")->getRow(array("customerid" => $customerId), "id");
        
        if(empty($nfRelationInfo)) {
        // 添加牛粉的关联关系
            Model::ins("CusRelationNf")->insert($cusRelation);
        
            $parentCusFans = Model::ins("CusRelation")->getRow(array("customerid" => $parentId, "role" => 1, "parentrole" => 1), "parentid");
            // 添加牛粉在总表的关联关系
            $fansRelationData = array(
                "customerid" => $customerId,
                "role" => 1,
                "parentid" => $parentId,
                "parentrole" => 1,
                "grandpaid" => !empty($parentCusFans) ? $parentCusFans['parentid'] : -1,
                "grandparole" => !empty($parentCusFans) ? 1 : -1,
                "addtime" => $createtime
            );
            
            Model::ins("CusRelation")->insert($fansRelationData);
        }
    
        // 添加在总表的关联关系表中
        $cusRelationData = array(
            "customerid" => $customerId,
            "role" => $bullRoleId,
            "parentid" => $parentId,
            "parentrole" => $parentRole,
            // "parentrole" => $parentId != -1 ? $bullRoleId : -1,
            "grandpaid" => $grandpaId,
            "grandparole" => $grandpaRole,
            // "grandparole" => $grandpaId != -1 ? $bullRoleId : -1,
            "addtime" => $createtime
        );
        Model::ins("CusRelation")->insert($cusRelationData);
        
        if($parentId > 0 && ($this->_relationModelName == "CusRelationOr" || $this->_relationModelName == "CusRelationTalent")) {
            // 升级角色
            Model::new("User.Role")->userLevelRole(["customerid"=>$parentId,"role"=>$cusRelationData['parentrole']]);
        }
        
        CommonRoleModel::roleRelationRole(array("userid" => $parentId, "roleid" => 1, "customerid" => $customerId));
    
        //var_dump($cusRelationId);
    
        return $cusRelation;
    }
    
    /**
     * [_noPass 不通过审核，牛人、牛创客用到]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月1日 下午1:57:35
     * @return [type]    [description]
     */
    public function _noPass($roleReco)
    {    
        $roleRecoModel = $this->_getRecoModel();
    
        $createtime = date('Y-m-d H:i:s');
    
        //更新审核状态
        $roleRecoUpdateData = array(
            'remark' => $roleReco['remark'], 
            'status' => 3, 
            'examinetime' => $createtime           
        );
        $result = $roleRecoModel->modify($roleRecoUpdateData, ['id' => $roleReco['id']]);
        //var_dump($result); exit();
    
        return $result;
    }

    /**
     * [getCustomerName 根据id获取用户]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-30T17:07:32+0800
     * @return   [type]                   [description]
     */
    public static function getCustomerName($customerid){
        $customerData = Model::ins('CusCustomer')->getRow(['id'=>$customerid],"mobile,username");
        return $customerData;
    }
    
    /**
     * @user 禁用操作
     * @param enable 状态值 id 用户id值
     * @author jeeluo
     * @date 2017年4月18日上午9:47:18
     */
    protected function _enable($params) {
        // 确保属性值修改范围
        if(!in_array($params['enable'], $this->enable_arr)) {
            return ["code" => "400", "data" => "选择正确操作"];
        }
        
        // 执行修改操作
        Model::ins("CusCustomer")->modify(array("enable" => $params['enable']), array("id" => $params['id']));
        if($params['enable'] == -1) {
            // 删除mtoken
            Model::ins("CusMtoken")->delete(array("id" => $params['id']));
        }
        return ["code" => "200"];
    }
    
    /**
    * @user 查看用户信息
    * @param 
    * @author jeeluo
    * @date 2017年4月18日下午1:53:22
    */
    protected function _getCusCustomerInfo($customerid) {
        
        // 用户基本信息
        $cus = Model::ins("CusCustomer")->getRow(array("id" => $customerid));
        if(empty($cus)) {
            return ["code" => "1000", "data" => "记录不存在"];
        }
        
        $cusInfo = Model::ins("CusCustomerInfo")->getRow(array("id" => $customerid));
        
        $cusInfo = array_merge($cusInfo, $cus);
        
        // 查看用户信息的当前角色
        $role = Config::get("role_money.".$this->_role);
        
        // 查询角色引荐信息
        $cusRelation = Model::ins("CusRelation")->getRow(array("customerid" => $customerid, "role" => $role), "parentid");
        if($cusRelation['parentid'] > -1) {
            // 查看引荐人手机号码
            $parentCus = Model::ins("CusCustomer")->getRow(array("id" => $cusRelation['parentid']));
            
            $cusInfo['instroducerMobile'] = $parentCus['mobile'];
        } else {
            $cusInfo['instroducerMobile'] = '公司';
        }
        
        // 获取用户已有角色
        $cusRoleList = Model::ins("CusRole")->getList(array("customerid" => $customerid), "role, enable", "role asc");
        
        $roleStr = '';
        foreach ($cusRoleList as $role) {
            $roleStr .= $this->_roleLookup[$role['role']];
            if($role['role'] == 5) {
                if($role['enable'] == -1) {
                    $roleStr .= "(未上架)";
                }
            }
            $roleStr .= ",";
        }
        $roleStr = substr($roleStr, 0, -1);
        
        $cusInfo['roleStr'] = $roleStr;
        
        return ["code" => "200", "data" => $cusInfo];
    }
    
    /**
    * @user 获取用户关系
    * @param 
    * @author jeeluo
    * @date 2017年4月18日下午2:07:17
    */
    protected function _getRoleRelation($customerid) {
        // 用户分润关系表
        $roleRelation = Model::ins("RoleRelation")->getRow(array("id" => $customerid));
        
        if(empty($roleRelation)) {
            return ["code" => "1000", "data" => "老数据,数据异常"];
        }
        
        $result = array();
        
        foreach ($roleRelation as $key => $role) {
            if($role != 0 && $role > -1) {
                $cus = Model::ins("CusCustomer")->getRow(array("id" => $role), "mobile");
                $result[$key] = $cus['mobile'];
            } else {
                $result[$key] = '无';
            }
            // 有几个字段是角色值字段 非用户值字段
            if($key == 'business_prole' || $key == 'business_pprole' || $key == 'business_role') {
                $result[$key] = !empty($this->_roleLookup[$roleRelation[$key]]) ? $this->_roleLookup[$roleRelation[$key]] : '公司';
            }
        }
        
        return ["code" => "200", "data" => $result];
    }
    
    protected function _addCusRole($params) {
        
        // 查看用户是否存在
        $userCus = Model::ins("CusCustomer")->getRow(array("mobile" => $params['mobile']), "id");
        
        if(!empty($userCus)) {
            //判断用户角色是否存在
            $cusRoleWhere = array(
                "customerid" => $userCus['id'],
                "role" => Config::get("role_money.".$this->_role),
            );
            $userRole = Model::ins("CusRole")->getRow($cusRoleWhere);
            
            if(!empty($userRole)) {
                // 用户角色已存在
                return ["code" => "400", "data" => "用户角色已存在"];
            }
        }

        $paramsData['instroducerid'] = -1;
        $paramsData['instroducerrole'] = -1;
        
        // 获取归属
        if(!empty($params['instroducerMobile'])) {
            // 查询用户表
            $cus = Model::ins("CusCustomer")->getRow(array("mobile" => $params['instroducerMobile']), "id");
            
            if(!empty($cus)) {
                // 查询该用户是否有对应的角色
                $role = Config::get("role_money.".$this->_role);
                
                $cusRole = Model::ins("CusRole")->getRow(array("customerid" => $cus['id'], "role" => $role), "id");
                
                if(!empty($cusRole)) {
                    $paramsData['instroducerid'] = $cus['id'];
                    $paramsData['instroducerrole'] = $role;
                }
            }
        }
        
        // 获取地区
//         $province = Model::ins("SysArea")->getRow(array("id" => $params['area_province']), "areaname");
//         $city = Model::ins("SysArea")->getRow(array("id" => $params['area_city']), "areaname");
//         $county = Model::ins("SysArea")->getRow(array("id" => $params['area_county']), "areaname");
//         $paramsData['area'] = $province['areaname'].$city['areaname'].$county['areaname'];
        $area = CommonModel::getSysArea($params['area_county']);
        
        if($area['code'] != "200") {
            $paramsData['area'] = '';
        } else {
            $paramsData['area'] = $area['data'];
        }
        
        $createtime = getFormatNow();
        
//         if($params['role_status'] == 1) {
        
            // 写入推荐表数据
//             $roleRecoModel = $this->_getRecoModel();
//             $recoData = array(
//                 "realname" => $params['realname'],
//                 "mobile" => $params['mobile'],
//                 "area" => $paramsData['area'],
//                 "address" => $params['address'],
//                 "area_code" => !empty($params['area_county']) ? $params['area_county'] : $params['area_city'],
//                 "instroducerid" => $paramsData['instroducerid'],
//                 "pay_status" => 2,
//                 "status" => 2,
//                 "examinetime" => $createtime,
//                 "addtime" => $createtime,
//                 "orderno" => CommonModel::getRoleOrderNo($this->_roleOrder[$this->_role]),
//                 "amount" => Config::get('role_money.'.$this->_roleMoney[$this->_role]),
//                 "pay_time" => $createtime,
//                 "pay_amount" => EnPrice($params['amount']),
//             );
            
//             $recoId = $roleRecoModel->insert($recoData);
            
//             $recoInfo = $roleRecoModel->getRow(array("id" => $recoId));
//         } else {
            $recoInfo = array(
                "realname" => $params['realname'],
                "mobile" => $params['mobile'],
                "area" => $paramsData['area'],
                "address" => $params['address'],
                "area_code" => !empty($params['area_county']) ? $params['area_county'] : $params['area_city'],
                "instroducerid" => $paramsData['instroducerid'],
                "instroducerrole" => $paramsData['instroducerrole'],
                "pay_status" => 2,
                "status" => 2,
                "examinetime" => $createtime,
                "addtime" => $createtime,
                "orderno" => CommonModel::getRoleOrderNo($this->_roleOrder[$this->_role]),
                "amount" => Config::get('role_money.'.$this->_roleMoney[$this->_role]),
                "pay_time" => $createtime,
                "pay_amount" => EnPrice($params['amount']),
            );
//         }
        
        // 写入用户表信息
        $customerId = $this->_checkRecoMobile($recoInfo, getFormatNow());
        
//         if($params['role_status'] == 1) {
//             // 写入推荐表的customerid值
//             $roleRecoModel->modify(["customerid"=>$customerId],["id"=>$recoId]);
//         }
        
        // 是否已经是牛人、牛创客
        $cusRoleWhere = array(
            "customerid" => $customerId,
            "role" => Config::get("role_money.".$this->_role),
        );
        $cusRole = Model::ins("CusRole")->getRow($cusRoleWhere);
        
        if(empty($cusRole)) {
            $cusRelation = $this->_addRecoRole($recoInfo, $customerId, getFormatNow());
            
            if(($this->_role === "bullPeoRole") && ($this->_upgradeDescendantCount > 0) && method_exists($this, "_upgradeToBullenRole"))
            {
                $parentId = $cusRelation['parentid'];
                $grandpaId = $cusRelation['grandpaid'];
                if($parentId >= 1) {
                    $parentCusRoleId = $this->_upgradeToBullenRole($parentId, getFormatNow());
                    
                    // 判读是否升级上上级推荐人
                    if($grandpaId >= 1) {
                        $grandpaCusRoleId = $this->_upgradeToBullenRole($grandpaId, getFormatNow());
                    }
                }
            }
        }
        
        // 操作成功，返回推荐表信息
        return ["code" => "200", "data" => $recoInfo];
    }
    
    public function _flowRecoFans($params) {
        $roleOBJ = new RoleModel();
        if(!$roleOBJ->roleRange(array("role" => $params['recoRoleType']))) {
            return ["code" => "20102", "data" => "角色范围不正确"];
        }
//         $roleRecoOBJ = new RoleRecoModel();
//         $recolist = $roleRecoOBJ->getRoleRecoType($params);
//         if(empty($recolist)) {
//             return ["code" => "1001", "data" => "无操作权限"];
//         }
        
//         if(!in_array($params['recoRoleType'], $recolist)) {
//             return ["code" => "1001", "data" => "无操作权限"];
//         }
        
        $params['status'] = 2;

        $result = Model::new("User.Flow")->flowRecoCus($params);
        
        return $result;
    }
}