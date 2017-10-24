<?php
namespace app\lib\Mq;

use app\lib\Mq\Util;
use think\Config;

use app\lib\Model;

use app\model\Sys\MqModel;

use app\lib\Log;

/*
 * 消息订阅者
 */
class HttpConsumer
{
    //签名
    private static $signature = "Signature";
    //Consumer ID
    private static $consumerid = "ConsumerID";
    //访问码
    private static $ak = "AccessKey";
    //每次取N条记录
    private static $num = 10;

    //配置信息
    private static $config = null;

    private static $topic = null;

    private static $url = null;

    private static $akey = null;

    private static $skey = null;

    private static $cid = null;

    private static $util = null;
    //构造函数
    function __construct()
    {
        
        /*
        [section]
        #您在控制台创建的Topic
        Topic = "xxx"
        #公测环境的URL
        URL = "http://publictest-rest.ons.aliyun.com"
        #阿里云身份验证码
        Ak = "xxx"
        #阿里云身份验证密钥
        Sk = "xxx"
        #MQ控制台创建的Producer ID
        ProducerID = "xxx"
        #MQ控制台创建的Consumer ID
        ConsumerID = "xxx"
         */
        $config = Config::get("mq");
        //获取Topic
        $this::$topic = $config["Topic"];
        //获取Topic的URL路径
        $this::$url = $config["URL"];
        //阿里云身份验证码
        $this::$akey = $config["Ak"];
        //阿里云身份验证密钥
        $this::$skey = $config["Sk"];
        //Consumer ID
        $this::$cid = $config["ConsumerID"];

        $this::$num = $config['num'];


        $this::$util = new Util();
    }

    //订阅流程
    public function process()
    {
        //打印配置信息
        //var_dump($this::$config);
        //获取Topic
        $topic = $this::$topic;
        //获取Topic的URL路径
        $url = $this::$url;
        //阿里云身份验证码
        $ak = $this::$akey;
        //阿里云身份验证密钥
        $sk = $this::$skey;
        //Consumer ID
        $cid = $this::$cid;

        $newline = "\n";
        //构造工具对象
        $util = $this::$util;
        while (true)
        {
            

            try
            {
                //构造时间戳
                $date = time()*1000;
                //签名字符串
                $signString = $topic.$newline.$cid.$newline.$date;
                //计算签名
                $sign = $util->calSignature($signString,$sk);
                //构造签名标记
                $signFlag = $this::$signature.":".$sign;
                //构造密钥标记
                $akFlag = $this::$ak.":".$ak;
                //标记
                $consumerFlag = $this::$consumerid.":".$cid;
                //构造HTTP请求发送内容类型标记
                $contentFlag = "Content-Type:text/html;charset=UTF-8";
                //构造HTTP头部信息
                $headers = array(
                    $signFlag,
                    $akFlag,
                    $consumerFlag,
                    $contentFlag,
                );
                //构造HTTP请求URL
                $getUrl = $url."/message/?topic=".$topic."&time=".$date."&num=".$this::$num;
                //初始化网络通信模块
                $ch = curl_init();
                //填充HTTP头部信息
                curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
                //设置HTTP请求类型,此处为GET
                curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"GET");
                //设置HTTP请求URL
                curl_setopt($ch,CURLOPT_URL,$getUrl);
                //构造执行环境
                ob_start();
                //开始发送HTTP请求
                curl_exec($ch);
                //获取请求应答消息
                $result = ob_get_contents();
                //清理执行环境
                ob_end_clean();
                //打印请求应答信息
                //var_dump($result); 
                //exit;
                //关闭HTTP网络连接
                curl_close($ch);
                //解析HTTP应答信息
                $messages = json_decode($result,true);
                //如果应答信息中的没有包含任何的Topic信息,则直接跳过
                if (count($messages) ==0){
                    continue;
                }
                //string '[{"body":"","bornTime":"1490692511643","key":"http","msgHandle":"X1BFTkRJTkdNU0dfIyVSRVRSWSVDSURfUk1RX1NZU19IVFRQX0NJRF9OTkhfVEVTVC0tLW5uaF90b3BpY190ZXN0I3FkaW50ZXJuZXRvcmRlci0wMiMwIzMxI0NJRF9OTkhfVEVTVA==","msgId":"0A97CB497DDF7FAB0BC28C2C459BA2F0","reconsumeTimes":1,"tag":"http"},{"body":"é\u0098¿é\u0087\u008Cå·´å·´","bornTime":"1490692511748","key":"http","msgHandle":"X1BFTkRJTkdNU0dfIyVSRVRSWSVDSURfUk1RX1NZU19IVFRQX0NJRF9OTkhfVEVTVC0tLW5uaF90b3BpY190ZXN0I3Fka'... (length=9541)
                //依次遍历每个Topic消息
                foreach ((array)$messages as $message)
                {

                    if(MqModel::checkMq($message['msgId'])){

                        //print_r($message);
                        //一个一个进程做处理
                        $param = json_decode($message['body'],true);
                        
                        $result = true;

                        if($result){
                            $this->delTopic($message);
                            MqModel::updateMqLog($message['msgId'],[
                                    "body"=>$message['body'],
                                    "bornTime"=>$message['bornTime'],
                                    "key"=>$message['key'],
                                    "msgHandle"=>$message['msgHandle'],
                                    "reconsumeTimes"=>$message['reconsumeTimes'],
                                    "tag"=>$message['tag'],
                                ]);
                        }
                        
                        foreach($param as $p){
                            try{
                                
                                //执行处理
                                if($p['url']!='' && strpos($p['url'],".")!==false){
                                    // 路径 + 参数
                                    if(!isset($p['param']))
                                        $p['param'] = [];
                                    $arr = explode(".",$p['url']);
                                    Model::new($arr[0].".".$arr[1])->{$arr[2]}($p['param']);
                                }
                                
                            }catch (Exception $e){
                                //打印异常信息
                                //echo $e->getMessage();
                                
                                // 错误处理
                                // MqModel::addMqLog();
                                Log::add($e,__METHOD__);
                            }
                        }
                        
                        $body = null;

                        // 关闭数据库连接
                        Model::ins("SysMq")->close();

                    }
                }
            }
            catch (Exception $e)
            {
                //打印异常信息
                //echo $e->getMessage();
                Log::add($e,__METHOD__);
            }
            
        }
    }

    /*
    处理完成后，删除
     */
    public function delTopic($message){

        //获取Topic
        $topic = $this::$topic;
        //获取Topic的URL路径
        $url = $this::$url;
        //阿里云身份验证码
        $ak = $this::$akey;
        //阿里云身份验证密钥
        $sk = $this::$skey;
        //Consumer ID
        $cid = $this::$cid;

        $util = $this::$util;
        //var_dump($message);
        //var_dump(json_decode($message['body'],true));
        //获取时间戳
        $date = (int)($util->microtime_float()*1000);
        //构造删除Topic消息URL
        $delUrl = $url."/message/?msgHandle=".$message['msgHandle']."&topic=".$topic."&time=".$date;
        //签名字符串
        $signString = $topic.$newline.$cid.$newline.$message['msgHandle'].$newline.$date;
        //计算签名
        $sign = $util->calSignature($signString,$sk);
        //构造签名标记
        $signFlag = $this::$signature.":".$sign;
        //构造密钥标记
        $akFlag = $this::$ak.":".$ak;
        //构造消费者组标记
        $consumerFlag = $this::$consumerid.":".$cid;
        //构造HTTP请求头部信息
        $delheaders = array(
            $signFlag,
            $akFlag,
            $consumerFlag,
            $contentFlag,
        );
        //初始化网络通信模块
        $ch = curl_init();
        //填充HTTP请求头部信息
        curl_setopt($ch,CURLOPT_HTTPHEADER,$delheaders);
        //设置HTTP请求URL信息
        curl_setopt($ch,CURLOPT_URL,$delUrl);
        //设置HTTP请求类型,此处为DELETE
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'DELETE');
        //构造执行环境
        ob_start();
        //开始发送HTTP请求
        curl_exec($ch);
        //获取请求应答消息
        $result = ob_get_contents();
        //清理执行环境
        ob_end_clean();
        //打印应答消息
        //var_dump($result);
        //关闭连接
        curl_close($ch);
    }
}
// //构造消息订阅者
// $consumer = new HttpConsumer();
// //启动消息订阅者
// $consumer->process();        
?>