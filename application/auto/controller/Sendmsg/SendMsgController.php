<?php
// +----------------------------------------------------------------------
// |  [ 自动发送消息 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年7月3日17:05:47}}
// +----------------------------------------------------------------------
namespace app\auto\controller\Sendmsg;
use app\auto\ActionController;
use app\lib\Model;
use think\Config;
use app\lib\Log;


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
        
        Model::new("Sys.SendMsg")->sendOneSystemMsg();
        
        echo "ok";
    }

    /**
     * [sendOneSystemMsg 单条逐个发送系统消息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-13T15:04:30+0800
     * @return   [type]                   [description]
     */
    public function sendTowSystemMsgAction(){
        
        Model::new("Sys.SendMsg")->sendTwoSystemMsg();
        
        echo "ok";
    }

    /**
     * [sendTestSystemMsgAction 群发系统消息测试]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-23T10:27:12+0800
     * @return   [type]                   [description]
     */
    public function sendTestSystemMsgAction(){
        Model::new("Sys.SendMsg")->sendTestSystemMsg();
        
        echo "ok";
    }

}