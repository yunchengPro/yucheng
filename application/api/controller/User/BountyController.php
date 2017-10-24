<?php
// +----------------------------------------------------------------------
// |  [ 用户奖励金接口 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年7月17日17:04:48}}
// +----------------------------------------------------------------------

namespace app\api\controller\User;
use app\api\ActionController;
use app\lib\Model;
use app\model\User\BountyModel;

class BountyController extends ActionController {

	public function __construct() {
        parent::__construct();
    }

    /**
     * [hasbountyAction 是否有奖励金记录]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-17T17:05:56+0800
     * @return   [type]                   [description]
     */
    public function hasbountyAction(){
        $hasbounty = 0;
        $param['customerid'] = $this->userid;
        $hasbountyData = BountyModel::hasbounty($param);
        if($hasbountyData)
            $hasbounty = 1;
        return $this->json(200,['hasbounty'=>$hasbounty]);
    }

    /**
     * [bountylistAction 用户奖励金列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-17T17:06:33+0800
     * @return   [type]                   [description]
     */
    public function bountylistAction(){
        $param['customerid'] = $this->userid;
        $bountyList =  BountyModel::bountyList($param);
        return $this->json(200,$bountyList);
    }
}