<?php
namespace app\api;

use app\ComController;

use \think\Config;
use \think\Cache;
use app\model\User\TokenModel;

use app\lib\Model;

class ActionController extends ComController
{   

    public $userid = NULL;

    /*public $version = '';*/

    public function __construct(){
        parent::__construct();

        $this->_checkapi();
    }

    public function _checkapi($userid=''){

        //$this->check_request();
        
        //判断是否需要校验登录令牌
        $check_mtoken_conf = Config::get("checkmtoken");
        if($userid==''){
            if(!empty($check_mtoken_conf[$this->params['_apiname']]))
                $this->check_mtoken();
        }else{
            $this->userid = $userid;
        }
    }


    /**
     * 判断用户是否登录
     * @Author   zhuangqm
     * @DateTime 2017-02-23T18:29:03+0800
     * @return   [type]                   [description]
     */
    protected function check_mtoken(){
        $mtoken = $this->params['mtoken'];
        if(empty($mtoken)) {
            exit($this->json(104));
        }
        
        $tokenModel = new TokenModel();
        $userId = $tokenModel->getTokenId($mtoken);
        if(empty($userId)) {
            exit($this->json(104));
        }else{
            $this->userid = $userId['id'];
        }
    }

    /**
     * 校验app请求是否合法
     * @Author   zhuangqm
     * @DateTime 2017-02-23T18:27:33+0800
     * @return   [type]                   [description]
     */
    protected function check_request(){

        $cc = $this->params['_cc'];
        $ck = $this->params['_ck'];
        $_apiname = $this->params['_apiname'];

        if(empty($_apiname))
            return true;

        $apiname = strtolower($_apiname);

        if(strpos($apiname,"payment")!==false || strpos($apiname,"push")!==false || strpos($apiname,"mycode")!==false || strpos($apiname,"getstopaycodeurl")!==false  || strpos($apiname,"getsotcodeurl")!==false || strpos($apiname,"jumpurl")!==false  || strpos($apiname,"thirdparty")!==false)
            return true;
        
        if(empty($cc) || empty($ck))
            exit($this->json("408"));

        $ApiRequestRedis = Model::Redis("ApiRequest");
        
        // 判断重复提交
        $mtoken = $this->params['mtoken'];

        if(!$ApiRequestRedis->exists($ck)){

            //1 请求安全判断
            $apikey = Config::get("key");
            $check = substr(md5($cc.$apikey['app_key']).md5($cc.$apikey['app_key'].$_apiname),substr($cc,-1,1),51);
            
            if($check!=$ck)
                exit($this->json("408"));

            $ApiRequestRedis->set($ck,'1',6000);

            //2 业务数据判断
            if($this->params['checktoken']!=''){
                if(strtoupper(md5($this->ParaParam($this->params).$apikey['app_key']))!=strtoupper($this->params['checktoken']))
                    exit($this->json("409"));
            }

            //3 判断重复提交
            if(strpos($apiname,"policy")===false){
                $mtoken = $this->params['mtoken'];
                
                if(!empty($mtoken) && strlen($mtoken)<100){
                    $check_key = $this->returnKey();
                    if($ApiRequestRedis->exists($check_key)){
                        // 已存在
                        $lasttime = $ApiRequestRedis->get($check_key);
                        if((microtime(true)-$lasttime)<0.5){
                            // 1秒内的请求 都视为重复提交
                            exit($this->json("410"));
                        }
                    }

                    $ApiRequestRedis->set($check_key,microtime(true),10);
                }
            }

            
        }else{
            exit($this->json("408"));
        }


    }

    protected function returnKey(){
        $tmp = $this->params;
        unset($tmp['_cc']);
        unset($tmp['_ck']);
        unset($tmp['_env']);
        unset($tmp['_network']);
        unset($tmp['_devid']);
        unset($tmp['_time']);

        $keystr = '';
        foreach($tmp as $k=>$v){
            $keystr.=$k."=".$v."&";
        }
        $tmp = null;
        return md5($keystr);
    }

    //将数组转成uri字符串
    protected function ParaParam($param)
    {
        unset($param['checktoken']);
        $buff = "";
        ksort($param);
        foreach ($param as $k => $v){
            $buff .= $k. "=" . stripslashes($v) . "&";
        }
        
        $buff = $buff!=''?substr($buff,0,-1):"";
        return $buff;
    }

    /*
    获取接口版本
     */
    /*public function getVersion(){

        if($this->version==''){
            $v = '1.0.0';
            $_env = $this->getParam("_env");
            $_env = json_decode(stripslashes($_env),true);
            if(!empty($_env['_v']))
                $this->version = substr($_env['_v'],1);
            else
                $this->version = $v;
        }
        return $this->version;
    }

    public function Version($v){
        if($v==$this->getVersion())
            return true;
        else
            return false;
    }*/
}
