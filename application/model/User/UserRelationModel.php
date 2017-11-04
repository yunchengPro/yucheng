<?php
namespace app\model\User;
use app\lib\Model;

class UserRelationModel
{
    /**
    * @user 获取下级关系(数量)
    * @param $role 角色值
    * @param $customerid 用户id值
    * @author jeeluo
    * @date 2017年10月11日下午2:49:01
    */
    public function getChildRelation($param) {
        if($param['customerid'] == '' || $param['customerid'] == 0) {
            return ["code" => "404"];
        }
        
        // 有间接下级和直接下级
        $direct = $this->childDirectRelation($param);
        
        // $indirect = $this->childIndirectRelation($param);
        $total = $this->totalDirectRelation($param);
        // $total = $direct+$indirect;
        
        $result['direct'] = $direct;
        $result['total'] = $total;
        
        return ["code" => "200", "data" => $result];
//         if($param['role'] == 1) {
//             // 有间接下级和直接下级
//             $direct = $this->childDirectRelation($param);
//             $indirect = $this->childIndirectRelation($param);
            
//             $result = $direct+$indirect;
            
//             return ["code" => "200", "data"=>$result];
//         } else if($param['role'] == 2 || $param['role'] == 3 || $param['role'] == 4) {
//             // 有间接下级和直接下级
//             $direct = $this->childDirectRelation($param);
//             return ["code" => "200", "data" => $result];
//         } 
//         return ["code" => "400"];
    }
    
    /**
    * @user 直接下级关系(直接)
    * @param $role 角色值
    * @param $customerid 用户id值
    * @author jeeluo
    * @date 2017年10月11日下午2:39:53
    */
    private function childDirectRelation($param) {
        $CusRelation = Model::ins("CusRelation");

        $cusRelation = $CusRelation->getRow(["parentid"=>$param['customerid']],"count(id) as count");
        
        // $cusRelation = $CusRelation->getRow(["parentrole"=>$param['role'],"parentid"=>$param['customerid']],"count(id) as count");
        return $cusRelation['count'];
    }
    
    /**
    * @user 查看下级关系(间接)
    * @param $role 角色值
    * @param $customerid 用户id值
    * @author jeeluo
    * @date 2017年10月11日下午2:46:03
    */
    private function childIndirectRelation($param) {
        $CusRelation = Model::ins("CusRelation");

        // $cusRelation = $CusRelation->getRow(["grandparole"=>$param['role'],"grandpaid"=>$param['customerid']],"count(id) as count");
        return $cusRelation['count'];
    }

    /**
    * @user 查看用户所有关系
    * @param $customerid 用户id值
    * @author jeeluo
    * @date 2017-10-23 17:26:58
    */
    private function totalDirectRelation($param) {
        $CusRelationList = Model::ins("CusRelationList");

        $total = $CusRelationList->getRow(["parentid"=>$param['customerid']],"count(id) as count");

        // $firstLevel = $CusRelation->getRow(["parentid"=>$param['customerid']],"count(id) as count");
        // $secondLevel = $CusRelation->getRow(["grandpaid"=>$param['customerid']],"count(id) as count");
        // $thirdLevel = $CusRelation->getRow(["ggrandpaid"=>$param['customerid']],"count(id) as count");

        // $total = $firstLevel['count'] + $secondLevel['count'] + $thirdLevel['count'];

        return $total['count'] ? $total['count'] : 0;
    }
}