<?php
/**
 * 公用Controller层，所有大模块里面的Controller继承自该类
 * @author zhuangqm
 * @date 2017-02-23
 */
namespace app;

use \think\Controller;
use \think\Config;

class ComController extends Controller{

	public $params; 

    public $version = '';

    public $dev_type = ''; // 设备类型 A|I

	public function __construct(){

        parent::__construct();

        /**
        * 初始化所有传入参数
        */

        $this->params = $this->_addslashes($this->request->param());
    }

    public function setParam($param){
        $this->params = $this->_addslashes($param);
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
        //$return_str = preg_replace('/":\[\]/i','":{}',$return_str);
        //Sysparam::$return_code = $code;
        //Sysparam::$return_msg = $msg;

        return $return_str;
    }

    //仿注入处理
    public function _addslashes($param_array){

        if(is_array($param_array)){

            $trim_flag = BIND_MODULE=='api'?true:false;

            foreach($param_array as $key=>$value){
                if(is_string($value)){
                    if(!$trim_flag)
                        $value = trim($value);
                    $value = preg_replace( "@<script(.*?)</script>@is", "", $value ); 
                    $value = preg_replace( "@<iframe(.*?)</iframe>@is", "", $value ); 
                    $value = preg_replace( "@<style(.*?)</style>@is", "", $value ); 
                    $value = preg_replace( "@<script>@is", "", $value ); 
                    $value = preg_replace( "@</script>@is", "", $value );
                    //$value = htmlspecialchars($value);
                    $value = addslashes($value);
                    $param_array[$key] = $value;
                }
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
    获取接口版本
     */
    public function getVersion(){

        if($this->version==''){
            $v = '1.0.0';
            $_env = $this->getParam("_env");
            $_env = json_decode(stripslashes($_env),true);
            if(!empty($_env['_v'])){
                $this->version = substr($_env['_v'],1);
                $this->dev_type = substr($_env['_v'],0,1);
            }
            else{
                $this->version = $v;
                $this->dev_type = "I";
            }
        }
        return $this->version;
    }

    /*
    $v  api版本号 1.0.1
    $type 类型类型 A|I   android或者IOS
     */
    public function Version($v,$type=''){
        $this->getVersion();

        $v          = str_replace(".", "", $v); //控制的版本
        $version    = str_replace(".", "", $this->version); // 当前版本

        if($type==''){
            if($v<=$version)
                return true;
            else
                return false;
        }else{
            if($v<=$version && strpos($type,$this->dev_type)!==false)
                return true;
            else
                return false;
        }
        
        return false;
    }

    /*
    * 初始化Excel导出数据
    * $param = array(
                "head"
                "list"=>"",
                "offset"=>
            );
    */
    public function iniExcelData($param){

        $tmp = array();
        $tmp['excel_key']   = $this->getParam("excel_key");
        
        if(isset($param['head']) && !empty($param['head']))
            $tmp['head']        = $param['head'];

        if(isset($param['field']) && !empty($param['field'])){
            $tmp['head'] = [];
            foreach($param['field'] as $k=>$v){
                $tmp['head'][] = $v;
            }

            $tmplist = [];
            foreach($param['list'] as $k=>$v){
                $tmp_list = [];
                foreach($param['field'] as $k_f=>$v_f){
                    $tmp_list[$k_f] = $v[$k_f];
                }
                $tmplist[$k] = $tmp_list;
            }
            $param['list'] = $tmplist;
        }

        $tmp['data']        = $param['list'];
        $tmp['count']       = count($param['list']);
        $tmp['offset']      = $param['offset'];
        $tmp['next_offset'] = $param['offset']+1;
        $tmp['filename']    = $param['filename'];
        return json_encode($tmp,JSON_UNESCAPED_UNICODE);
    }

}
