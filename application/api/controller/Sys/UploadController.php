<?php
// +----------------------------------------------------------------------
// |  [ 2016-02-28 购物车信息 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ISir <673638498@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\Sys;
use app\api\ActionController;

use think\Config;

use app\lib\alioss\sts_app;

use DateTime;

class UploadController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    
    private function gmt_iso8601($time){
        $dtStr = date("c", $time);
        $mydatetime = new \DateTime($dtStr);
        $expiration = $mydatetime->format(DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration."Z";
    }

    public function policyAction(){
        //print_r($param);
            //$oss_config = include_once(ROOT_DIR."/lib/configs/alioss.php");
            $oss_config = Config::get("alioss");

            if(!empty($oss_config)){

                $server_type    = $this->params['server_type']!=''?$this->params['server_type']:$oss_config['server_type']; 
                $maxsize        = $this->params['maxsize']!=''?$this->params['maxsize']:4194304; 

                $id             = $oss_config['accessKeyId'];
                $key            = $oss_config['accessKeySecret'];
                $host           = "http://".$oss_config['bucketName'].".".$oss_config['OSSServerName'];
                $expire         = $oss_config['expire']; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
                $ImgServerName  = $oss_config['ImgServerName'];
               

                $now = time();
                //$expire = 30; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
                $end = $now + $expire;
                $expiration = $this->gmt_iso8601($end);
                $dir = (substr($server_type,-1,1)!='/'?$server_type."/":$server_type).date("Y-m-d");

                //最大文件大小.用户可以自己设置
                $condition = array(0=>'content-length-range', 1=>0, 2=>1048576000);
                $conditions[] = $condition; 

                //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
                $start = array(0=>'starts-with', 1=>'$key', 2=>$dir);
                $conditions[] = $start; 


                $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
                //echo json_encode($arr);
                //return;
                $policy = json_encode($arr);
                $base64_policy = base64_encode($policy);
                $string_to_sign = $base64_policy;
                $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

                $response = array();
                $response['accessid'] = $id;
                $response['host'] = $host;
                $response['policy'] = $base64_policy;
                $response['signature'] = $signature;
                $response['expire'] = $end;
                //这个参数是设置用户上传指定的前缀
                
                //$response['callback'] = $base64_callback_body;
                //这个参数是设置用户上传指定的前缀
                $response['dir'] = $dir."/".$this->proRandName();
                $response['success_action_status']=$oss_config['success_action_status'];
                $response['ImgServerName'] = $ImgServerName;
                //echo json_encode($response);
                $return = array(
                        "code"=>200,
                        "data"=>$response,
                    );
            }else{
                $return = array(
                        "code"=>400,
                        "msg"=>"config is empty",
                    );
            }

            //return json_encode($return);
            return $this->json($return['code'],$return['data']);
    }

    /* 设置随机文件名 */
    private function proRandName() {    
      //$fileName = date('YmdHis')."_".rand(100,999);    
      $str_arr = array('a','b','c','d','e','f','g','h','i','j','k','l','n','m','o','p','q','r','s','t','u','v','w','x','y','z');
      $fileName = time().$str_arr[rand(0,25)].$str_arr[rand(0,25)].$str_arr[rand(0,25)].$str_arr[rand(0,25)].rand(1000,9999);
      return $fileName; 
    }

    //保存图片记录
    public function getfile(){
        if($this->isPost()){
            $addData = array(
                'filetype' => $this->params['f_filetype'], 
                'fileoriginal' => $this->params['f_fileoriginal'],
                'filesize' => $this->params['f_filesize'],
                'fileurl'  => $this->params['f_fileurl'],
                'createby' => 'admin',
                'cratetime' => date('Y-m-d H:i:s',time())
                );
            Db::DbTable("LogFile")->insert($addData); 
            return json_encode(array(
                    "code"=>200,
                ));
        }
        return json_encode(array(
                    "code"=>400,
                ));
    }

    /**
     * sts传输方式
     * @Author   zhuangqm
     * @Datetime 2016-11-17T16:15:23+0800
     * @return   [type]                   [description]
     */
    public function stsAction(){

        $oss_config = Config::get("alioss");

        $sts = new sts_app();
        $data = $sts->getSts();

        return $this->json("200",[
                "bucketName"=>$oss_config['bucketName'],
                "host"=>$oss_config['ImgServerName'],
                "dir"=>$oss_config['server_type']!=''?$oss_config['server_type']."/".date("Y-m-d")."/".$this->proRandName():"nnb/images/".date("Y-m-d")."/".$this->proRandName(),
                "param"=>$data,
            ]);
    }
}