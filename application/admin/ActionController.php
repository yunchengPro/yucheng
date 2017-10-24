<?php
namespace app\admin;

use app\ComController;
use \think\Config;
use \think\Session;
use app\form\FormToken;

use app\lib\Model;

class ActionController extends ComController
{   
    public $business_roleid = null;
    public $username = null;
    public $businessid = null;

    public function __construct(){

        parent::__construct();

        $this->check_request();

        $this->business_roleid = Session::get('business_roleid');
        $this->username = Session::get('username');
        $this->businessid = Session::get('businessid');
        $this->customerid = Session::get('customerid');
        $path = "/".strtolower($this->getPath());

        Session::set('_path',$path);

        if(empty(Session::get('businessid')) && substr($path,0,6)!='/login' && substr($path,0,6)!='/setPwd'){
            $url = '/login';
            $this->showErrorPage('请登录','/login',5,1);
        }

    }

    /**
     * [Btoken 生成token]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-15T15:51:25+0800
     */
    protected function Btoken($param){
        return FormToken::formtoken($param);
    } 

    /**
     * [Ctoken 校验token]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-15T15:52:53+0800
     */
    protected function Ctoken(){

        return FormToken::check_formtoken($this->getParam('_tokenname'),$this->getParam('_tokenvalue'));
    }

    public function getMethod(){
        return $this->request->method();
    }

    public function isPost(){
        if(strtolower($this->getMethod())=='post')
            return true;
        else
            return false;
    }

    /*
    * 获取当前url请求相对路径
    * return 如：user/index/detail
    */
    public function getPath(){

        $Path_arr = explode("/",$this->request->path());
        $Module     = isset($Path_arr[0])&&$Path_arr[0]!=''?ucfirst($Path_arr[0]):"Index";
        $Controller = isset($Path_arr[1])&&$Path_arr[1]!=''?ucfirst($Path_arr[1]):"Index";
        $Method     = isset($Path_arr[2])&&$Path_arr[2]!=''?$Path_arr[2]:"index";

        return ucfirst($Module).'/'.$Controller.'/'.$Method;
        //return $this->request->path();
    }

    /*
    * 跳到视图页面
    * $data = array() 数组格式 带到视图页面的参数
    */
    public function view($data = array()){
        $data['full_page'] = $this->isPost()?false:true;
        if(isset($data['total'])){
            $data['page']       = $this->getParam('page',1);
            $data['pagesize']   = $this->getParam('pagesize',20);
        }

        $data['sort_by'] = $this->getParam('sort_by');
        $data['sort_order'] = $this->getParam('sort_order');

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
    public function showSuccess($msg,$url='',$time=1){

        $success = true;
        if(empty($url)) {
            $js = 'parent.parent.window.location.reload();';
        }else{
            $js ='parent.parent.window.location.href="'.($url!=''?$url:"/".$this->getPath()).'";'; 
        }
        $extra_js = '
        if(parent.parent.TOP_FLAG!=1){  
            '.$js.'
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
        exit();
    }
    /**
     * [showError 错误提示信息]
     * @return [type] [description]
     * @author [ISir] <[<email 673638498@qq.com>]> 2016-08-24                 
     */
    public function showError($msg,$url='',$time=2){
        $success = false;
        
        $this->showMessage($msg,$succes,$url,$time);

        $extra_js = 'parent.parent.layer.close(parent.parent.layer.getFrameIndex(parent.window.name));';
        $extra_js = 'parent.window.location.reload(parent.layer.getFrameIndex(window.name));';
        $this->showMessage($msg,$succes,$url,$time,$extra_js);

        //$extra_js = 'parent.window.location.reload();parent.parent.layer.close(parent.parent.layer.getFrameIndex(parent.window.name));';
        //$extra_js = '';
        //$this->showMessage($msg,$succes,$url,$time,$extra_js);
        exit();
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
        exit();
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
    public function showMessage($msg,$success=1,$url,$time=2,$extra_js=''){
        
        //$this->_helper->notifyPostDispatch();//执行插件的操作
        if ($this->request->isAjax()){
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
        //exit();
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

    /*
    配置where条件
    array(
        "id"=>"="
        "keyword"=>"like"
        "type"=>"="
        "addtime"=>"times"
        "type"=>"like" like '%searchkeyword%'
    );
    */
    public function searchWhere($set,$where=array()){
        if(!empty($set) && is_array($set)){
            $tmp_where = array();

            foreach($set as $key=>$value){
                if($value!=''){

                    if($value=='times'){
                        if($this->getParam("start_".$key)!='' && $this->getParam("end_".$key)!=''){
                            $tmp_where[$key] = array(
                                    array(">=",$this->getParam("start_".$key)." 00:00:00"),
                                    array("<=",$this->getParam("end_".$key)." 23:59:59"),
                                );
                        }else{
                            if($this->getParam("start_".$key)!='')
                                $tmp_where[$key] = array(">=",$this->getParam("start_".$key)." 00:00:00");
                            if($this->getParam("end_".$key)!='')
                                $tmp_where[$key] = array("<=",$this->getParam("end_".$key)." 23:59:59");
                        }

                    }else if($value=='='){
                        if($this->getParam($key)!='')
                            $tmp_where[$key] = $this->getParam($key);

                    }else if($value=='like'){
                        if($this->getParam($key)!='')
                            $tmp_where[$key] = array($value,"%".$this->getParam($key)."%");
                    }else{
                        if($this->getParam($key)!='')
                            $tmp_where[$key] = array($value,$this->getParam($key));
                    }
                }

            }

            if(!empty($tmp_where) && !empty($where)){

                foreach($where as $key=>$value){
                    if(isset($tmp_where[$key])){
                        $tmp_arr = array();
                        $tmp_arr[] = is_array($tmp_where[$key])?$tmp_where[$key]:array("=",$tmp_where[$key]);
                        $tmp_arr[] = is_array($value)?$value:array("=",$vaule);
                        $tmp_where[$key] = $tmp_arr;
                    }else{
                        $tmp_where[$key] = $value;
                    }
                }
                return $tmp_where;
            }else{
                if(!empty($tmp_where))
                    return $tmp_where;
                if(!empty($where))
                    return $where;
            }
            return $tmp_where;
        }else{
            if(!empty($where))
                return $where;
            else
                return array();
        }

    }

    public function order($order,$sort){
        $sort_by    = $this->getParam('sort_by',$order);
        $sort_order = $this->getParam('sort_order',$sort);
        
        return $sort_by." ".$sort_order;
    }


    /**
     * 校验请求
     * @Author   zhuangqm
     * @DateTime 2017-06-29T10:13:04+0800
     * @return   [type]                   [description]
     */
    protected function check_request(){

        if(!$this->isPost())
            return true;

        $ApiRequestRedis = Model::Redis("ApiRequest");
        
        //1 请求安全判断
        $apikey = Config::get("key");

        $path = "/".strtolower($this->getPath());

        //3 判断重复提交
        if(strpos($path,"policy")===false){
            
            $check_key = $this->returnKey($path);
            if($ApiRequestRedis->exists($check_key)){
                // 已存在
                $lasttime = $ApiRequestRedis->get($check_key);
                if((microtime(true)-$lasttime)<1){
                    // 1秒内的请求 都视为重复提交
                    $this->showError("不能重复操作");
                    exit;
                }
            }

            $ApiRequestRedis->set($check_key,microtime(true),10);
            
        }

        
    }

    protected function returnKey($path=''){
        $tmp = $this->params;

        if($path!='')
            $tmp['path'] = $path;

        unset($tmp['_tokenname']);
        unset($tmp['_tokenvalue']);

        $keystr = '';
        foreach($tmp as $k=>$v){
            $keystr.=$k."=".$v."&";
        }
        $tmp = null;
        return BIND_MODULE.":".md5($keystr);
    }
}
