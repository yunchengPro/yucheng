<?php
// +----------------------------------------------------------------------
// |  [ 导出Excel ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2016-04-11
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Sys;
use app\superadmin\ActionController;

use \think\Config;

use \think\Session;


class ExcelController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    public function exportAction() {
        $export_url = $this->getParam("export_url");
        $excel_key = $this->getParam("excel_key");


        if($export_url=='' || $excel_key==''){
            echo "参数有误";
            exit;
        }

        $excel_config = Config::get("excel");

        $export_flag = true;

        $excel_key_value = Session::get($excel_key);
        if($excel_key_value!=0 ){
            if( ((time()-substr($excel_key,4,10))<600)){
                $export_flag = false;
                $tmp = explode("#@#",$excel_key_value);
                $this->view->filepath   = $tmp[0];
                $this->view->filename   = $tmp[1];
            }else{
                $excel_key = $this->getexcelkey();
            }
        }

        $query_arr = $_SERVER['REQUEST_METHOD']=="GET"?$_GET:$_POST;

        $viewData = [];
        $viewData['export_flag']    = true; // $export_flag
        $viewData['query_str']      = json_encode($query_arr,JSON_UNESCAPED_UNICODE);//$_SERVER["QUERY_STRING"];
        $viewData['export_url']     = $export_url; //获取内容数据路径
        $viewData['excel_key']      = $excel_key; //获取内容数据路径
        $viewData['excel_domain']   = $excel_config['excel_domain']; //excel域名
        $viewData['excel_url']      = $excel_config['excel_url']; //生成excel，提交数据的路径
        $viewData['getexcel_url']   = $excel_config['getexcel_url']; //获取excel文件路径

        return $this->view($viewData);
    }

    public function getexcelkeyAction(){
        echo $this->getexcelkey();
        exit;
    }

    public function getexcelkey(){
        //$export_url = $this->_request->getParam("export_url");
        $excel_key  = $this->getParam("excel_key");
        //有效时间10分钟，10分钟内不能重复导出
        if($excel_key=='' || ($excel_key!='' && ((time()-substr($excel_key,4,10))>=600))){
            $excel_key = rand(1000,9999).time().rand(1000,9999);
            Session::set($excel_key,0);
        }
        return $excel_key;
    }

    public function endexportAction(){
        $excel_key  = $this->getParam("excel_key");
        $filepath   = $this->getParam("filepath");
        $filename   = $this->getParam("filename");
        if($excel_key!='' && $filepath!='')
            Session::set($excel_key,$filepath."#@#".$filename);
            //$_SESSION[$excel_key]=$filepath."#@#".$filename;
        exit;
    }

}
