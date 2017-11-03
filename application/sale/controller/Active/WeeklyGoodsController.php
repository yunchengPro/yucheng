<?php
// +----------------------------------------------------------------------
// |  [ 七夕活动 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author jeeluo
// | @DateTime 2017-09-22 10:40:44
// +----------------------------------------------------------------------
namespace app\mobile\controller\Active;

use app\mobile\ActionController;

class WeeklyGoodsController extends ActionController {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
    * @user 列表页面
    * @param 
    * @author jeeluo
    * @date 2017年9月22日上午11:17:53
    */
    public function listAction() {
        $goodsid = !empty($this->params['goodsid']) ? $this->params['goodsid'] : '';
        $viewData = [
            "title" => "每周好货",
            "goodsid"=>$goodsid
        ];
        return $this->view($viewData);
    }
    
    /**
    * @user 下期预售
    * @param 
    * @author jeeluo
    * @date 2017年9月25日上午9:51:13
    */
    public function nextWeekAction() {
        $viewData = [
            "title" => "下期预售",
        ];
        return $this->view($viewData);
    }
    
    /**
    * @user 获取列表数据
    * @param 
    * @author jeeluo
    * @date 2017年9月22日上午11:18:05
    */
    public function getListDataAction() {
        // 列表数据
        $type = !empty($this->params['type']) ? $this->params['type'] : 0;
        $goodsid = !empty($this->params['goodsid']) ? $this->params['goodsid'] : 0;
        $customerid = empty($this->params['customerid']) ? !empty($this->userid) ? $this->userid : 0 : 0;
        $page = !empty($this->params['page']) ? $this->params['page'] : 1;
        
        $json_result = $this->_api(([
            "actionname"=>"mall.index.goodsactivelist",
            "param"=>[
                "type"=>$type,
                "goodsid"=>$goodsid,
                "customerid"=>$customerid,
                "page"=>$page
            ],
        ]));
        
        $data = json_decode($json_result, true);
        
        foreach ($data['data']['list'] as $k => $v) {
            $data['data']['list'][$k]['strstarttime'] = strtotime($v['starttime']);
            $data['data']['list'][$k]['strendtime'] = strtotime($v['endtime']);
        }
        
        return json_encode($data);
    }
    
    /**
    * @user 修改提醒状态
    * @param 
    * @author jeeluo
    * @date 2017年9月23日下午6:29:33
    */
    public function updateRemindAction() {
        // 获取数据
        $buyid = $this->params['buyid'];
        $remindStatus = $this->params['remindStatus'];
        $customerid = $this->userid;
        
        return $this->_api(([
            "actionname"=>"mall.index.updateRemind",
            "param"=>[
                "buyId"=>$buyid,
                "remindStatus"=>$remindStatus,
                "customerid"=>$customerid
            ],
        ]));
    }
}