<?php
namespace app\model\Order;

use \think\Config;

use app\lib\model;

class OrderCountModel
{

    /*
    判断记录是否存在
     */
    public function checkexits($userid){
        $row = Model::ins("OrdUserCount")->getRow(["id"=>$userid],"id");
        if(empty($row)){
            Model::ins("OrdUserCount")->insert(["id"=>$userid,"customerid"=>$userid]);
        }
    }
    
    // 字段增加1
    public function addCount($userid,$field){
        $this->checkexits($userid);
        $OrdUserCountOBJ = Model::ins("OrdUserCount");
        $result = $OrdUserCountOBJ->update($field."=".$field."+1",["id"=>$userid]);
        //更新redis
        if($result)
            Model::ins("OrdUserCount")->hincrbyRedis($userid,[$field=>1]);
    }

    // 字段扣减1
    public function deCount($userid,$field){
        $OrdUserCountOBJ = Model::ins("OrdUserCount");
        $result = $OrdUserCountOBJ->update($field."=".$field."-1",["id"=>$userid]);
        //更新redis
        if($result)
            Model::ins("OrdUserCount")->hincrbyRedis($userid,[$field=>'-1']);
    }

    // 获取数量
    public function getCount($userid,$field){
        return Model::ins("OrdUserCount")->getRow(["id"=>$userid]);
    }



    
}
