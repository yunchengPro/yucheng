<?php
namespace app\admin\controller\Demo;
use app\admin\ActionController;

use \think\Config;

use app\lib\Db;

class IndexController extends ActionController
{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    
    //
    public function indexAction(){
        print_r($this->params);
        $arr = array(
                "code"=>"200",
                "msg"=>"操作成功",
                "data"=>array(
                        "test"=>"1111",
                        "aaa"=>"33333",
                    ),
            );
        return $this->json($arr);
    }

    //简约写法
    public function simplistAction(){
        //获取请求参数
        //print_r($this->params);
        //
        $name = $this->params['name'];

        $where = "1=1";
        $field = "*";
        //获取列表数据
        $list = Db::Table("Demo")->pagelist($where,$field,"id desc");

        //print_r($pagelist);
        $viewData = array(
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
            );

        //$this->test($name);

        return $this->view($viewData);
    }

    public function demolistAction(){

        //获取请求参数
        //print_r($this->params);

        //$where = "id in(44,45)";
        if($this->params['keyword']!='')
            $where.=" and f_name like '%".$this->params['keyword']."%'";
        $field = "*";
        $where = array();
        //获取列表数据
        $list = Db::Table("Demo")->pagelist($where,$field,"id desc");

        //print_r($pagelist);
        $viewData = array(
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
            );
        return $this->view($viewData);
    }

    public function addAction(){

        return $this->view();
    }

    /**
     * [addData 添加demo]
     */
    public function addDataAction(){
         return $this->view();
    }

    /**
     * [getPostData 接收添加数据]
     * @return [type] [description]
     */
    public function getPostDataAction(){
        $post = $this->params;
        $data = Db::Table("Demo")->_facade($post);
        //print_r($data);
        if(!Db::Table("Demo")->_validate($data)){
            $this->showError(Db::Table("Demo")->_getError());
        }else{
            Db::Table("Demo")->addData($data);
            $this->showSuccess("操作成功");
        }
    }

    public function editAction(){
        return "123";
    }



    public function showAction(){
        
        //User::test();
        //$users = DB::table('User')->get();

        //$row = Db::Table("User")->getRow();
        $row = Db::Table("Demo")->getRow(array("id"=>27),"id,username as name",array("id"=>"asc"));

        print_r($row);

        //echo "##".$row[0]."###";

        echo "<br>==========<br>";
        //$list = Db::Table("Demo")->getList(array("f_streetname"=>234),"id,f_name",array("id"=>"desc"));

        //print_r($list);
        $insertData = array(
                "f_name"=>"AAAAAAAA",
                "f_mobile"=>"999999",
            );
        //echo "###".Db::Table("Demo")->insert($insertData)."##";
        /*
        echo "@@@@".Db::Table("Demo")->update(array(
                "f_name"=>"BBBBBBBB",
                "f_mobile"=>"7777",
            ),array("id"=>"52"))."@@@@";
            */
        //echo "@@@@".Db::Table("Demo")->delete(array("id"=>"65"))."@@@@";
        //echo "<br>==========<br>";
        //echo "Model操作：<br>";
        //ModelDemo::test();
        //echo "<br>==========<br>";
        //Img();
        //echo "########".ROOT_DIR."#########";
        //echo "当前路径：".$this->getPath()."<br>";
        //echo "当前url：".$this->getUrl()."<br>";
        //echo $this->test;

        $showinfo = "显示的内容1312312";
        
        $viewData = array(
                "showinfo"=>$showinfo,
                "id"=>$this->getParam("id"),
            );
        return $this->view($viewData);
    }

    public function testAction(){

        print_r($this->params);
        echo "==116778899=";
        print_r(Config::get("upload"));
        return '';
    }

    public function imgAction(){
        return response()->view('Demo.index.img')->header('Content-type','image/jpeg');;
        //return response('123<br>123dsfkasdjfasjfd sakldfja s<br>askdfjalksdjfaksldjf a')->header('Content-type','image/jpeg');
    }

    public function tableAction(){
        return $this->view();
    }
}
