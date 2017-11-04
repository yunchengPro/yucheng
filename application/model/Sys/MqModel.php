<?php
namespace app\model\Sys;

use app\lib\Model;

//发送消息
use app\lib\Mq\HttpProducer;

//消息订阅
use app\lib\Mq\HttpConsumer;

class MqModel
{

    static $mqlist = [];

    static $debug = false;
    /*
    构造消息发布者
    [
    url   路径  格式如 flow.flow.test  test为方法
    param = [ 参数  数组
            "tablename"=>"",
            "data"=>[],
        ]
    ]
     */
    public static function push($param){

        if($param['url']!=''){
            // 路径 + 参数
            if(!isset($param['param']))
                $param['param'] = [];
            $arr = explode(".",$param['url']);
            return Model::new($arr[0].".".$arr[1])->{$arr[2]}($param['param']);

        }else{
            return false;
        }
/*
        //构造消息发布者
        $producer = new HttpProducer();
        //启动消息发布者
        $result = $producer->process($param); // ["msgId"=>"0A97CB5E159A7FAB0BC28C2C7A975705","sendStatus"=>"SEND_OK"]
        if($result['sendStatus']=='SEND_OK'){
            //写发送日志
            self::addMqLog($result);
            return true;
        }else{
            return false;
        }*/
    }

    /*
    构造消息订阅者
     */
    public static function pull(){
        // 构造消息订阅者
        $consumer = new HttpConsumer();
        //启动消息订阅者
        $consumer->process();  
    }

    //发送mq日志
    public static function addMqLog($data){
        Model::ins("SysMq")->insert($data);
    }

    // mq请求日志
    public static function addMqListLog($data){
        Model::ins("SysMqList")->insert([
                "content"=>json_encode($data,JSON_UNESCAPED_UNICODE),
                "addtime"=>date("Y-m-d H:i:s"),
            ]);
    }

    public static function updateMqLog($msgId,$data){
       
        Model::ins("SysMq")->update($data,["msgId"=>$msgId]);
    }

    //判断消息队列是否执行成功
    public static function checkMq($msgId){

        if($msgId=='')
            return false;

        $row = Model::ins("SysMq")->getRow(["msgId"=>$msgId],"id,bornTime");

        if(!empty($row)){
            if($row['bornTime']=='')
                return true;
            else
                return false;
        }else{
            return false;
        }
    }

    // 添加消息队列
    /*
    $param = [
        "url"=> url   路径  格式如 flow.flow.test  test为方法
        "param"=> 方法
    ]
     */
    public static function add($param){
        return true;
        self::$mqlist[] = $param;
    }

    public static function getMqList(){
        return self::$mqlist;
    }

    public static function setDebug($flag=false){
        self::$debug = $flag;
    }

    // 提交
    public static function submit(){
        return true;
        if(!empty(self::$mqlist)){
            
            // 记录消息请求信息
            self::addMqListLog(self::$mqlist);

            //构造消息发布者
            $producer = new HttpProducer();
            //启动消息发布者
            $result = $producer->process(self::$mqlist,self::$debug); // ["msgId"=>"0A97CB5E159A7FAB0BC28C2C7A975705","sendStatus"=>"SEND_OK"]
            
            // 清空请求数据
            self::$mqlist = [];

            if($result['sendStatus']=='SEND_OK'){
                //写发送日志
                self::addMqLog($result);
                return true;
            }else{
                return $result;
            }
        }else{
            return ["code"=>"empty"];
        }
    }

    public static function get(){
        // 构造消息订阅者
        $consumer = new HttpConsumer();
        //启动消息订阅者
        $consumer->process();  
    }

}