<?php
namespace app\api\controller\Demo;
use app\api\ActionController;

use app\lib\Db;

use app\lib\Model;

class DbController extends ActionController
{
    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }   

    /**
     * 获取单条数据Demo
     * @Author   zhuangqm
     * @DateTime 2017-02-25T10:20:39+0800
     * @return   [type]                   [description]
     */
    public function getRowAction(){

        $row = Db::Table("User")->getRow(["id"=>1]);
        print_r($row);

        $row = Db::Table("User")->getRow(["id"=>1],"*");
        print_r($row);

        $row = Db::Table("User")->getRow(["id"=>1],"name");
        print_r($row);
        
        exit;
    }

    /**
     * 获取多条数据demo
     * @Author   zhuangqm
     * @DateTime 2017-02-25T10:34:35+0800
     * @return   [type]                   [description]
     */
    public function getListAction(){

        $list = Db::Table("User")->getList([]);
        print_r($list);

        $list = Db::Table("User")->getList(["name"=>"thinkphp_test"],"id,name","id desc");
        print_r($list);

        //limit 2
        $list = Db::Table("User")->getList(["name"=>"thinkphp_test"],"id,name","id desc",2);
        print_r($list);

        exit;
    }

    //写入数据
    public function insertAction(){

        $id = Db::Table("User")->insert([
                "name"=>"testaaaa",
                "sex"=>"男"
            ]); 

        echo "写入ID：".$id;
    }

    /**
     * 修改数据
     * @Author   zhuangqm
     * @DateTime 2017-02-25T10:45:28+0800
     * @return   [type]                   [description]
     */
    public function updateAction(){
        echo "修改前数据：<br>";
        $row = Db::Table("User")->getRow(["id"=>1]);
        print_r($row);

        //修改数据
        Db::Table("User")->update(["name"=>"db-update-".time()],["id"=>1]);

        echo "修改后数据：<br>";
        $row = Db::Table("User")->getRow(["id"=>1]);
        print_r($row);
    }

    /**
     * 删除数据
     * @Author   zhuangqm
     * @DateTime 2017-02-25T10:47:40+0800
     * @return   [type]                   [description]
     */
    public function deleteAction(){
        echo "删除前数据：<br>";
        $row = Db::Table("User")->getRow(["id"=>8]);
        print_r($row);

        //删除数据
        Db::Table("User")->delete(["id"=>8]);

        echo "删除后数据：<br>";
        $row = Db::Table("User")->getRow(["id"=>8]);
        print_r($row);
    }

    /**
     * mongodb的操作
     * @Author   zhuangqm
     * @DateTime 2017-02-25T11:29:52+0800
     * @return   [type]                   [description]
     */
    public function mongoAction(){
        var_dump(Db::Table("DemoMG")->insert([
                "name"=>"庄秋敏",
                "sex"=>"男",
            ]));
        exit;
    }

    //测试事务
    public function transAction(){

        $obj = Db::Table("CusFlow");

        //开启事务
        $obj->startTrans();
        try{
            
            $obj->insert([
                    "id"=>"12",
                    "customerid"=>"12",
                    "addtime"=>date("Y-m-d H:i:s"),
                ]);

            $obj->insert([
                    "id"=>"11",
                    "customerid"=>"11",
                    "addtime"=>date("Y-m-d H:i:s"),
                ]);
            // 提交事务
            $obj->commit();   
            echo "OK"; 
        } catch (\Exception $e) {
            // 回滚事务
            $obj->rollback();
            echo "ERROR";
        }
        exit;
    } 

    //测试事务
    public function trans1Action(){

        $obj = Db::Table("CusFlow");

        //开启事务
        $obj->startTrans();
        try{
            
            Model::new("Flow.AmoFlow")->test();
            Model::new("Flow.Flow")->test();
            // 提交事务
            $obj->commit();   
            echo "OK"; 
        } catch (\Exception $e) {
            // 回滚事务
            $obj->rollback();
            echo "ERROR";
        }
        exit;
    } 


    //测试事务
    public function transtestAction(){

        $obj = Db::Table("CusFlow");

        $amount = Db::Table("AmoAmount");

        //开启事务
        $obj->startTrans();
        try{
            
            $obj->insert([
                    "id"=>"17",
                    "customerid"=>"17",
                    "addtime"=>date("Y-m-d H:i:s"),
                ]);

            $cash = 13;

            $amount->update("cashamount=cashamount-".$cash,['id'=>1]);
            
            // 提交事务
            $obj->commit();   
            echo "OK"; 
        } catch (\Exception $e) {
            // 回滚事务
            $obj->rollback();
            echo "ERROR";
        }
        exit;
    } 
}
