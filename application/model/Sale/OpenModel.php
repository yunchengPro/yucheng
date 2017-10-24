<?php
namespace app\model\Sale;

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
            $row = Model::ins("SaleCusCustomerOpen")->getRow(["openid"=>$openid],"customerid");
            return $row['customerid'];
        }else{
            return '';
        }
    }

    public function addUserOpenInfo($userinfo){

        if(!empty($userinfo) && $userinfo['openid']!=''){

            $row = Model::ins("SaleCusCustomerOpen")->getRow(["openid"=>$userinfo['openid']],"count(*) as count");

            if($row['count']==0){
                Model::ins("SaleCusCustomerOpen")->insert([
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
                ]);
            }else{
                Model::ins("SaleCusCustomerOpen")->update([
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
}