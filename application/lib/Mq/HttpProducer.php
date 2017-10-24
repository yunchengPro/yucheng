<?php
namespace app\lib\Mq;

use app\lib\Mq\Util;
use think\Config;

/*
 * 消息发布者者
 */
class HttpProducer
{
    //签名
    private static $signature = "Signature";
    //在MQ控制台创建的Producer ID
    private static $producerid = "ProducerID";
    //阿里云身份验证码
    private static $aks = "AccessKey";
    //配置信息
    private static $configs = null;
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
        $this::$configs = Config::get("mq");
    }

    //计算md5
    private function md5($str)
    {
        return md5($str);
    }
    //发布消息流程
    public function process($data,$debug=false)
    {
        //打印配置信息
        //var_dump($this::$configs);
        //获取Topic
        $topic = $this::$configs["Topic"];
        //获取保存Topic的URL路径
        $url = $this::$configs["URL"];
        //读取阿里云访问码
        $ak = $this::$configs["Ak"];
        //读取阿里云密钥
        $sk = $this::$configs["Sk"];
        //读取Producer ID
        $pid = $this::$configs["ProducerID"];
        //HTTP请求体内容
        // $body = json_encode([
        //         "url"=>"AmoAmount",
        //         "param"=>[
        //             "data"=>"1",
        //         ],
        //     ],JSON_UNESCAPED_UNICODE);

        $body = json_encode($data,JSON_UNESCAPED_UNICODE);

        $newline = "\n";
        //构造工具对象
        $util = new Util();
        
        //计算时间戳
        $date = time()*1000;
        //POST请求url
        $postUrl = $url."/message/?topic=".$topic."&time=".$date."&tag=http&key=http";
        //签名字符串
        $signString = $topic.$newline.$pid.$newline.$this->md5($body).$newline.$date;
        //计算签名
        $sign = $util->calSignature($signString,$sk);
        //初始化网络通信模块
        $ch = curl_init();
        //构造签名标记
        $signFlag = $this::$signature.":".$sign;
        //构造密钥标记
        $akFlag = $this::$aks.":".$ak;
        //标记
        $producerFlag = $this::$producerid.":".$pid;
        //构造HTTP请求头部内容类型标记
        $contentFlag = "Content-Type:text/html;charset=UTF-8";
        //构造HTTP请求头部
        $headers = array(
            $signFlag,
            $akFlag,
            $producerFlag,
            $contentFlag,
        );
        //设置HTTP头部内容
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        //设置HTTP请求类型,此处为POST
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"POST");
        //设置HTTP请求的URL
           curl_setopt($ch,CURLOPT_URL,$postUrl);
        //设置HTTP请求的body
        curl_setopt($ch,CURLOPT_POSTFIELDS,$body);
        //构造执行环境
        ob_start();
        //开始发送HTTP请求
        curl_exec($ch);
        //获取请求应答消息
        $result = ob_get_contents();
        //清理执行环境
        ob_end_clean();
        //打印请求应答结果
        if($debug)
            var_dump($result); //返回json '{"msgId":"0A97CB5E159A7FAB0BC28C2C7A975705","sendStatus":"SEND_OK"}'
        //关闭连接
        curl_close($ch);
        return json_decode($result,true);
    }
}
// //构造消息发布者
// $producer = new HttpProducer();
// //启动消息发布者
// $producer->process();
?>