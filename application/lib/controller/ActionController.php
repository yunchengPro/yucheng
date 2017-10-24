<?php
namespace app\lib\controller;

use think\Controller;
use \think\Config;
use \think\Session;

class ActionController extends Controller
{   

    public $params; 
    
    public function __construct(){
        parent::__construct();

        /**
        * 初始化所有传入参数
        */

        $this->params = $this->_addslashes($this->request->param());
    }


    /*
    * 转json数据
    */
    public function json($code='400',$data=array(),$msg=""){

        if($code=='200'){
            $msg = $msg!=''?$msg:"success";
        }else{
            if($msg==''){
                $code_msg = Config::get("code");
                $msg = $code_msg[$code];
            }
        }

        $return_arr = array(
                "code"=>$code,
                "msg"=>$msg,
                "data"=>$data
            );

        $return_str = json_encode($return_arr,JSON_UNESCAPED_UNICODE);

        $return_str = preg_replace('/:([^"^\[^\/]*?)(,|})/i',':"${1}"${2}',$return_str);
        $return_str = preg_replace('/"null"/i','""',$return_str);

        //Sysparam::$return_code = $code;
        //Sysparam::$return_msg = $msg;

        return $return_str;
    }

    //仿注入处理
    public function _addslashes($param_array){

        if(is_array($param_array)){
            foreach($param_array as $key=>$value){
                if(is_string($value))
                    $param_array[$key] = addslashes(trim($value));
            }
        }else{

            $param_array = addslashes($param_array);
        }

        return $param_array;
    }

    /*
    * 获取当前请求数据
    * $key 获取的参数值
    * $default_value 获取参数设置的默认值
    */
    public function getParam($key,$default_value=''){
        /*
        if(empty($default_value))
            return $this->request->input($key);
        else
            return $this->request->input($key,$default_value);  
        */
        if(isset($this->params[$key]))
            return $this->params[$key];
        else
            return $default_value;
    }

    /*
    * 获取当前url请求相对路径
    * return 如：user/index/detail
    */
    public function getPath(){

        $Path_arr = explode("/",$this->request->path());
        $Module     = isset($Path_arr[0])&&$Path_arr[0]!=''?$Path_arr[0]:"Index";
        $Controller = isset($Path_arr[1])&&$Path_arr[1]!=''?$Path_arr[1]:"index";
        $Method     = isset($Path_arr[2])&&$Path_arr[2]!=''?$Path_arr[2]:"index";

        return ucfirst($Module).'/'.$Controller.'/'.$Method;
        //return $this->request->path();
    }

    /*
    * 跳到视图页面
    * $data = array() 数组格式 带到视图页面的参数
    */
    public function view($data = array()){
        return view($this->getPath(), (array)$data);
    }

    /*
    * 获取视图路径
    */
    public function getViewUrl(){
        
        return ucfirst($this->param['_apiname']);
    }

    /*
    * 获取session值
    * $key 获取的key值
    * $value 如果key值不存在，则默认返回$value
        $this->session('userid') //登录用户ID
        $this->session('agentid') //登录用户的代理商ID
        $this->session('loginname') //登录用户名（登录时用的名称）
        $this->session('username') //用户姓名
        $key
    */
    public function session($key,$value=''){

        //return $this->request->session()->get($key);
        //return session($key);
        
        if(Session::has($key))
            return Session::get($key);
        else
            return $value!=''?$value:'';
        
    }

    /**
     * [showSucsess 成功提示信息]
     * @return [type] [description]
     * @author [ISir] <[<email 673638498@qq.com>]> 2016-08-24
     */
    public function showSuccess($msg,$url,$time=1){

        $success = true;
        $extra_js = '
        if(parent.parent.TOP_FLAG!=1){  
            parent.parent.window.location.reload();
            parent.parent.layer.close(parent.parent.layer.getFrameIndex(parent.window.name)); 
        }else{ 
            if(parent.TOP_FLAG!=1){ 
                parent.window.location.reload(); 
                parent.layer.close(parent.layer.getFrameIndex(parent.window.name)); 
            }else{
                //window.location.href="'.($url!=''?$url:"/".$this->getPath()).'"; 
                layer.close(layer.getFrameIndex(window.name));
            }
        } ';
        //$extra_js = 'alert(13);';
        $this->showMessage($msg,$success,$url,$time,$extra_js);
        
    }
    /**
     * [showError 错误提示信息]
     * @return [type] [description]
     * @author [ISir] <[<email 673638498@qq.com>]> 2016-08-24                 
     */
    public function showError($msg,$url,$time=2){
        $success = false;
        
        $this->showMessage($msg,$succes,$url,$time);

        $extra_js = 'parent.parent.layer.close(parent.parent.layer.getFrameIndex(parent.window.name));';
        $this->showMessage($msg,$succes,$url,$time,$extra_js);

        //$extra_js = 'parent.window.location.reload();parent.parent.layer.close(parent.parent.layer.getFrameIndex(parent.window.name));';
        //$extra_js = '';
        //$this->showMessage($msg,$succes,$url,$time,$extra_js);

    }
    /**
     * [showPage 新开页面]
     * @param  [type]  $msg  [description]
     * @param  [type]  $url  [description]
     * @param  integer $time [description]
     * @return [type]        [description]
     */
    public function showPage($url,$msg,$time=2){
        $success = true;
        $extra_js  = "window.parent.parent.location.href='{$url}'";
        $this->showMessage($msg,$succes,$url,$time,$extra_js);
    }
    /**
     * [showMessage 提示信息管理]
     * @param  [type]  $msg      [description]
     * @param  integer $success  [description]
     * @param  [type]  $url      [description]
     * @param  integer $time     [description]
     * @param  [type]  $extra_js [description]
     * @return [type]            [description]
     */
    public function showMessage($msg,$success=1,$url,$time=2,$extra_js){
        
        //$this->_helper->notifyPostDispatch();//执行插件的操作
        if ($this->request->ajax()){
            if ($continue){
                echo json_encode(array('msg'=>$msg,
                                  'result'=>$success?"success":"error",
                                  'code'=>$success,
                                  'extra_js'=>$extra_js));
                return ;
            }else{
                die(json_encode(array('msg'=>$msg,
                                  'result'=>$success?"success":"error",
                                  'code'=>$success,
                                  'extra_js'=>$extra_js)));
            }
        }

        if(!empty($url)){
            $extra_js .= "target.location.href='{$url}'";
        }

        //callback为显示信息，并关闭后才执行回调
        $callback = '';
        //extra_js是不等待信息关闭就执行的程序
        if ($extra_js == ''){
            if ($url == '' || (strpos($url,'reload') !== false)){
                $extra_js .= "
                if (target.listTable && target.listTable.is_searched()){
                    target.listTable.search();
                    target.YSL.Popup.closeAll();
                }   else    {
                target.location.reload();
                }";
            }   elseif ($url == 'back'){
                $extra_js .= "target.history.go(-1);";
            }   elseif ($url == 'donothing'){
                $extra_js = "";
            }   else {

                //$extra_js .= "target.location.href='{$url}'";
                $extra_js .= "target.location.href='{$url}'";
            }

            if ($success == false && $url == ''){//显示失败结果时，不做callback操作  @20120627，因为有些地方失败时，需要显示tips而且返回的
                $extra_js = '';
            }
        }
        
        if ($url == 'closepopup'){
            $callback = '
                ,function(){
                    if (window.parent != window.self){
                        parent.YSL.Popup.closeAll();
                    }
                }
            ';
        }
        $html =
        '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
        <head>
            <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
            <title></title>
            <script type="text/javascript" src="/newui/lib/jquery/1.9.1/jquery.min.js"></script>
            <script type="text/javascript" src="/js/ysl.js"></script>
            <script>
            
            if (top.DLERP_TOP_PAGE){
                    var target = window;
                    while(target.parent != top){
                        target = target.parent;
                    }
                }   else    {
                    if(top.TOP_FLAG==1)
                        var target = window;
                    else
                        var target = top;
                }
            </script>
        </head>

        <body>
            <script type="text/javascript">
                var tip = new top.YSL.Tip("'.$msg.'",'.($success ? '1' : '2').','.$time.$callback.');
                '.$extra_js.'
            </script>
        </body>
        </html>';
            if ($continue){
                echo  $html;
                return ;
            }else{
                die($html);
            }
            //var_dump($html);
        echo $html;
    }



     /**
     * 错误输出函数
     * @param string $content 错误输出信息
     */

    public  function showErrorPage($content,$url='',$time=2,$gototop=0){

        if(empty($url))
            $url=$this->getPath();
        $stringHtml = '<meta charset="utf-8">
        <script language="javascript" type="text/javascript"> 
        var i = '. $time .'; 
        var intervalid; 
        intervalid = setInterval("fun()", 1000); 
        function fun() { 
            if (i == 0) { 
                clearInterval(intervalid); 
            } 
            document.getElementById("mes").innerHTML = i; 
            i--; 
        } 
        </script> 
        <div style="width:500px;height:auto;padding:10px;border:1px solid #CCC;margin:140px auto;text-align:center;line-height:30px;">';
        $stringHtml .= '错误提示信息:<br />';
        $stringHtml .= '<font color="red" style="font-weight:bold;font-size:20px;">';
        $stringHtml .= $content;
        $stringHtml .= '</font>';
        $stringHtml .= '<br/><span id="mes">'.$time.'</span>...后跳转';
        
        if(!empty($url)){
            if($gototop==0)
                $stringHtml .= '&nbsp;&nbsp;<a href="'.$url.'">点击跳转</a>';
            else
                $stringHtml .= '&nbsp;&nbsp;<a href="javascript:top.window.location.href="'.$url.'";">点击跳转</a>';
        }else{
            $stringHtml .= '&nbsp;&nbsp;<a href="javascript:;" onclick="window.history.back()">点击跳转</a>';
        }
        $stringHtml .= '</div>';
        echo $stringHtml;
        if(!empty($url)){

            if($gototop==0)
                header("refresh:".$time.";url=".$url);
            else
                echo '<script type="text/javascript">top.window.location.href="'.$url.'";</script>';

        }else{
            echo '<script type="text/javascript">history.back();</script>';
        }

        exit();

    }

    /**
     * [error 保存]
     * @param  [type]  $content [description]
     * @param  [type]  $url     [description]
     * @param  integer $time    [description]
     * @return [type]           [description]
     */
    public  function showSuccessPage($content,$url='',$time=1){
        if(empty($url))
            $url=$this->getPath();
         $stringHtml = '<meta charset="utf-8">
        <script language="javascript" type="text/javascript"> 
            var i = '. $time .'; 
            var intervalid; 
            intervalid = setInterval("fun()", 700); 
            function fun() { 
                if (i == 0) { 
                    clearInterval(intervalid); 
                } 
                document.getElementById("mes").innerHTML = i; 
                i--; 
            } 
            </script> 
        <div style="width:500px;height:auto;padding:10px;border:1px solid #CCC;margin:140px auto;text-align:center;line-height:30px;">';
        $stringHtml .= '成功提示信息:<br />';
        $stringHtml .= '<font color="green" style="font-weight:bold;font-size:20px;">';
        $stringHtml .= $content;
        $stringHtml .= '</font>';
        $stringHtml .= '<br/><span id="mes">'.$time.'</span>...后跳转';
        $stringHtml .= '&nbsp;&nbsp;<a href="'.$url.'">点击跳转</a>';
        $stringHtml .= '</div>';
        echo $stringHtml;
        header("refresh:".$time.";url=".$url);

        exit();
    }
}
