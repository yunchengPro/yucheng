<?php
namespace app\model\User;

use app\lib\Model;

use think\Config;

class OpenModel
{
    /**
     * 获取openid对应的用户编号
     * @Author   zhuangqm
     * @DateTime 2017-09-07T18:24:12+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function getUserid($param){
        $openid = $param['openid'];
        if(!empty($openid)){
            $row = Model::ins("CusCustomerOpen")->getRow(["openid"=>$openid],"customerid");
            return $row['customerid'];
        }else{
            return '';
        }
    }

    /**
     * 判断openid是否存在
     * @Author   zhuangqm
     * @DateTime 2017-10-12T10:15:13+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function checkOpenid($param){
        $openid = $param['openid'];
        if(empty($openid))
            return false;
        
        $row = Model::ins("CusCustomerOpen")->getRow(["openid"=>$openid],"count(*) as count");
        if($row['count']>0)
            return true;
        else
            return false;
    }

    public function addUserOpenInfo($userinfo){

        if(!empty($userinfo) && $userinfo['openid']!=''){

            $row = Model::ins("CusCustomerOpen")->getRow(["openid"=>$userinfo['openid']],"count(*) as count");

            if($row['count']==0){
                Model::ins("CusCustomerOpen")->insert([
                    "openid"=>$userinfo['openid'],
                    "addtime"=>date("Y-m-d H:i:s"),
                    "open_type"=>"weixin_web",
                    "headimgurl"=>$userinfo['headimgurl'],
                    "nickname"=>$userinfo['nickname'],
                    "sex"=>$userinfo['sex'],
                    "city"=>$userinfo['city'],
                    "country"=>$userinfo['country'],
                    "privilege"=>$userinfo['privilege'],
                    "unionid"=>$userinfo['unionid'],
                    "open_type"=>"weixin",
                ]);
            }else{
                Model::ins("CusCustomerOpen")->update([
                    "headimgurl"=>$userinfo['headimgurl'],
                    "nickname"=>$userinfo['nickname'],
                    "sex"=>$userinfo['sex'],
                    "city"=>$userinfo['city'],
                    "country"=>$userinfo['country'],
                    "privilege"=>$userinfo['privilege'],
                    "unionid"=>$userinfo['unionid'],
                ],["openid"=>$userinfo['openid']]);
            }

        }

        return ["code"=>"200"];
    }

    /**
    * @user 绑定openid 值
    * @param 
    * @author jeeluo
    * @date 2017年10月20日下午5:14:01
    */
    public function bindOpenid($param) {
        // $isOpenRecord = $this->checkOpenid(["openid"=>$param['openid']]);
        $isOpenRecord = Model::ins("CusCustomerOpen")->getRow(["openid"=>$param['openid']],"id,headimgurl,nickname,sex");
        if($isOpenRecord['id']){
            $cusOpen = $this->getUserid(["openid"=>$param['openid']]);
            if(empty($cusOpen)) {
                // 绑定open customerid
                Model::ins("CusCustomerOpen")->update(["customerid"=>$param['customerid']],["openid"=>$param['openid']]);
                return ["code" => "200", "data"=>$isOpenRecord];
            }
            return ["code"=>"200","data"=>[]];
        } 
        return ["code" => "1000"];
    }
}