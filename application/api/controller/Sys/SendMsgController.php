<?php
// +----------------------------------------------------------------------
// |  [ 2016-02-28 发送系统消息 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ISir <673638498@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\Sys;
use app\api\ActionController;

use app\lib\Log;
use app\lib\Model;

class SendMsgController extends ActionController{

    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    /**
     * [sendOneSystemMsg 单条逐个发送系统消息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-13T15:04:30+0800
     * @return   [type]                   [description]
     */
    public function sendOneSystemMsgAction(){
        // exit();
        // var_dump(Model::new("Sys.SendMsg")->sendOneSystemMsg(['msg'=>$this->params['msg']]));
        // exit();
        // 用户拒绝退单提醒消息
        Model::new("Sys.Mq")->add([
            // "url"=>"Msg.SendMsg.orderRefuseOragree",
            "url"=>"Sys.SendMsg.sendOneSystemMsg",
            "param"=>[
                'msg'=>$this->params['msg']
            ],
        ]);
        Model::new("Sys.Mq")->submit();

       

      
        return $this->json(200);
    }
}
