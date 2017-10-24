<?php
namespace app\tool\controller\Index;
use app\tool\ActionController;

use \think\Config;

class IndexController extends ActionController
{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    

    public function indexAction(){

        $config = Config::get("tool");
        $apiurl = Config::get("apiurl");
        $version = Config::get("version");
    	
        return $this->view([
                "config"=>$config,
                "apiurl"=>$apiurl['apiurl'],
                "version"=>$version,
            ]);
    }

    public function getApiParamAction(){

        $api = $this->params['api'];
        $mode = $this->params['mode'];
        if($api!='' && $mode!=''){
            $config = Config::get("tool");
            
            echo json_encode($config[$mode]['api'][$api],JSON_UNESCAPED_UNICODE);
            exit;
        }else{
            echo json_encode(array());
            exit;
        }
    }

    public function getRequestAction(){
        $request = $this->params;
        if($request['url']!='' && $request['api']!=''){
            //返回json格式数据
            $url = $request['url'];
            $method = $request['method']!=''?$request['method']:"post";
            $dev_type = $request['dev_type']!=''?$request['dev_type']:"I";
            $api = $request['api'];
            $_v = $dev_type.$request['version'];
            unset($request['url']);
            unset($request['method']);
            unset($request['api']);
            unset($request['dev_type']);
            unset($request['version']);
            $request['_env'] = json_encode(["_v"=>$_v],JSON_UNESCAPED_UNICODE);
            $tmpurl = $url;
            $tmpurl.="?_apiname=".$api."&";
            foreach($request as $k=>$v){
                $tmpurl.=$k."=".urlencode($v)."&";
            }

            //生成cc+ck
            $cc = time();
            $appkey = Config::get("key");
            $ck = substr(md5($cc.$appkey['app_key']).md5($cc.$appkey['app_key'].$api),substr($cc,-1,1),51);
            $tmpurl.="_cc=".$cc."&_ck=".$ck."&";
            $request['_cc'] = $cc;
            $request['_ck'] = $ck;

            $request['_apiname'] = $api;

            //增加一个checktoken参数
            $request['checktoken'] = md5($this->ParaParam($request).$appkey['app_key']);
            $tmpurl.="checktoken=".$request['checktoken']."&";

            //直接返回json数据
            if($method=='post'){
                $result = $this->post(["url"=>$url,"data"=>$request]);
            }else{
                $result = $this->get(["url"=>$tmpurl]);
            }
            
            echo json_encode([
                    "url"=>$tmpurl,
                    "result"=>$result,
                ],JSON_UNESCAPED_UNICODE);
            exit;
        }else{
            echo json_encode([],JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function post($param){
        //return '{"code":"200","msg":"操作成功","data":{"name":"谁的空间发","test":"的说法的说法"}}';
        $url = $param['url'];
        $post_data = $param['data'];
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function get($param){
        return '{

            "code":"200",
            "msg":"操作成功",
            "data":{

                "name":"谁的空间发",
                "test":"的说法的说法",
            }
        }';
        $url = $param['url'];
        
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }


    public function getApiAction(){
        $mode = $this->params['mode'];
    
        if($mode!=''){
            $config = Config::get("tool");
            $arr = [];
            foreach($config[$mode]['api'] as $k=>$v){
                $arr[] = array("api"=>$k,"name"=>$v['name']);
            }
            echo json_encode($arr,JSON_UNESCAPED_UNICODE);
            exit;
        }else{
            echo json_encode(array(),JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    //将数组转成uri字符串
    protected function ParaParam($param)
    {
        unset($param['checktoken']);
        $buff = "";
        ksort($param);
        foreach ($param as $k => $v){
            $buff .= $k. "=" . stripslashes($v) . "&";
        }
        
        $buff = $buff!=''?substr($buff,0,-1):"";
        return $buff;
    }
}
