<?php
namespace app\sale;

use app\ComController;
use \think\Config;
use \think\Session;
use \think\Cookie;
use app\form\FormToken;

use app\model\User\TokenModel;

use app\lib\Model;

class ActionController extends ComController
{   
    public $business_roleid = null;
    public $username = null;
    public $businessid = null;

    public $visitor_code = null; //每个访问者随机分配一个号当作当前用户的id

    public $openid = null;

    public $userid = null;

    public $userrole = null;

    public $mtoken = null;

    public $_version = "I99.0.0";  // 设置一个足够大的值

    public $_path = '';

    public $login_path = "/index/index/login"; // 登录页面链接

    public $weixin_path = "/sys/weixin/oauthinfo"; // 微信授权链接

    public $is_weixin = false;

    public $checktoken = '';


    public function __construct(){

        parent::__construct();

        $this->check_request();

        //判断当前请求来源 微信 还是其他浏览器
        $this->is_weixin = is_weixin();

        $this->CookieHandle();

        //判断是否要登录
        $check_mtoken_conf = Config::get("checkmtoken");
        if(!empty($check_mtoken_conf[strtolower($this->getPath(true))]))
            $this->check_mtoken();
        
    }

    /**
     * cookie处理
     * @Author   zhuangqm
     * @DateTime 2017-09-05T17:25:57+0800
     */
    protected function CookieHandle(){
        // 获取通用信息
        $this->userid = Cookie::get('customerid');
        $this->mtoken = Cookie::get('mtoken'); //作为一个校验值
        $this->openid = Cookie::get('openid');
        // $this->userrole = Cookie::get("userrole");

        $this->visitor_code = Session::get("visitor_code");

        // echo "=====".$this->userid."======<br>";
        // echo "=====".$this->mtoken."======<br>";
        // echo "=====".$this->openid."======<br>";

        // Cookie::set('userid','');
        // Cookie::set('mtoken','');
        // Cookie::set('openid','');

        if(empty($this->openid) && !empty($this->params['openid'])){

            if(!Model::new("User.Open")->checkOpenid(["openid"=>$this->params['openid']])){
                // 重新走授权
                $this->authurlto();
            }


            $this->openid = $this->params['openid'];
            Cookie::set('openid',$this->params['openid'],3600*24*300);

            /*if($this->params['openuserinfo']){
                $this->params['openuserinfo'] = str_replace('\"', '"', $this->params['openuserinfo']);
                Model::new("User.Open")->addUserOpenInfo(json_decode(urldecode($this->params['openuserinfo']),true));
            }*/

        }

        //查询openid绑定的用户id
        if(!empty($this->openid) && empty($this->userid)){
            $this->userid = Model::new("User.Open")->getUserid(["openid"=>$this->openid]);
            if(!empty($this->userid)){
                $this->mtoken = md5($this->userid.getConfigKey());

                Cookie::set('customerid',$this->userid,3600*24*300);
                Cookie::set('mtoken',$this->mtoken,3600*24*300);
            }
        }
        $path = strtolower($this->getPath(true));
        // 如果当前是微信打开的，就先走授权再继续
        if($this->is_weixin && empty($this->openid) && $path!=$this->weixin_path && $path!='/sys/weixin/urlto'  && $path!='/sys/weixin/infourlto' && $path!=$this->login_path && $path!="/index/index/sendvalicode" && $path!="/index/index/dologin" && $path!="/index/index/bindmobile"  && $path!="/index/index/bindrecomend"){
            /*$redirect_uri = $this->getPath(true)."?".$this->ParaParam($this->params);

            header('Location:'.$this->weixin_path."?redirect_uri=".urlencode($redirect_uri));
            exit;*/
            $this->authurlto();
        }

        if(empty($this->visitor_code)){
            $this->visitor_code = md5((microtime(true)*10000).rand(100,999));
            Session::set("visitor_code",$this->visitor_code);
        }
    }


    protected function authurlto(){
        $redirect_uri = $this->getPath(true)."?".$this->ParaParam($this->params);

        header('Location:'.$this->weixin_path."?redirect_uri=".urlencode($redirect_uri));
        exit;
    }

    /**
     * 判断用户是否登录
     * @Author   zhuangqm
     * @DateTime 2017-02-23T18:29:03+0800
     * @return   [type]                   [description]
     */
    protected function check_mtoken(){

        // 注意，下面填写的小写
        $check_login_path[] = $this->login_path;
        $check_login_path[] = $this->weixin_path;
        $check_login_path[] = "/index/index/dologin";

        if(in_array(strtolower($this->getPath(true)),$check_login_path))
            return true;

        /*// 如果当前是微信打开的，就先走授权再继续
        if($this->is_weixin && empty($this->openid)){
            $redirect_uri = $this->getPath(true)."?".$this->ParaParam($this->params);
            header('Location:'.$this->weixin_path."?redirect_uri=".urlencode($redirect_uri));
            exit;
        }*/

        if(empty($this->userid) || empty($this->mtoken)) {
            $redirect_uri = $this->getPath(true)."?".$this->ParaParam($this->params);
            header('Location:'.$this->login_path."?redirect_uri=".urlencode($redirect_uri));
            exit;
        }

        if(md5($this->userid.getConfigKey())!=$this->mtoken){
            Cookie::set('customerid',"",time());
            Cookie::set('mtoken',"",time());
            $redirect_uri = $this->getPath(true)."?".$this->ParaParam($this->params);
            header('Location:'.$this->login_path."?redirect_uri=".urlencode($redirect_uri));
            exit;
        }

        // 判断用户状态
        $this->check_user(['userid'=>$this->userid]);

    }

    protected function check_user($param){
        $cusItem = Model::ins("CusCustomer")->getRow(["id"=>$param['userid']],"id,enable");

        if(empty($cusItem)){
            $redirect_uri = $this->getPath(true)."?".$this->ParaParam($this->params);
            header('Location:'.$this->login_path."?redirect_uri=".urlencode($redirect_uri));
            exit;
        }

        if($cusItem['enable']==0){
            // 跳到第二步
            header('Location:/Index/Index/bindMobile');
            exit;
        }

        if($cusItem['enable']==2){
            exit("用户已禁用");
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
    public function getPath($prefix=false){
        if($this->_path==''){
            $Path_arr = explode("/",$this->request->path());
            $Module     = isset($Path_arr[0])&&$Path_arr[0]!=''?ucfirst($Path_arr[0]):"Index";
            $Controller = isset($Path_arr[1])&&$Path_arr[1]!=''?ucfirst($Path_arr[1]):"Index";
            $Method     = isset($Path_arr[2])&&$Path_arr[2]!=''?$Path_arr[2]:"index";

            $this->_path = ucfirst($Module).'/'.$Controller.'/'.$Method;
        }

        return ($prefix?"/":"").$this->_path;
        //return $this->request->path();
    }

    /*
    * 跳到视图页面
    * $data = array() 数组格式 带到视图页面的参数
    */
    public function view($data = array()){
        $data['full_page'] = $this->isPost()?false:true;

        $data['publicDomain'] = Config::get("mobiledomain.publicDomain");

        //判断当前访问来源
        if(empty($data['is_weixin']))
            $data['is_weixin'] = $this->is_weixin;

        if(empty($data['userid']))
            $data['userid']    = $this->userid;

        if(empty($data['mtoken']))
            $data['mtoken']    = $this->mtoken;

        if(empty($data['mtoken']))
            $data['openid']    = $this->openid;

        if(empty($data['checktoken']))
            $data['checktoken'] = $this->checktoken;

        $path = $this->getPath();

        $data['UrlPath'] = $path;

        return view($path, (array)$data);
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
       // exit();
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
            return array();
        }

    }

    /**
     * 校验请求
     * @Author   zhuangqm
     * @DateTime 2017-06-29T10:13:04+0800
     * @return   [type]                   [description]
     */
    protected function check_request(){

        //1 请求安全判断
        $apikey = Config::get("key");

        $path = "/".strtolower($this->getPath());

        // 业务数据判断
        if($this->params['cuiid']!=''){
            if(md5($this->ParaParam(array_merge($this->params,["urlpath"=>$path])).$apikey['app_key'])!=$this->params['cuiid'])
                exit("操作有误");
        }

        if($this->isPost()){

          //  $ApiRequestRedis = Model::Redis("ApiRequest");
            
            // 判断重复提交
            if(strpos($path,"policy")===false){
                
                // $check_key = $this->returnKey($path);
                // if($ApiRequestRedis->exists($check_key)){
                //     // 已存在
                //     $lasttime = $ApiRequestRedis->get($check_key);
                //     if((microtime(true)-$lasttime)<1){
                //         // 1秒内的请求 都视为重复提交
                //         //$this->showError("不能重复操作");
                //         exit("不能重复操作");
                //     }
                // }

                // $ApiRequestRedis->set($check_key,microtime(true),10);
                
            }

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

    // 生成路径code
    // 说明 ：urlpath 这个参数一定要传
    // $param 为要加密的数组参数
    // $param = [
    //      "urlpath"=>"/Index/inex/index",
    //      其他参数
    // ]
    // return cuiid
    public function Cuiid($param){
        if($param['urlpath']!=''){

            $apikey = Config::get("key");

            return md5($this->ParaParam($param).$apikey['app_key']);
 
        }else{
            exti("操作有误");
        }
    }


    //将数组转成uri字符串
    protected function ParaParam($param)
    {
        unset($param['cuiid']);
        $buff = "";
        ksort($param);
        foreach ($param as $k => $v){
            $buff .= $k. "=" . stripslashes($v) . "&";
        }
        
        $buff = $buff!=''?substr($buff,0,-1):"";
        return $buff;
    }

    /**
     * 添加校验机制--针对提交页面做一层校验机制
     * @Author   zhuangqm
     * @DateTime 2017-09-07T14:23:38+0800
     * @return   [type]                   [description]
     */
    public function addcheck(){

        //生成一个checktoken
        $this->checktoken = md5($this->visitor_code.(microtime(true)*10000).rand(100,999));

        //redis
        $ApiRequestRedis = Model::Redis("ApiRequest");
            
        // 生成校验值
            
        $check_key = "checktoken:".$this->visitor_code.":".$this->checktoken;
        
        $ApiRequestRedis->set($check_key,md5($check_key.getConfigKey()),1800);
    }

    /**
     * 校验请求是否合法
     * @Author   zhuangqm
     * @DateTime 2017-09-07T14:53:24+0800
     * @return   [type]                   [description]
     */
    public function checktokenHandle(){
        if(!empty($this->params['checktoken'])){
            $ApiRequestRedis = Model::Redis("ApiRequest");
            $check_key = "checktoken:".$this->visitor_code.":".$this->params['checktoken'];
            if($ApiRequestRedis->exists($check_key)){
                //校验是否合法
                $checktoken_value = $ApiRequestRedis->get($check_key);
                $apikey = Config::get("key");
                if(!empty($checktoken_value) && $checktoken_value==md5($check_key.getConfigKey())){
                    $ApiRequestRedis->del($check_key);
                }else{
                    header('Content-type: application/json; charset=utf-8');
                    exit($this->json("408",[],"请求无效，请重新刷新页面"));
                }
            }else{
                header('Content-type: application/json; charset=utf-8');
                exit($this->json("408",[],"请求无效，请重新刷新页面"));
            }
        }else{
            header('Content-type: application/json; charset=utf-8');
            exit($this->json("408",[],"请求无效，请重新刷新页面"));
        }
    }

    public function errorReturn($msg=''){
        $msg = $msg!=""?$msg:"操作有误";
        exit("<script>alert('".$msg."'); history.go(-1)</script>");
    }
}
