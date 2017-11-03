<?php
namespace app\sale\controller\User;

use app\sale\ActionController;
use app\lib\Model;

class FlowController extends ActionController {
    
    public function __construct() {
        parent::__construct();
    }

    const pageNum = 20;
    
    private $flowCusType = array("1","2","3","4");
    
    /**
    * @user 用户消费钱包流水列表页面
    * @param $type 1 现金 2 商家 3 消费 4 积分
    * @author jeeluo
    * @date 2017年10月11日下午4:42:56
    */
    public function flowCusListAction() {
        $title = "交易明细";
        $type = $this->params['type'];
        
        if($type == "1") {
            $title = '钱包余额';
        } else if($type == "2") {
            $title = "我的营业额";
        } else if($type == "3") {
            $title = "我的消费券";
        } else {
            $title = "我的积分";
        }
        
//         $begintime = $this->params['begintime'];
//         $endtime = $this->params['endtime'];

        $begintime = date('Y-m-d', strtotime("-2 day"));
        $endtime = date('Y-m-d', time());
        
        $viewData = [
            'title' => $title,
            'customerid' => $this->userid,
            'type' => $type,
            'begintime' => $begintime,
            'endtime' => $endtime
        ];
        
        return $this->view($viewData);
    }
    
    
    /**
    * @user 用户消费钱包流水列表数据
    * @param $customerid 用户id值
    * @author jeeluo
    * @date 2017年10月11日下午5:05:50
    */
    public function flowCusDataAction() {
        $customerid = $this->params['customerid'];
        $type = $this->params['type'];
        
        if(!in_array($type,$this->flowCusType)) {
            return $this->json("10004");
        }

        $where["customerid"] = $customerid;
        $where['type'] = $type;

        $begintime = $this->params['begintime'];
        $endtime = $this->params['endtime'];
        if(!empty($begintime) || !empty($endtime)) {
            if(strtotime($begintime) <= strtotime($endtime)) {
                $where['flowtime'] = [[">=",date("Y-m-d 00:00:00", strtotime($begintime))],["<",date("Y-m-d 23:59:59", strtotime($endtime))]];
            }
        }
        
        $result = Model::new("User.UserFlow")->getCusFlowData($where);
        
        if($result["code"] != "200") {
            return $this->json($result["code"]);
        }

        $allCountNum = $result['data']['total'];
        $result['data']['maxPage'] = ceil($allCountNum/self::pageNum);
        
        return $this->json($result["code"], $result['data']);
    }
    
    public function flowreclistAction() {
        $title = "现金余额";
        
        $viewData = [
            "title" => $title,
            "customerid" => $this->userid
        ];
        
        return $this->view($viewData);
    }
    
    public function getflowreclistdataAction() {
        $customerid = $this->params['customerid'];
        $param['customerid'] = $customerid;
        $param['type'] = 5;
        
        $begintime = date('Y-m-d 00:00:00', strtotime("-2 day"));
        $endtime = date('Y-m-d 23:59:59', time());
        $param['flowtime'] = [[">=",$begintime],["<",$endtime]];
        
        $result = Model::new("User.UserFlow")->getCusFlowData($param);
        
        if($result['code'] != "200") {
            return $this->json($result["code"]);
        }
        
        $result['data']['maxPage'] = ceil($result['data']['total']/self::pageNum);
        
        return $this->json($result["code"], $result["data"]);
    }
    
    public function flowbuslistAction() {
        $title = "营业额明细";
        
//         $begintime = date('Y-m-d', strtotime("-2 day"));
//         $endtime = date('Y-m-d', time());
        
        $viewData = [
            'title' => $title,
            'customerid' => $this->userid,
//             'type' => $type,
//             'begintime' => $begintime,
//             'endtime' => $endtime
        ];
        
        return $this->view($viewData);
    }
    
    /**
    * @user 营业额流水列表
    * @param 
    * @author jeeluo
    * @date 2017年10月24日下午6:18:35
    */
    public function getflowbuslistdataAction() {
        $customerid = $this->params['customerid'];
        
//         if(!in_array($type,$this->flowCusType)) {
//             return $this->json("10004");
//         }
        
        $param["customerid"] = $customerid;
//         $param['type'] = $type;
        $param['begintime'] = date('Y-m-d 00:00:00', strtotime("-2 day"));
        $param['endtime'] = date('Y-m-d 23:59:59', time());
        
        $result = Model::new("User.UserFlow")->getNoMonthBusList($param);
        
        if($result["code"] != "200") {
            return $this->json($result["code"]);
        }
        
        $result['data']['maxPage'] = ceil($result['data']['total']/self::pageNum);
        
        return $this->json($result["code"], $result['data']);
    }
}