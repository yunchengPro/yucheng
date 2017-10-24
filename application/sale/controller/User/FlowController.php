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
        $title = "消费交易明细";
        $type = $this->params['type'];
        $begintime = $this->params['begintime'];
        $endtime = $this->params['endtime'];
        
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
            if(strtotime($begintime) <= $endtime) {
                $where['flowtime'] = [[">=",date("Y-m-d 00:00:00", strtotime($begintime))],["<",date("Y-m-d", strtotime($endtime))]];
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
}