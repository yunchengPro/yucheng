<?php
// +----------------------------------------------------------------------
// |  [ 分润设置 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author jeeluo
// | @DateTime 2017-04-22
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Amount;

use app\superadmin\ActionController;
use app\lib\Model;

class ProfitSettingController extends ActionController
{

    public function __construct() {
        parent::__construct();
    }
    
    /**
     * 实体店列表
     * @Author   zhuangqm
     * @DateTime 2017-05-13T15:05:53+0800
     * @return   [type]                   [description]
     */
    public function stolistAction(){

        $where = array();
        $where = $this->searchWhere([
            "mobile" => "=",
            "businessname" => "like",
//             "addtime" => "times",
        ], $where);
        
        if(!empty($where['mobile'])) {
            $where['customerid'] = ["in", "select id from cus_customer where mobile like '%".$where['mobile']."%'"];
            unset($where['mobile']);
        }
        
        $where['ischeck'] = 1;
        $where['enable'] = 1;
        $where['isvip'] = -1;
        
        $stoList = Model::ins("StoBusiness")->pageList($where, "*", "addtime desc, id desc");
        $list['total'] = $stoList['total'];
        $list['list'] = array();
        foreach ($stoList['list'] as $key => $v) {
            $stoInfo = Model::ins("StoBusinessInfo")->getRow(array("id" => $v['id']));
            
            $list['list'][$key] = $stoInfo;
        }
     
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => "牛掌柜",
        );
    
        return $this->view($viewData);
    }

    /**
     * 设置分润对象
     * @Author   zhuangqm
     * @DateTime 2017-05-13T15:48:07+0800
     * @return   [type]                   [description]
     */
    public function stosettingAction(){
        $id = $this->params['id'];

        if(empty($id))
            $this->showError('参数有误');

        $settinglist = Model::ins("AmoProfitSetting")->getList(["objtype"=>2,"objid"=>$id],"*");

        $settingvalue = [];
        foreach($settinglist as $k=>$v){
            $settingvalue[$v['profit']] = $v['status'];
        }

        $profitsetting = Model::new("Amount.ProfitSetting")->getfield();

        foreach($profitsetting as $key=>$value){
            $profitsetting[$key] = [
                "name"=>$value,
                "value"=>!empty($settingvalue[$key])?$settingvalue[$key]:1,
            ];
        }

        return $this->view([
                "profitsetting"=>$profitsetting,
                "id"=>$id,
                "action"=>'/Amount/ProfitSetting/dostosetting',
            ]);
    }

    /**
     * 处理结果
     * @Author   zhuangqm
     * @DateTime 2017-05-13T17:00:40+0800
     * @return   [type]                   [description]
     */
    public function dostosettingAction(){
        $id = $this->params['id'];

        if(empty($id))
            $this->showError('参数有误');
        
        $profitsetting = Model::new("Amount.ProfitSetting")->getfield();

        $profitsettingData = [];
        foreach($profitsetting as $key=>$value){
            if(!empty($this->params[$key]) && $this->params[$key]=='-1'){
                $profitsettingData[] = [
                    "profit"=>$key,
                    "status"=>-1,
                    "objtype"=>2,
                    "objid"=>$id,
                ];
            }
        }

       
        Model::ins("AmoProfitSetting")->delete(["objtype"=>2,"objid"=>$id]);

        foreach($profitsettingData as $data){
            Model::ins("AmoProfitSetting")->insert($data);
        }

        $this->showSuccess('操作成功');
    }
}