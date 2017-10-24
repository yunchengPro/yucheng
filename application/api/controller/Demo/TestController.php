<?php
namespace app\api\controller\Demo;
use app\api\ActionController;

use app\api\model\ModelDemo;

//获取配置
use \think\Config;

//获取Db操作类
use app\lib\Db;

//获取Redis操作类
use app\lib\Redis;

//获取MongoDb操作类
use app\lib\MongoDb;

//短信发送操作类
use app\lib\Sms;

//日志
use \think\Log;

use app\lib\Model;

use app\model\Order\OrderPayModel;

use app\lib\RongCloud\RongcloudClass;

use app\lib\Img;

use app\lib\QRcode;

use app\lib\Pay\Allinpay\Ali as Allinpay_ali;

use app\lib\Pay\Weixin\WeixinApp;

use app\lib\Pay\Ali\alipay_app;
use app\model\Sys\CommonRoleModel;


use app\lib\Pay\Allinpay\ProcessServlet;

use app\lib\Pay\Allinpay\Allinpay;

use app\lib\ApiService\Bank;

use app\lib\ApiService\MobileArea;

use app\lib\ApiService\Sms as SmsApi;

class TestController extends ActionController
{
    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    
    public function privatekeyAction() {
        $mobile = $this->params['mobile'];
        if(empty($mobile)) {
            return $this->json(404);
        }
        if($this->Version("1.0.0"))
            return $this->json("200", ["privatekey" => md5($mobile.getConfigKey())]);
        if($this->Version("1.0.1"))
            return $this->json("200", ["privatekey" => 1]);
        if($this->Version("1.0.2"))
            return $this->json("200", ["privatekey" => md5($mobile.getConfigKey())]);
        if($this->Version("1.0.3"))
            return $this->json("200", ["privatekey" => md5($mobile.getConfigKey())]);
    }

    
    public function valicodeAction(){
        $code = $this->params['code'];

        if(empty($code))
            return $this->json("404");

        $config = Config::get("key");

        return $this->json("200",[
                "valicode"=>md5($code.$config['app_key']),
            ]);
    }

    /*
    分润测试流程
     */
    public function profitAction(){

        $orderOBJ = Model::ins("OrdOrder");

        $order = $orderOBJ->getInfoByOrderNo('NNH20170322104813818212',"*");

        OrderPayModel::updateOrderBuy($order,"ali_app",[
                "total_amount"=>99,
            ]);
    }


    public function bullcode1Action(){
        /*$key = Config::get("key");
        echo $key['app_key']."----";
        echo md5('70521814335641945333'.$key['app_key']);

        exit;*/
        $bullcode = Model::ins("BullCode");

        $bullcodecode = Model::ins("BullCodeCode");

        
        $key = Config::get("key");

        $addtime = date("Y-m-d H:i:s");

        $amount = 100000;

        for($i=1;$i<=100;$i++){
            //$show_code = rand(1000,9999).rand(1000,9999).rand(1000,9999).rand(1000,9999).rand(1000,9999);
            $show_code = rand(1000,9999).rand(1000,9999).rand(100,999); // 11位
            $count = $bullcodecode->getRow(["show_code"=>$show_code],"count(*) as count");
            if($count['count']==0){

                $bull_code = md5($show_code.$key['app_key']);

                $data = [
                    "bull_code"=>$bull_code,
                    "show_code"=>$show_code,
                    "amount"=>$amount,
                ];
                $bullcodecode->insert($data);
            }

        
        }

        echo "OK";
        exit;
    }

    public function bullcode2Action(){


        /*
        1.  100元充值卡   2000张
        2.  50元充值卡     2000张
        3.  20元充值卡     1000张  
         */

        //$bullcode = Model::ins("BullCode");

        $bullcodecode = Model::ins("BullCodeCode");

        
        $key = Config::get("key");

        $addtime = date("Y-m-d H:i:s");


        echo md5("44701760603673721446".$key['app_key']);
        exit;

        $amount = 10000;

        for($i=1;$i<=2000;$i++){
            $show_code = rand(1000,9999).rand(1000,9999).rand(1000,9999).rand(1000,9999).rand(1000,9999);
            $count = $bullcodecode->getRow(["show_code"=>$show_code],"count(*) as count");
            if($count['count']==0){

                $bull_code = md5($show_code.$key['app_key']);

                $data = [
                    "bull_code"=>$bull_code,
                    "show_code"=>$show_code,
                    "amount"=>$amount,
                ];
                $bullcodecode->insert($data);
            }

        
        }
    }

    public function bullcode3Action(){

        $bullcodecode = Model::ins("BullCodeCode");

         $key = Config::get("key");

        $list = $bullcodecode->getList([]);

        foreach($list as $k=>$v){
            $bull_code = md5($v['show_code'].$key['app_key']);
            $bullcodecode->modify(["bull_code"=>$bull_code],["id"=>$v['id']]);
        }
        echo "OK";
    }

    public function gettokenAction(){

        $result = Model::new("Msg.RongCloud")->getToken(1);

        print_r($result);
    }

    // 发送文本消息
    public function sendmsgAction(){

        $obj = new RongcloudClass();

        // 订单 消息
        $result = $obj->SendTxtMsg([
                "fromuser"=>"48fdf6e3f967fa0d564855103dafe654",
                "touser"=>"Android",
            ]);

        print_r($result);
    }

    public function sendsysmsgAction(){

        $obj = new RongcloudClass();

        /*$result = $obj->SendSysMsg([
                "fromuser"=>"48fdf6e3f967fa0d564855103dafe654",
                "touser"=>"nnh02",
            ]);*/

        $result = $obj->SendSysTxtImgMsg([
                "fromuser"=>"48fdf6e3f967fa0d564855103dafe654",
                "touser"=>"Android",
                "content"=>[
                    "title"=>"您购买的[美的空调]订单已发货",
                    "content"=>"消息文本内容",
                    "imageUri"=>Img::url("nnh/images/2017-03-20/1489995657fhvo6888.jpeg",500,500),
                    "url"=>"http://mobile.niuniuhuiapp.com",
                    "extra"=>["orderno"=>"NNH20170309143829963586","productname"=>"美的光波微波炉智能控制温度大红星NAZ-M1000"],
                ],
            ]);


        print_r($result);
    }

    public function refushtokenAction(){

        $RongcloudClass = new RongcloudClass();

        $token_result = $RongcloudClass->refresh([
            "userid"=>md5("-1"),
            "name"=>"订单处理消息",
            "headerpic"=>"http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492503724vo538.png",
        ]);

        print_r($token_result);

        $token_result = $RongcloudClass->refresh([
            "userid"=>md5("-2"),
            "name"=>"分润收益消息",
            "headerpic"=>"http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492504443cl312.png",
        ]);

        print_r($token_result);


        $token_result = $RongcloudClass->refresh([
            "userid"=>md5("-3"),
            "name"=>"充值提现消息",
            "headerpic"=>"http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492504548yv228.png",
        ]);

        print_r($token_result);

        $token_result = $RongcloudClass->refresh([
            "userid"=>md5("-4"),
            "name"=>"系统消息",
            "headerpic"=>"http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492504634ks882.png",
        ]);

        print_r($token_result);
    }

    public function gettokenbuild1Action(){

        $obj = new RongcloudClass();

        $userid = md5("-1");
        $name = "订单处理消息";
        $headerpic = "http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492503724vo538.png";

        /*$userid = md5("-2");
        $name = "分润收益消息";
        $headerpic = "http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492504443cl312.png";

        $userid = md5("-3");
        $name = "充值提现消息";
        $headerpic = "http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492504548yv228.png";

        $userid = md5("-4");
        $name = "系统消息";
        $headerpic = "http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492504634ks882.png";
*/
        //echo $userid."-----";

        $param = [
            "userid"=>$userid,
            "name"=>$name,
            "headerpic"=>$headerpic,
        ];

        $cusrongcloudOBJ = Model::ins("CusRongcloud");
        $result = $obj->getToken($param);
        if($result['code']=='200' && $result['token']!=''){
            $cusrongcloudOBJ->insert([
                    "customerid"=>-1,
                    "token"=>$result['token'],
                    "userid"=>$userid,
                    "addtime"=>date("Y-m-d H:i:s"),
                    "name"=>$name,
                    "portraitUri"=>$headerpic,
                ]);
        }

        //print_r($result);
        return $this->json("200",["result"=>$result]);
    }

    public function gettokenbuild2Action(){

        $obj = new RongcloudClass();
/*
        $userid = md5("-1");
        $name = "订单处理消息";
        $headerpic = "http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492503724vo538.png";
*/
        $userid = md5("-2");
        $name = "分润收益消息";
        $headerpic = "http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492504443cl312.png";

       /* $userid = md5("-3");
        $name = "充值提现消息";
        $headerpic = "http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492504548yv228.png";

        $userid = md5("-4");
        $name = "系统消息";
        $headerpic = "http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492504634ks882.png";
*/
        //echo $userid."-----";

        $param = [
            "userid"=>$userid,
            "name"=>$name,
            "headerpic"=>$headerpic,
        ];

        $cusrongcloudOBJ = Model::ins("CusRongcloud");
        $result = $obj->getToken($param);
        if($result['code']=='200' && $result['token']!=''){
            $cusrongcloudOBJ->insert([
                    "customerid"=>-2,
                    "token"=>$result['token'],
                    "userid"=>$userid,
                    "addtime"=>date("Y-m-d H:i:s"),
                    "name"=>$name,
                    "portraitUri"=>$headerpic,
                ]);
        }

        //print_r($result);
        return $this->json("200",["result"=>$result]);
    }

    public function gettokenbuild3Action(){

        $obj = new RongcloudClass();

       /* $userid = md5("-1");
        $name = "订单处理消息";
        $headerpic = "http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492503724vo538.png";

        $userid = md5("-2");
        $name = "分润收益消息";
        $headerpic = "http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492504443cl312.png";
*/
        $userid = md5("-3");
        $name = "充值提现消息";
        $headerpic = "http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492504548yv228.png";

       /* $userid = md5("-4");
        $name = "系统消息";
        $headerpic = "http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492504634ks882.png";
*/
        //echo $userid."-----";

        $param = [
            "userid"=>$userid,
            "name"=>$name,
            "headerpic"=>$headerpic,
        ];

        $cusrongcloudOBJ = Model::ins("CusRongcloud");
        $result = $obj->getToken($param);
        if($result['code']=='200' && $result['token']!=''){
            $cusrongcloudOBJ->insert([
                    "customerid"=>-3,
                    "token"=>$result['token'],
                    "userid"=>$userid,
                    "addtime"=>date("Y-m-d H:i:s"),
                    "name"=>$name,
                    "portraitUri"=>$headerpic,
                ]);
        }
        //print_r($result);
        return $this->json("200",["result"=>$result]);
    }

    public function gettokenbuild4Action(){

        $obj = new RongcloudClass();

     /*   $userid = md5("-1");
        $name = "订单处理消息";
        $headerpic = "http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492503724vo538.png";

        $userid = md5("-2");
        $name = "分润收益消息";
        $headerpic = "http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492504443cl312.png";

        $userid = md5("-3");
        $name = "充值提现消息";
        $headerpic = "http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492504548yv228.png";
*/
        $userid = md5("-4");
        $name = "系统消息";
        $headerpic = "http://nnhtest.oss-cn-shenzhen.aliyuncs.com/NNH/images/2017-04-18/1492504634ks882.png";

        //echo $userid."-----";

        $param = [
            "userid"=>$userid,
            "name"=>$name,
            "headerpic"=>$headerpic,
        ];

        $cusrongcloudOBJ = Model::ins("CusRongcloud");
        $result = $obj->getToken($param);
        if($result['code']=='200' && $result['token']!=''){
            $cusrongcloudOBJ->insert([
                    "customerid"=>-4,
                    "token"=>$result['token'],
                    "userid"=>$userid,
                    "addtime"=>date("Y-m-d H:i:s"),
                    "name"=>$name,
                    "portraitUri"=>$headerpic,
                ]);
        }

        //print_r($result);
        return $this->json("200",["result"=>$result]);
    }

    // 发送消息测试
    public function msgtestAction(){
        $obj = new RongcloudClass();

        $touser  = '8065ef80e5c10ff228ca5ecaab2985ff';

        echo $touser.'=========';

        // 订单 消息
        $result = $obj->SendMsg([
                "fromuser"=>"6bb61e3b7bce0931da574d19d1d82c88",
                "touser"=>$touser,
                "msgtype"=>"RC:ImgTextMsg",
                "content"=>[
                    "title"=>"您购买的[美的空调]订单已发货".date("Y-m-d H:i:s"),
                    "content"=>"消息文本内容",
                    "imageUri"=>Img::url("nnh/images/2017-03-20/1489995657fhvo6888.jpeg",500,500),
                    "url"=>"http://mobile.niuniuhuiapp.com",
                    "extra"=>["orderno"=>"NNH20170309143829963586","productname"=>"美的光波微波炉智能控制温度大红星NAZ-M1000"],
                ],
            ]);

        print_r($result);

        // 分润收益消息
        $result = $obj->SendMsg([
                "fromuser"=>"5d7b9adcbe1c629ec722529dd12e5129",
                "touser"=>$touser,
                "msgtype"=>"RC:TxtMsg",
                "content"=>[
                    "content"=>"您在牛排消费获得100.00牛豆".date("Y-m-d H:i:s"),
                    "extra"=>["title"=>"分润收益"],
                ],
            ]);

        print_r($result);

        // 充值提现消息
        $result = $obj->SendMsg([
                "fromuser"=>"0267aaf632e87a63288a08331f22c7c3", // b3149ecea4628efd23d2f86e5a723472
                "touser"=>$touser,
                "msgtype"=>"RC:TxtMsg",
                "content"=>[
                    "content"=>"尊敬的用户，您于****成功充值400000.00元现金".date("Y-m-d H:i:s")."\n如有疑问，请拨打000-123234782",
                    "extra"=>["title"=>"充值成功"],
                ],
            ]);

        print_r($result);

        // 系统消息
        $result = $obj->SendSysMsg([
                "fromuser"=>"0267aaf632e87a63288a08331f22c7c3",
                "touser"=>$touser,
                "msgtype"=>"RC:ImgTextMsg",
                "content"=>[
                    "title"=>"系统消息-标题".date("Y-m-d H:i:s"),
                    "content"=>"系统消息-内容".date("Y-m-d H:i:s"),
                    "imageUri"=>Img::url("nnh/images/2017-03-20/1489995657fhvo6888.jpeg",500,500),
                    "url"=>"http://mobile.niuniuhuiapp.com",
                    "extra"=>["orderno"=>"NNH20170309143829963586","productname"=>"美的光波微波炉智能控制温度大红星NAZ-M1000"],
                ],
            ]);

        print_r($result);
    }

    public function codeAction(){
        echo QRcode::png("http://www.niuniuhuiapp.cn");
        exit;
    }

    public function lbsAction(){

        print_r(Model::new("Sys.Lbs")->Distance(113.93998,22.544574,113.937903,22.545151));

    }


    public function mqtestAction(){
        /*
        Model::new("Sys.Mq")->add([
                "url"=>"Demo.Mq.test",
                "param"=>[
                    "bull_code"=>"A".rand(1000,9999).time().rand(1000,9999),
                    "addtime"=>date("Y-m-d H:i:s"),
                ],
            ]);

        Model::new("Sys.Mq")->submit();
*/

        Model::ins("BullCode")->insert([
                "bull_code"=>rand(1000,9999),
                "addtime"=>date("Y-m-d H:i:s"),
            ]);

        return $this->json("200");

        /*Model::new("Sys.Mq")->add([
                "url"=>"Msg.SendMsg.NewUserMsg",
                "param"=>[
                    "userid"=>$userid,
                ],
            ]);

        Model::new("Sys.Mq")->submit();*/
        
        exit;
    }

    public function getUserTokenAction(){

        $RongcloudClass = new RongcloudClass();

        $userid = 1144;

        //$touser = Model::new("Msg.RongCloud")->getUserToken($userid);
        $touser = "8065ef80e5c10ff228ca5ecaab2985ff";
        echo $touser;
        if($touser!=''){

            $fromuser = "6bb61e3b7bce0931da574d19d1d82c88"; // 系统消息

            /*$result = $RongcloudClass->SendMsg([
                    "fromuser"=>$fromuser,
                    "touser"=>$touser,
                    "msgtype"=>"RC:TxtMsg",
                    "content"=>[
                        "content"=>"欢迎您来到APP！我们在这为您提供上千万种商品和实体店，在这，您将可以开启您的购物、分享之旅。",
                        "extra"=>["title"=>"注册成功"],
                    ],
                ]);*/
            $result = $RongcloudClass->SendMsg([
                    "fromuser"=>$fromuser,
                    "touser"=>$touser,
                    "msgtype"=>"RC:TxtMsg",
                    "content"=>[
                        "content"=>"您购买的XXXXX已经发货了",
                        "extra"=>["title"=>"订单已发货"],
                    ],
                ]);
            print_r($result);
        }
    }

    // 测试脚本完成后执行
    public function testregAction(){

        var_dump(Model::new("Sys.Register")->shutdown([
                "class"=>"\app\model\Demo\MqModel",
                "method"=>"test",
                "param"=>["bull_code"=>rand(100,999),"addtime"=>date("Y-m-d H:i:s"),"amount"=>rand(100,999)],
            ]));
        echo "ccccccc";
    }

    // 数据库关闭
    public function mysqlcloseAction(){
        $row1 = Model::ins("CusCustomer")->getRow(["id"=>1],"*");
        print_r($row1);
        Model::ins("CusCustomer")->close();
        $row2 = Model::ins("CusCustomer")->getRow(["id"=>3],"*");
        print_r($row2);
        Model::ins("CusCustomer")->close();
    }

    public function test54Action(){
        OrderPayModel::orderpay_tnd([
                "userid"=>2113,
                "orderno"=>"NNHTND20170613203400649024",
                "flowid"=>"NNHTND20170613203400649024".rand(1000,9999),
            ]);
    }


    public function alipayAction(){

        $payOBJ = new Allinpay_ali();

        $data = $payOBJ->getPayOrder([
                "userid"=>4,
                "orderno"=>"NNH20170616192950514538",
                "pay_price"=>1000,
            ]);

        //print_r($data);
        $result = json_decode($data,true);
        //echo $result['payinfo'];
        //exit;
        if($result['payinfo']!='')
            header("Location:".$result['payinfo']);
        else
            exit("参数有误"); 
        exit;
    }

    public function downOrderListAction(){
        $weixinapp = new WeixinApp();
        echo $weixinapp->downOrderList();
    }

    // 获取支付宝对账单
    public function alibillAction(){
        $payOBJ = new alipay_app();

        $result = $payOBJ->downbill([
                "bill_date"=>date("Y-m-d",strtotime("-1 day")),
            ]);
        //print_r($result);

        $status = 0;
        $filename = '';
        $bill_url = "";
        if(!empty($result['bill_url'])){
            $bill_url = $result['bill_url'];
            
            preg_match("/&downloadFileName=(.*?)&/i",urldecode($bill_url),$return);  
            $filename = $return[1];

            $filepath = "/home/alibill/".$filename; // "/home/alibill/".$filename

            if(!empty($filename) && !file_exists($filepath)){

                // 1 下载文件
                $file_handle = fopen($bill_url,'r');
                $data = '';
                if($file_handle){
                    while(!feof($file_handle)) {
                        $data.= fgets($file_handle);
                    }
                }
                //echo $data;
                $handle = fopen($filepath,'w');
                fwrite($handle, $data);

                fclose($file_handle);
                fclose($handle);

                exec("unzip ".$filepath);

                /*
                
                exec("wget -P /home/alibill/".$filename." ".$bill_url);

                //2 解压文件
                exec("unzip /home/alibill/".$filename);
                */
                //echo "OK!!!!";
                $status = 1;
            }else{

                //echo "file exists";
                $status = 2;
            }
        }
        return $this->json("200",[
                "result"=>$result,
                "filename"=>$filename,
                "status"=>$status,
                "bill_url"=>$bill_url,
            ]);
        //exit;
    }
    
    public function getgiveuserAction() {
        $data = '';
        if(CommonRoleModel::getUserRoleGive(["customerid"=>$this->params['customerid'],"role"=>$this->params['role']])) {
            $data = "非赠送";
        } else {
            $data = "赠送";
        }
        return $this->json(200, $data);
    }


    public function processservletAction(){
        $ProcessServlet = new ProcessServlet();

        // 实时代付
        $response = $ProcessServlet->getPayOrder([
                "userid"=>4,
                "orderno"=>"NNH20170616192950514593",
                "pay_price"=>1,
                "account_no"=>"6216607000005865053",
                "account_name"=>"陈国正",
                "summary"=>"代付测试",
            ]);
        /*$response = $ProcessServlet->getPayOrder([
                "userid"=>4,
                "orderno"=>"NNH20170616192950514594",
                "batch_list"=>[
                    [
                        "pay_price"=>1,
                        "account_no"=>"6216607000005865053",
                        "account_name"=>"陈国正",
                        "summary"=>"代付测试1",
                    ],
                    [
                        "pay_price"=>2,
                        "account_no"=>"6216607000005865053",
                        "account_name"=>"陈国正",
                        "summary"=>"代付测试2",
                    ],
                ],
            ]);    */
        echo "===========结果===========\n";
        print_r($response);
        

        /*if($response['AIPG']['INFO']['RET_CODE']=='0000'){
            echo "OK";
        }else{
            echo $response['AIPG']['INFO']['ERR_MSG'];
        }*/

        exit;
    }

    // 实时单笔代付
    public function timeprocessservletAction(){
        $ProcessServlet = new ProcessServlet();

        $response = $ProcessServlet->getTimePayOrder([
                "userid"=>4,
                "orderno"=>"NNH20170616192950514541",
                "pay_price"=>1,
                "account_no"=>"6216607000005865053",
                "account_name"=>"陈国正",
                "summary"=>"代付测试",
            ]);
        echo "===========结果===========\n";
        print_r($response);
        

        if($response['AIPG']['INFO']['RET_CODE']=='0000'){
            echo "OK";
        }else{
            echo $response['AIPG']['INFO']['ERR_MSG'];
        }

        exit;
    }


    public function processservletresultAction(){
        $ProcessServlet = new ProcessServlet();

        // 查询支付结果
        $response = $ProcessServlet->getOrderResult([
                
                "query_sn"=>"2005840000140661501484050SnEEQyzYbG",
                
            ]);    
        echo "===========结果===========\n";
        print_r($response);
        

        /*if($response['AIPG']['INFO']['RET_CODE']=='0000'){
            echo "OK";
        }else{
            echo $response['AIPG']['INFO']['ERR_MSG'];
        }*/

        exit;
    }

    
    public function test21Action() {
        Model::new("Sys.Mq")->add([
            "url"=>"Order.OrderMsg.sysAreaerror",
            "param"=>[
                "id"=>1,
                "mobile"=>"13265411044",
                "type" => 1,
            ],
        ]);
        Model::new("Sys.Mq")->submit();
    }


    public function buildqrcodeAction(){
        // public static function build_png($text,$filename='',$str='',$matrixPointSize=10,$png_maring=2){ 40148
        $list = Model::ins("StoBusinessCodeBak")->getList("isuse=-1 and business_code>='43360' and business_code<='43413'","*");
        //$list = Model::ins("StoBusinessCode")->getList("business_code='32300'","*");
        
        $config = Config::get("key");

        foreach($list as $k=>$v){
            $code = $v['business_code'];
            $url = "http://mobile.niuniuhuiapp.com/StoBusiness/Index/setpayamount?managerid=&noinvamount=&amount=&business_code=".$code."&stocode=".$code."&type=2&qrcodev=20170802";
            
            $result = QRcode::build_png($url,$code,"平台号:".$code,40,2);
        }
        print_r($result);
        
        exit;
    }

    /**
     *                      userid,
     *                                           orderno,
     *                                           pay_price
     *                                           order_time
     * @Author   zhuangqm
     * @DateTime 2017-07-25T14:45:28+0800
     * @return   [type]                   [description]
     */
    public function aaAction(){
        echo Allinpay::QuickWeb([
                "userid"=>2888,
                "orderno"=>'NNHNR20170725145908372158',
                "pay_price"=>29800,
                "order_time"=>'2017-07-25 14:59:08',
            ]);
        exit;
    }


    public function bbAction(){

        echo Img::url("http://nnhtest.oss-cn-shenzhen.aliyuncs.com/nnh/images/2017-07-27/1501144268omei3391.jpeg");
        exit;

    }

    public function ccAction(){
        $AllinpayProductLog         = Model::ins("AllinpayProductLog");
        exit;
    }
    
    
    public function valicodephoneAction() {
        $mobile = $this->params['mobile'];
        
        if(phone_filter($mobile)) {
            return $this->json(200, ["错误的"]);
        }
        return $this->json(200, ["正确的"]);
    }

    public function checkbankAction(){

        $result = Bank::api([
                "cardNo"=>"6214857555093543",
                "realName"=>"庄秋敏",
            ]);

        if($result==true)
            echo "OK";
        else
            echo "error";
        exit;
    }

    public function getmobileareaAction(){
        $result = MobileArea::api("15013883804");

        print_r($result);
        exit;
    }

    public function smsAction(){
        /*
        不具备牛创客身份通知    SMS_94645029    
        不具备牛达人身份通知    SMS_94730034    
        不具备创客身份通知    SMS_94680027    
        牛商牛店下架通知    SMS_94785035  您好！您提供的牛店信息审核未通过，请重新完善信息。下架原因：${content}  
        牛商牛店审核不通过通知    SMS_94695022 您好！您分享${name}为牛商，资料审核未通过，请重新完善信息。未通过原因：${content} 
        牛商牛店审核通知    SMS_94820021     恭喜您，您分享${name}为牛商，资料审核已通过。
         */
        /*$result = SmsApi::api([
                "param"=>json_encode([
                        "name"=>"庄秋敏"
                    ],JSON_UNESCAPED_UNICODE),
                "mobile"=>"15013883804",
                "code"=>"SMS_94820021",
            ]);
        var_dump($result);
        if($result)
            echo "发送成功";
        else
            echo "发送失败";*/

        /*$result = SmsApi::api([
                "param"=>json_encode([
                        "name"=>"庄秋敏",
                        "content"=>"身份有误",
                    ],JSON_UNESCAPED_UNICODE),
                "mobile"=>"15013883804",
                "code"=>"SMS_94695022",
            ]);
        var_dump($result);
        if($result)
            echo "发送成功";
        else
            echo "发送失败";*/

        /*$result = SmsApi::api([
                "param"=>json_encode([
                        "content"=>"身份有误",
                    ],JSON_UNESCAPED_UNICODE),
                "mobile"=>"15013883804",
                "code"=>"SMS_94785035",
            ]);
        var_dump($result);
        if($result)
            echo "发送成功";
        else
            echo "发送失败";*/

        /*$result = SmsApi::api([
                "param"=>"{}",
                "mobile"=>"15013883804",
                "code"=>"SMS_94680027",
            ]);
        var_dump($result);
        if($result)
            echo "发送成功";
        else
            echo "发送失败";*/

        /*$result = SmsApi::api([
                "param"=>"{}",
                "mobile"=>"15013883804",
                "code"=>"SMS_94730034",
            ]);
        var_dump($result);
        if($result)
            echo "发送成功";
        else
            echo "发送失败";*/

        $result = SmsApi::api([
                "param"=>"{}",
                "mobile"=>"15013883804",
                "code"=>"SMS_94645029",
            ]);
        var_dump($result);
        if($result)
            echo "发送成功";
        else
            echo "发送失败";
    }

    /**
     * 重新初始化抢购商品redis
     * @Author   zhuangqm
     * @DateTime 2017-09-23T11:47:52+0800
     * @return   [type]                   [description]
     */
    public function iniproductbuyredisAction(){

        $ProProductBuyModel = Model::ins("ProProductBuy");

        $ProductBuyRedis = Model::Redis("ProProductBuy");

        $list = $ProProductBuyModel->getList([],"*");

        foreach($list as $k=>$v){
            $productid = $v['productid'];
            $ProductBuyRedis->del($productid); // 以productid作为主键
            $ProductBuyRedis->insert($productid,$v);
        }

        echo "OK";
    }

}
