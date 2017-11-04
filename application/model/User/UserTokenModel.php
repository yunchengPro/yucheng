<?php
namespace app\model\User;
use app\lib\Model;
use app\model\Sys\CommonModel;

class UserTokenModel {
    const minRandToken = 10000;
    
    const maxRandToken = 99999;
    
    public function buildToken($customerid) {
        return md5($customerid."_".time()."_".rand(self::minRandToken, self::maxRandToken));
    }
    
    public function getTokenId($token) {
    
        $mtokenRedis = Model::Redis("UserMtoken");
        if($mtokenRedis->exists($token)) {
            $user = $mtokenRedis->getRow($token);
    
            $userId['id'] = $user['userid'];
    
            return $userId;
        } else {
            $mtoken = Model::ins("CusMtoken")->getRow(["mtoken"=>$token],"id");
    
            if(!empty($mtoken)) {
                // 写入
                $this->insertMtokenRedis(["mtoken"=>$token,"customerid"=>$mtoken['id']]);
//                 Model::new("User.User")->insertMtokenRedis(["mtoken"=>$token,"userid"=>$mtoken['id']]);
                return $mtoken;
            }
            return false;
        }
        return false;
    }
    
    /**
    * @user 修改 写入mtoken数据
    * @param 
    * @author jeeluo
    * @date 2017年10月23日下午3:37:06
    */
    public function updateInsertToken($param) {
        $customerid = $param['customerid'];
        
        unset($param['customerid']);
        // 查询用户mtoken是否存在
        $CusMtoken = Model::ins("CusMtoken");
        
        $mtokenInfo = $CusMtoken->getRow(["id"=>$customerid]);
        if(empty($mtokenInfo)) {
            $param['id'] = $customerid;
            // 写入数据
            $status = $CusMtoken->insert($param);
        } else {

            // 删除redis
            $this->deleteMtokenRedis($mtokenInfo["mtoken"]);

            // 修改数据
            $CusMtoken->update($param, ["id"=>$customerid]);
        }
        
        return ["code" => "200"];
    }
    
    /**
    * @user 修改mtoken redis数据
    * @param 
    * @author jeeluo
    * @date 2017年10月27日下午2:13:09
    */
    public function updateMtokenRedis($param) {
        if(empty($param)) {
            return ["code" => "404"];
        }
        
        $mtoken = $param['mtoken'];
        // * 这里会传递过mtoken
        unset($param['mtoken']);
        $mtokenRedis = Model::Redis("UserMtoken");
        
        
        // 检测mtoken
        if($mtokenRedis->exists($mtoken)) {
            $data = $mtokenRedis->getRow($mtoken);
        
            foreach ($param as $k => $v) {
                $data[$k] = $v;
            }
        
            $mtokenRedis->update($mtoken,$data,CommonModel::mtokenRedisOutTime());
        } else {
            // redis有异
            return ["code"=>"400"];
        }
        return ["code" => "200"];
    }
    
    /**
    * @user 获取
    * @param 
    * @author jeeluo
    * @date 2017年10月23日下午3:14:56
    */
    public function getMtokenRedis($param) {
        if(empty($param['mtoken'])) {
            return ["code"=>"404"];
        }
        
        $mtoken = $param['mtoken'];
        $data = [];
        
        // 判断mtoken 是否在redis中存在
        $mtokenRedis = Model::Redis("UserMtoken");
        if($mtokenRedis->exists($mtoken)) {
            // mtoken 存在于redis中(读取redis数据)
            $data = $mtokenRedis->getRow($mtoken);
        } else {
            if(empty($param['customerid'])) {
                return ["code" => "404"];
            }
            // 不存在(写入redis数据)
            $result = $this->insertMtokenRedis($param);
            if($result["code"] == "200") {
                $data = $result["data"];
            }
        }
        return ["code" => "200", "data"=>$data];
    }
    
    /**
    * @user 写入redis mtoken信息
    * @param 
    * @author jeeluo
    * @date 2017年10月23日下午3:13:11
    */
    public function insertMtokenRedis($param) {
        $mtokenRedis = Model::Redis("UserMtoken");
        $params = [];
        if($mtokenRedis->exists($param['mtoken'])) {
            
        } else {
            // 根据用户id 查询用户数据(部分简易)
            $cusModel = Model::ins("CusCustomer");
            $cusInfoModel = Model::ins("CusCustomerInfo");
            
            $cus = $cusModel->getRow(["id"=>$param['customerid']],"mobile");
            $cusInfo = $cusInfoModel->getRow(["id"=>$param['customerid']],"realname,nickname,headerpic");
            
            $params['userid'] = $param['customerid'];
            $params['mobile'] = $cus['mobile'];
            $params['realname'] = $cusInfo['realname'];
            $params['nickname'] = $cusInfo['nickname'];
            $params['headerpic'] = $cusInfo['headerpic'];
            
            $mtokenRedis->insert($param['mtoken'],$params,CommonModel::mtokenRedisOutTime());
        }
        return ["code" => "200", "data"=>$params];
    }
    
    /**
    * @user 删除mtoken redis
    * @param 
    * @author jeeluo
    * @date 2017年10月23日下午3:02:32
    */
    public function deleteMtokenRedis($redis_key) {
        $mtokenRedis = Model::Redis("UserMtoken");
        
        if($mtokenRedis->exists($redis_key)) {
            // 删除redis
            $mtokenRedis->del($redis_key);
        }
        
        return ["code" => "200"];
    }
}