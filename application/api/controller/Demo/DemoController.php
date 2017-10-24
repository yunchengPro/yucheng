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
//use \think\Log;

use \think\Exception;


use app\lib\Model;

use app\lib\Pinyin;

use app\lib\Log;


class DemoController extends ActionController
{
    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    public function indexAction(){
        /*$config = Config::get("webview");
        print_r($config);
        $config = Config::get();
        print_r($config);*/
        //echo APP_PATH;
        //throw new \think\Exception('异常消息', 100006);
        /*echo "@@@@";
        print_r($this->params);
        echo "@@@@";*/
        return $this->json("200",[
                "info"=>"hello welcome to nnh",
            ]);
    }

    public function getDemoTestAction(){
        return $this->json("200",[
                "info"=>"getDemoTest welcome to nnh",
            ]);
    } 

    public function dbdemoAction(){
        $db = thinkDb::connect("nnh_db");
        $result = $db->query('select * from user where id=:id',['id'=>1]);
        print_r($result);
        echo "---------";
        var_dump($db->execute('insert into user (id, name) values (:id, :name)',['id'=>4,'name'=>'thinkphp_test']));
        echo "---------";
        var_dump($db->query('select * from user where 1=1',[]));
        
        exit;
    }

    public function testAction(){

        /**
         * 获取所有输入参数
         */
        print_r($this->param);

        /**
         * 获取模块内的配置文件
         */
        print_r(Config::get("code"));

        /**
         * 获取公用的配置文件
         */
        print_r(Config::get("testconfig"));

        print_r(Config::get("adminconfig"));
        /**
         * 数据库定义类
         */
        
        $User = Db::Table("User");
        //echo $User->usertest()."-----";

        /**
         * 数据库操作
         */
        echo "<br>-------数据库操作-------<br>";
       
        echo "<br>-------数据库操作--end-------<br>";
        /**
         * Model类操作
         */
    	/*ModelDemo::demofunc([
    			"id"=>"123",
    			"name"=>"nnh",
    		]);*/

        /**
         * 已json的数据方式返回
         */
    	return $this->json("200",[
                "name"=>"nnh",
                "list"=>[
                    "0"=>[
                        "name"=>"demo",
                        "photo"=>"photo_url",
                    ],
                    "1"=>[
                        "name"=>"demo1",
                        "photo"=>"photo_url1",
                    ],
                ],
                "user"=>$item,
            ]);
    }

    /**
     * redis操作demo
     * @Author   zhuangqm
     * @DateTime 2017-02-22T14:02:05+0800
     * @return   [type]
     */
    public function redisdemoAction(){
        /**
         * redis以hash数据存储方式为主
         * @var [type]
         */
        $useritem = Redis::ins("user")->h_get(1);
        
        //写入
        $key = 101;
        $data = array("id"=>"102","title"=>"商品测试2");
        Redis::ins('product')->h_insert($key,$data);

        //更新
        $key = 101;
        $data = array("title"=>"商品测试33333");
        Redis::ins('product')->h_update($key,$data);

        //获取单条记录
        $key = 101;
        $result = Redis::ins('product')->h_get($key);

        //获取多条记录
        $key = "101,102,103,104";
        $result = Redis::ins('product')->h_gets($key);
        
        $key = array(101,102,103,104);
        $field = "title"; //或者array("title","tag")
        $result = Redis::ins('product')->h_gets($key,$field);



        Redis::close();

        /**
         * 已json的数据方式返回
         */
        return $this->json("200",[
                "useritem"=>$useritem,
            ]);
    }

    /**
     * mongodb的操作demo
     * @Author   zhuangqm
     * @DateTime 2017-02-22T14:58:43+0800
     * @return   [type]
     */
    public function mongodbdemoAction(){

        $obj = Model::Mongo("BusBusinessInfo");

        print_r($obj->getRow(["id"=>1]));

        exit;

        //初始对象
        $MongoDbOBJ = MongoDb::ins("product");

        //写入数据
        $data = array("name"=>"张三","sex"=>"男");
        MongoDb::ins("user")->insert($data);
        /*
        //更新数据
        $where  = array("_id"=>"1450255361449");
        $update = array("name"=>"zhangsan_new");
        $result = MongoDb::ins("product")->update($where,$update);
        var_dump($result);

        //mongodb操作--获取单条数据
        echo "======查询所有字段======<br>";
        $query = array("_id"=>'1450255878664');
        $result = MongoDb::ins("product")->findOne($query);
        print_r($result);
        echo "======查询指定字段======<br>";
        $query = array("name"=>"zhangsan_new");
        $result = MongoDb::ins("product")->findOne($query,"sex");
        print_r($result);

        //mongodb操作--获取多条数据
        $query  = array("_id"=>'1450255878664');
        $result = MongoDb::ins("product")->find($query,'name,sex',['age' => -1],0,10);
        print_r($result);
        $query  = array("name"=>'zhangsan');
        $result = MongoDb::ins("product")->find($query,'name,sex',['age' => -1],0,10);
        print_r($result);

        */


        MongoDb::close();

        return $this->json("200");
    }

    /**
     * 发送短信demo
     * @Author   zhuangqm
     * @DateTime 2017-02-24T10:10:52+0800
     * @return   [type]                   [description]
     */
    public function sendsmsdemoAction(){
        $code = 200;
        /*if(Sms::send('15013883804',['8899','10']))
            $code = '2001';*/

        return $this->json($code);
    }


    /**
     * 日志操作demo
     * @Author   zhuangqm
     * @DateTime 2017-02-24T11:51:18+0800
     * @return   [type]                   [description]
     */
    public function logAction(){
        Log::record('record-测试日志信息，这是警告级别','notice');
        Log::write('write-测试日志信息，这是警告级别','notice');

        return $this->json("200");
    }


    public function test11Action(){
        $total_fee = 0.01;
        Model::new("Amount.Amount")->add_cashamount([
                                                "userid"=>1,
                                                "amount"=>EnPrice($total_fee),
                                                "usertype"=>"2",
                                                "orderno"=>'NNH20170308140431612045',
                                                "flowtype"=>1,
                                                "role"=>1,
                                                "tablename"=>"AmoFlowCusCash",
                                            ]);

        echo "ok";
    }

    public function test2Action(){

        // $row = Model::ins("AmoAmount")->getRow(["id"=>1]);
        // print_r($row);
        // exit;
        // 
        $redis = Db::Redis("AmoAmount");

        var_dump($redis->hincrby("1","cashamount","-10"));
        exit;
    }

    /*
    mongodb操作的测试
     */
    public function test3Action(){
        $user_mongo = Model::Mongo("User");
        $where = ['sex' => "男"];
       
        //$data = array("id"=>"3","name"=>"张三","sex"=>"男");
        // $result = $user_mongo->getRow();

        // print_r($result);

        // $result = $user_mongo->getList(["id"=>2]);

        // print_r($result);
        
        print_r($user_mongo->delete(["id"=>'1']));


        $result = $user_mongo->pageList(['sex' => "男"]);

        print_r($result);
        //echo $user_mongo->getCount(['sex' => "男"]);
    }

    public function test4Action(){
        print_r(Model::new("Customer.BullBus")->updateExamStatus([]));
    }


    public function areaAction(){

        $areaOBJ = Model::ins("SysArea");

        $area = [];
        
        $pagesize = 50;
        $page     = 1;

        $count    =0;
        while(true){

            $list = $areaOBJ->getList("level=2","id,areaname as name","id asc",$pagesize,($page-1)*$pagesize);
            $page+=1;

            foreach($list as $k=>$v){

                $pin = substr(strtoupper(Pinyin::encode($v['name'])),0,1);
                $v['ucfirst'] = $pin;
                $area[$pin][] = $v;
                $count+=1;
            }
            if(count($list)==0 || count($list)<$pagesize)
                break;
        }
        ksort($area);
        // $newarea = [];
        //  $count    =0;
        // foreach($area as $k=>$v){
        //     foreach($v as $value){
        //         $newarea[] =$value;
        //         $count+=1;
        //     }
            
        // }
        echo "======---$count---======";
        echo json_encode($area,JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function pinAction(){
        echo strtoupper(Pinyin::encode("深圳市"));
    }

    public function test123Action(){
        $amountModel = Model::ins("AmoAmount");

        $amountModel->startTrans();
        try{
           
            
            Model::ins("AmoAmount")->insert(["id"=>1]);

            
        } catch (\Exception $e) {
            //print_r($e);
            Log::add($e,__METHOD__);
            //return ["code"=>"30004"];
        }
    }

    public function test5Action(){

        Model::ins("AmoAmount")->insert(["id"=>'']);
    }

    public function actlimitAction(){

        if(Model::new("Sys.ActLimit")->check("1234",3)){
            echo "限制次数达到3次了";
        }else{
            echo "ok";
        }
    }

    public function aaAction(){
        Model::new("Demo.Demo1")->test("a");
        Model::new("Demo.Demo1")->test("b");

        Model::new("Demo.Demo2")->test("c");

        Model::new("Demo.Demo1")->show();
    }

    public function versionAction(){

        // 判断是否1.0.0版本
        if($this->Version("1.0.0")){

            //业务处理
        }

        // 判断是否1.0.1版本
        if($this->Version("1.0.1")){

            //业务处理
        }

        return $this->json("200",$data);
    }
}
