<?php
namespace app\model\Sys;
use app\lib\Model;

class SysMsgModel {
    public function test() {
        Model::new("Msg.SendMsg")->SendSysMsg(array("userid"=>603,"title"=>"分享成功3","content"=>"测试内容3分享"));
    }
}
?>