<?php
// +----------------------------------------------------------------------
// | 牛牛汇 [ 申请管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年10月10日16:25:22}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Customer;
use app\superadmin\ActionController;
use app\lib\Model;
use app\lib\Log;

class ApplyController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [applymanagerListAction 申请成为经理]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-10T16:26:25+0800
     * @return   [type]                   [description]
     */
    public function applymanagerListAction(){
    	
        $where = $this->searchWhere([
                "name"=>"like",
                "mobile"=>"=",
                "addtime" => "times",
            ],$where);

        $list = Model::ins("RoleApplyManager")->pageList($where, "*", "addtime desc, id desc");
      
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => '申请成为经理',
        );
        
        return $this->view($viewData);
    }   

    /**
     * [applybusListAction 申请成为商家]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-10T16:29:48+0800
     * @return   [type]                   [description]
     */
    public function applybusListAction(){
        
        $where = $this->searchWhere([
                "name"=>"like",
                "mobile"=>"=",
                "addtime" => "times",
            ],$where);
        
        $list = Model::ins("RoleApplyBus")->pageList($where, "*", "addtime desc, id desc");
       
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => '申请成为商家',
        );
        
        return $this->view($viewData);
       
    }

    /**
     * [applypassAction 通过审核]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-10T17:37:10+0800
     * @return   [type]                   [description]
     */
    public function applybuspassAction(){
        $id = $this->getParam('id');
        $status = $this->getParam('status');
        $apply_bus = Model::ins('RoleApplyBus')->getRow(['id'=>$id]);
        $type = ['1'=>'业绩扣除','2'=>'5折现金支付'];
        $apply_bus['join_type'] = $type[$apply_bus['join_type']];
        $viewData = array(
            "action" =>'/Customer/Apply/doapplybuspass',
            "title" => '审核商家申请',
            "apply_bus"=>$apply_bus,
            "status"=>$status,
            "id" => $id
        );
        return $this->view($viewData);
    }

    /**
     * [doapplybuspassAction 通过审核]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-10T17:54:00+0800
     * @return   [type]                   [description]
     */
    public function doapplybuspassAction(){
        $id = $this->getParam('id');
        $status = $this->getParam('status');
        if(empty($id))
            $this->showError('请选择需要申请的申请！');
        $remark = $this->getParam('remark');
        $apply_bus = Model::ins('RoleApplyBus')->getRow(['id'=>$id]);
        
        $is_pay = $this->getParam('is_pay');
      
        if($is_pay == 1){
            $remark = $remark.'，已支付佣金';
        }else{
            $remark = $remark.'，未支付佣金';
        }

        if(empty($apply_bus))
            $this->showError('申请信息不存在！');

         if(!in_array($status, [1,2,3]))
            $this->showError('无效的审核状态');

        if($apply_bus['status'] == 2)
            $this->showError('该申请记录已经通过审核！');

        if($apply_bus['status'] == 3)
            $this->showError('该申请记录已被拒绝！');
        
        // 事务
        $AmoAmount       = Model::ins("AmoAmount");

        $AmoAmount->startTrans();

        try{

            $update = [
                'status'=>$status,
                'remark'=>$remark,
                'examinetime' => date('Y-m-d H:i:s')
            ];

            $ret = Model::ins('RoleApplyBus')->update($update,['id'=>$id]);
            if($ret > 0){

                if($status == 2){

                    $param = [
//                         'mobile' => $apply_bus['mobile'],
                        'customerid' => $apply_bus['customerid'],
                        'role'   => 2,//$apply_bus['role'] ,
                        //'businessid' => $apply_bus['customerid']
                    ];
                   
                   
                    $resut = Model::new('User.User')->updateRelationRole($param);

                    if($resut['code'] == "200"){
                       
                        /*if($apply_bus['join_type'] == 1){
                            $area_param = [
                                'customerid' => $resut['data']['customerid'],
                                'role' => 2
                            ];
                           
                            Model::new("Amount.Arrears")->arrears($area_param);
                        }*/
                        if($is_pay == 1){
                            // 计算佣金
                            Model::new("Amount.Role")->pay_bus([
                                "customerid"=>$apply_bus['customerid'],
                                "orderno"=>$apply_bus['orderno'],
                            ]);
                        }
                        // 提交事务
                        $AmoAmount->commit(); 
                        
                        $this->showSuccess('操作成功！');
                    }else{

                        $AmoAmount->rollback();

                        $this->showError('推荐错误！');
                    }
                }else{
                    $AmoAmount->commit();
                    $this->showSuccess('操作成功！');
                
                }
               
            }else{
                $AmoAmount->rollback();

                $this->showError('审核错误，请重新提交！');
            }

            

        } catch (\Exception $e) {
            $AmoAmount->rollback();
            Log::add($e,__METHOD__);

        }

        $this->showError('审核错误，请重新提交！');
    }



    /**
     * [applypassAction 通过审核]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-10T17:37:10+0800
     * @return   [type]                   [description]
     */
    public function applymanagerpassAction(){
        $id = $this->getParam('id');
        $status = $this->getParam('status');
        $apply_manager = Model::ins('RoleApplyManager')->getRow(['id'=>$id]);
        $type = ['1'=>'业绩扣除','2'=>'5折现金支付'];
        $apply_manager['join_type'] = $type[$apply_manager['join_type']];
        $viewData = array(
            "action" =>'/Customer/Apply/doapplymanagerpass',
            "title" => '审核商家申请',
            "apply_manager"=>$apply_manager,
            "status"=>$status,
            "id" => $id
        );
        return $this->view($viewData);
    }

    /**
     * [doapplybuspassAction 通过审核]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-10T17:54:00+0800
     * @return   [type]                   [description]
     */
    public function doapplymanagerpassAction(){
        $id = $this->getParam('id');
        $status = $this->getParam('status');
        if(empty($id))
            $this->showError('请选择需要申请的申请！');
        $remark = $this->getParam('remark');
        $apply_manager = Model::ins('RoleApplyManager')->getRow(['id'=>$id]);
        $is_pay = $this->getParam('is_pay');
      
        if($is_pay == 1){
            $remark = $remark.'，已支付佣金';
        }else{
            $remark = $remark.'，未支付佣金';
        }
        if(empty($apply_manager))
            $this->showError('申请信息不存在！');

        if(!in_array($status, [1,2,3]))
            $this->showError('无效的审核状态');

        if($apply_manager['status'] == 2)
            $this->showError('该申请记录已经通过审核！');

        if($apply_manager['status'] == 3)
            $this->showError('该申请记录已被拒绝！');

        // 事务
        $AmoAmount       = Model::ins("AmoAmount");

        $AmoAmount->startTrans();

        try{

            $update = [
                'status'=>$status,
                'remark'=>$remark,
                'examinetime' => date('Y-m-d H:i:s')
            ];
            
            $ret = Model::ins('RoleApplyManager')->update($update,['id'=>$id]);
          
            if($ret > 0){

                if($status == 2){

                    $param = [
//                         'mobile' => $apply_manager['mobile'],
                        'customerid' => $apply_manager['customerid'],
                        'role'   => 3,//$apply_manager['role'] ,
                        //'businessid' => $apply_manager['customerid']
                    ];
                    
                    $resut = Model::new('User.User')->updateRelationRole($param);
                   
                    if($resut['code'] == "200"){

                        /*if($apply_manager['join_type'] == 1){
                            $area_param = [
                                'customerid' => $resut['data']['customerid'],
                                'role' => 3
                            ];
                          
                            Model::new("Amount.Arrears")->arrears($area_param);
                        }*/
                        if($is_pay == 1){
                            // 计算佣金
                            Model::new("Amount.Role")->pay_manager([
                                "customerid"=>$apply_manager['customerid'],
                                "orderno"=>$apply_manager['orderno'],
                            ]);
                        }

                        // 提交事务
                        $AmoAmount->commit(); 
                       
                        $this->showSuccess('操作成功！');
                    }else{
                        $AmoAmount->rollback();
                        $this->showError('推荐错误！');
                    }
                    
                }else{
                    $AmoAmount->commit();
                    $this->showSuccess('操作成功！');
                
                }

               
            }else{
                $AmoAmount->rollback();
                $this->showError('审核错误，请重新提交！');
            }

        } catch (\Exception $e) {
            $AmoAmount->rollback();
            Log::add($e,__METHOD__);

        }

        $this->showError('审核错误，请重新提交！');
    }


     /**
     * [applybusListAction 总监信息列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-10T16:29:48+0800
     * @return   [type]                   [description]
     */
    public function applyChiefListAction(){
        
        $where = $this->searchWhere([
                "name"=>"like",
                "mobile"=>"=",
                "addtime" => "times",
            ],$where);
        
        $list = Model::ins("RoleApplyChief")->pageList($where, "*", "addtime desc, id desc");
       
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => '总监信息列表',
        );
        
        return $this->view($viewData);
       
    }

    /**
     * [addApplyChiefListAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-11T17:27:25+0800
     */
    public function addApplyChiefAction(){
        $viewData = array(
            "action" =>'/Customer/Apply/doApplyChief',
            "title" => '添加总监'
        );
        return $this->view($viewData); 
    }

    /**
     * [doApplyChiefAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-11T17:40:18+0800
     * @return   [type]                   [description]
     */
    public function doApplyChiefAction(){
        $post = $this->params;
      
        $area = Model::ins('SysArea')->getRow(['id'=>$post['area_county']],'id,areaname,parentid');
        $parent = Model::ins('SysArea')->getRow(['id'=>$area['parentid']],'id,areaname,parentid');
        $grand = Model::ins('SysArea')->getRow(['id'=>$parent['parentid']],'id,areaname,parentid');
      
        $city = $grand['areaname'].$parent['areaname'] . $area['areaname'];
        $insert = [
            'name'   => $post['name'],
            'mobile' => $post['mobile'],
            'area_code' => $post['area_county'],
            'area' => $city,
            'address' => $post['address'],
            'customerid' => 0,
            'role' => 0,
            'join_type'=>1,
            'addtime' => date('Y-m-d H:i:s')
        ];
        $id = $post['id'];
        if(empty($id)){
            $ret = Model::ins('RoleApplyChief')->insert($insert);
        }else{
            unset($insert['addtime']);
            $ret = Model::ins('RoleApplyChief')->update($insert,['id'=>$id]);
        }
        if($ret > 0){
            
            $param = [
                    'mobile' =>  $post['mobile'],
                    'role'   => 4,
                    'recommendid'=>-1,
                    'recommendrole'=>-1
                ];
               
            $resut = Model::new('User.User')->addUser($param);

            if($resut['code'] == 200){
                 
                $this->showSuccess('操作成功！');
            }else{
                $this->showError('操作错误，请重新提交！');
            }

        }else{
            $this->showError('操作错误，请重新提交！');
        }
    }

     public function editApplyChiefAction(){
        $id = $this->getParam('id');
        $apply_bus = Model::ins('RoleApplyChief')->getRow(['id'=>$id]);
        // print_r( $apply_bus);
        $viewData = array(
            "action" =>'/Customer/Apply/doApplyChief',
            "title" => '添加总监',
            'apply_bus'=>$apply_bus
        );
        return $this->view($viewData); 
    }

}